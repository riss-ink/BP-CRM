<?php

namespace App\Http\Controllers\API;

use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function getUser()
    {
        $user = \Auth::user();
        $role = $user->roles()->get()->whereNotIn('name',['admin','user','restricted'])->pluck('name');
        $permissions = $user->getPermissionsViaRoles()->pluck('name');
        $user->role = $role->first();
        $team = $user->team()->first();
        $user->is_admin = $user->hasRole('admin');
        $user->notifs = $user->unreadNotifications()->get();
        $user->team = '';
        if($team){
            $user->team = $team->name;
        }
        $user->can = $permissions;
        return response()->json($user, 200);

    }

    public function index()
    {
        $data = User::with('team')->get();
        return response()->json($data, 200);
    }

    public function edit(User $user)
    {
        $user->roles = $user->roles()->pluck('id');
        $roles = Role::all()->pluck('name', 'id');
        $teams = Team::all()->pluck('name', 'id');
        $data = ['user' => $user, 'roles' => $roles, 'teams' => $teams];
        return response()->json($data, 200);
    }

    public function update(User $user, Request $request)
    {
        $roleIds = $request->input('roles');
        $roles = Role::whereIn('id', $roleIds)->get()->pluck('name')->toArray();
        $updateData = $request->all();
        unset($updateData['roles']);
        $user->update($updateData);
        $user->syncRoles($roles);
        return response()->json('', 200);
    }

    public function markNotifs(User $user)
    {
        $user->unreadNotifications->markAsRead();
        return response()->json('', 200);
    }
}
