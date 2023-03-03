<?php

namespace App\Http\Controllers\API;

use App\ClientLogin;
use App\CustomField;
use App\Definition;
use App\Enums\ClientStatus;
use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;

class ClientController extends Controller
{
    public function create()
    {
        $cfs = [];
        $definitions = Definition::where('type', 'client')->with('field_group')->get();
        foreach ($definitions as $definition) {
            $field_group = $definition->field_group;

            $temp_custom_field = [
                'definition_id' => $definition->id,
                'type' => $definition->field_type,
                'id' => '',
                'label' => $definition->name,
                'value' => ''
            ];
            if ($temp_custom_field['type'] == 'json') {
                $temp_custom_field['value'] = [''];
            }
            $cfs[$field_group ? $field_group->name : 'default'][] = $temp_custom_field;
        }
        return response()->json($cfs, 200);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contacts' => 'required'

        ]);
        $request = $request->toArray();
        $cfs = array_pull($request, 'custom_fields');
        $logins = array_pull($request, 'logins');
        $client = Client::create($request);
        foreach ($cfs as $cf) {
            if ($cf['type'] == 'json') {
                $cf['value'] = json_encode($cf['value']);
            }

            $customField = new CustomField();
            $customField->definition_id = $cf['definition_id'];
            $customField->value = $cf['value'];
            $customField->save();
            $customField->clients()->attach($client->id);
        }
        foreach ($logins as $login) {
            if(!array_filter($login)){
                continue;
            }
            $client_login = ClientLogin::create($login);
            $client->logins()->save($client_login);
        }

        return response($client->id, 200);
    }

    public function edit(Client $client)
    {
        $cfs = $client->custom_fields()->get();
        $definitions = Definition::where('type', 'client')->with('field_group')->get();
        $logins = $client->logins()->get();
        $client->logins = $logins;
        if (!$client->business_hours) {
            $client->business_hours = [];
        }
        $custom_fields = [];
        foreach ($definitions as $definition) {
            $field_group = $definition->field_group;

            $temp_custom_field = [
                'definition_id' => $definition->id,
                'type' => $definition->field_type,
                'id' => '',
                'label' => $definition->name,
                'value' => ''
            ];
            $cf = $cfs->where('definition_id', $definition->id)->first();
            if ($temp_custom_field['type'] == 'json') {
                $temp_custom_field['value'] = [''];
            }
            if ($cf) {
                $temp_custom_field['id'] = $cf->id;
                $temp_custom_field['value'] = $cf->value;
                if ($definition->field_type == 'json') {
                    $temp_custom_field['value'] = json_decode($temp_custom_field['value'], true);
                }
            }
            $custom_fields[$field_group ? $field_group->name : 'default'][] = $temp_custom_field;
        }
        $client->custom_fields = $custom_fields;
        return response()->json($client, 200);
    }

    public function update(Client $client, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contacts' => 'required'
        ]);
        $request = $request->toArray();

        $cfs = array_pull($request, 'custom_fields');
        $logins = array_pull($request, 'logins');
        $client->update($request);
        if ($cfs) {
            foreach ($cfs as $cf) {
                if ($cf['type'] == 'json') {
                    $cf['value'] = json_encode($cf['value']);
                }
                if ($cf['id']) {
                    $customField = CustomField::find($cf['id']);
                    $customField->update(['value' => $cf['value']]);
                } else {
                    $customField = new CustomField();
                    $customField->definition_id = $cf['definition_id'];
                    $customField->value = $cf['value'];
                    $customField->save();
                    $customField->clients()->attach($client->id);
                }
            }
        }
        if ($logins) {
            foreach ($logins as $login) {
                if(!array_filter($login)){
                    continue;
                }
                if (!empty($login['id'])) {
                    $client_login = ClientLogin::find($login['id']);
                    $client_login->update(array_except($login, ['id', 'client_id', 'created_at', 'updated_at']));
                } else {
                    $client_login = ClientLogin::create($login);
                    $client->logins()->save($client_login);
                }
            }
        }
        return response('', 200);
    }

    public function index(Request $request)
    {
        $user = \Auth::user();
        $teamId = $user->team_id;
        $data['teams'] = Team::all()->pluck('name', 'id');
        if ($request->has('teamId')) {
            $teamId = $request->get('teamId');
        }
        if ($teamId == '0') {
            $data['clients'] = Client::all(['id', 'name', 'current_url', 'email', 'phone', 'status']);

        } else {
            $data['clients'] = Client::where('team_id', $teamId)->select(['id', 'name', 'current_url', 'email', 'phone', 'status'])->get();
        }
        foreach ($data['clients'] as $key => $client) {
            $data['clients'][$key]->status = ClientStatus::getDescription($client->status);
            $data['clients'][$key]->user = Client::find($client->id)->user()->first();
        }
        return response()->json($data, 200);
    }

    public function view(Client $client)
    {
        $client = $client->where('id', $client->id)->with(['logins', 'client_products.product.product_category'])->first();
        $client->gmb = $client->getCustomFieldByName('Google My Business Access');
        return response()->json($client, 200);
    }
}
