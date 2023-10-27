<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.'],
            ], 404);
        }
        $token = $user->createToken('auth-token')->plainTextToken;

        $role = $user->role;

        if ($role == 'staff') {
            $roleData = Staff::where('user_id', $user->id)->first();
            $userData = [
                'id' => $roleData->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'phone' => $roleData->phone,
                'location_id' => $roleData->location_id,
                'is_admin' => $roleData->is_admin,
            ];
        } else {
            $roleData = Member::where('user_id', $user->id)->first();
            $userData = [
                'id' => $roleData->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'location_id' => $roleData->location_id,
            ];
        }
        $response = [
            'user' => $userData,
            'token' => $token,
        ];

        return response($response, 201);
    }
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out',
        ];
    }
}