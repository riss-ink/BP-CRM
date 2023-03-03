<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProduct extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function custom_fields()
    {
        return $this->morphToMany('App\CustomField', 'customizable');
    }

    public function getCustomFields()
    {
        $productData = $this->where('id', $this->id)->with('product.product_category.definitions.field_group', 'custom_fields', 'client')->first();
        $cfs = Client::where('id', $productData->client->id)->with(['client_products.custom_fields'])->get();
        $cfs = $cfs->pluck('client_products.*.custom_fields');
        $cfs = $cfs->flatten();
        foreach ($productData->product->product_category->definitions as $definition) {
            $field_group = $definition->field_group;
            $custom_field = $productData->custom_fields->where('definition_id', $definition->id)->first();
            if (!$custom_field) {
                $cf = $cfs->where('definition_id', $definition->id)->first();
                if ($cf) {
                    $custom_field = $cf;
                }
            }
            if ($definition->field_type == 'json') {
                if ($custom_field) {
                    if ($custom_field->value) {
                        $custom_field->value = json_decode($custom_field->value, true);
                    }
                }
            }


            $arr = [
                'type' => $definition->field_type,
                'def_id' => $definition->id,
                'label' => $definition->name,
                'id' => $custom_field ? $custom_field->id : '',
                'value' => $custom_field ? $custom_field->value : ''
            ];
            $formattedData[$field_group ? $field_group->name : 'default'][] = $arr;
        }
        return $formattedData;
    }
}
