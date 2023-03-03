<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->pluck('name', 'id');
        $data = ['roles' => $roles, 'permissions' => $permissions];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => strtolower($request->input('name'))]);
        $permissionIds = $request->input('permissions');
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');
        $role->syncPermissions($permissions);
        return response()->json('', 200);
    }

    public function update(Role $role, Request $request)
    {
        $role->update(['name' => strtolower($request->input('name'))]);
        $permissionIds = $request->input('permissions');
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');
        $role->syncPermissions($permissions);
        return response()->json('', 200);

    }

    public function delete(Role $role)
    {
        $role->delete();
        return response()->json('', 200);
    }
}
