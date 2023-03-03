<?php

namespace App\Http\Controllers\API;

use App\Team;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index(){
        $teams = Team::with('users')->get();
        foreach ($teams as $team){
            $team->clients = $team->clients()->count();
            foreach ($team->users as $user) {
                $user->roles = $user->roles()->get()->pluck('name');
            }
        }
        return response()->json($teams, 200);
    }

    public function store(Request $request)
    {
        $team = Team::create(['name' => $request->input('name')]);
        foreach ($request->input('users') as $userId){
            $user = User::find($userId);
            $team->users()->save($user);
        }
        return response()->json('',200);
    }

    public function edit(Team $team)
    {
         $users = $team->users()->get()->pluck('id');
         return response()->json(['name' => $team->name, 'users' => $users], 200);
    }

    public function update(Team $team, Request $request)
    {
        $team->update(['name' => $request->input('name')]);
        $users = $team->users()->get();
        foreach ($users as $user) {
            $user->team()->dissociate();
            $user->save();
        }
        foreach ($request->input('users') as $userId){
            $user = User::find($userId);
            $team->users()->save($user);
        }
        return response()->json('',200);
    }
}
