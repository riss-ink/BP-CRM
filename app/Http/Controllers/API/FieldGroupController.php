<?php

namespace App\Http\Controllers\API;

use App\FieldGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FieldGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FieldGroup::all(), 200);
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
        FieldGroup::create(['name' => $request->input('name')]);
        return response()->json('', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FieldGroup  $fieldGroup
     * @return \Illuminate\Http\Response
     */
    public function show(FieldGroup $fieldGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FieldGroup  $fieldGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(FieldGroup $fieldGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FieldGroup  $fieldGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FieldGroup $fieldGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FieldGroup  $fieldGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(FieldGroup $fieldGroup)
    {
        //
    }
}
