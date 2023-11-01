<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    // register function, gets a token and a password via post request
    // if this token exixts in the user table add the password to the user

    public function register(Request $request) {
        $request->validate([
            // 'invite_token' => 'required|string|min:8|confirmed',
            'invite_token' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('invite_token', $request->invite_token)->first();

        // dd($user);
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->invite_token = null;
            $user->save();
            return response()->json([
                'user' => $user,
                'request' => $request->token,
                'message' => 'Password added to user',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Token not fosund',
                'user' => $user,
                'requesr' => $request->all(),
                // to-do remove this
            ], 404);
        }

    }


    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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