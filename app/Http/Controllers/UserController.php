<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('created_at', 'asc')->get();
        return UserResource::collection($users);
    }

    public function store(Request $request) {
        $user = User::create([
            'name' => $request->name
        ]);
        return new UserResource($user);
    }

    public function delete($id) {
        User::destroy($id);
        return 'success delete';
    }

    public function changeName(Request $request) {
        $user = User::find($request->id);
        if($user) {
            $updateName = $request->name;
            
            $user->update([
                'name' => $updateName
            ]);
        }
        
        return new UserResource($user);
    }
}
