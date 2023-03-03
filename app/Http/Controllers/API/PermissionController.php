<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function store(Request $request)
    {
        Permission::create(['name' => strtolower($request->input('name'))]);
        return response()->json('', 200);
    }

    public function update(Permission $permission, Request $request)
    {
        $permission->update(['name' => strtolower($request->input('name'))]);
        return response()->json('', 200);

    }

    public function delete(Permission $permission)
    {
        $permission->delete();
        return response()->json('', 200);
    }
}
