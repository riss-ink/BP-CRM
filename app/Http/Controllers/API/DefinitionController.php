<?php

namespace App\Http\Controllers\API;

use App\Definition;
use App\FieldGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefinitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fieldGroups = Definition::with(['field_group', 'product_categories'])->get();
        return response()->json($fieldGroups, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $field_group = $request->input('field_group_id');
        $defData = [
            'field_group_id' =>$field_group['id']? $field_group['id'] : 0,
            'name' => $request->input('name'),
            'field_type' => $request->input('field_type'),
            'type' => 'product_category'
        ];
        $definition = Definition::create($defData);
        foreach ($request->input('product_categories') as $category) {
            $definition->product_categories()->attach($category);
        }
        return response()->json($definition, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function show(Definition $definition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function edit(Definition $definition)
    {
        $categories = $definition->product_categories()->get()->pluck('id');
        $field_group = $definition->field_group()->first();
            $viewData=[
                'name' => $definition->name,
                'field_type' => $definition->field_type,
                'field_group_id' => ['name' => $field_group->name, 'id' => $field_group->id],
                'product_categories' => $categories->toArray()
        ];
            return response()->json($viewData, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Definition $definition)
    {
        $field_group = $request->input('field_group_id');
        $defData = [
            'field_group_id' => $field_group['id']? $field_group['id'] : 0,
            'name' => $request->input('name'),
            'field_type' => $request->input('field_type'),
            'type' => 'product_category'
        ];
        $definition->update($defData);
        $definition->product_categories()->detach();
        foreach ($request->input('product_categories') as $category) {
            $definition->product_categories()->attach($category);
        }
        return response()->json($definition, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Definition $definition)
    {
        //
    }
}
