<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccessControlMiddleware {

    /**
     * Handle the access control middleware.
     * @param mixed $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     * @description This middleware is used to filter records based on the user's role
     * @example $this->middleware('access-control:staff,member');
     */
    public function handle($request, Closure $next, ...$roles) {
        try {
            $user = $request->user();
            if ($user && in_array($user->role, $roles)) {
                // $request->merge(['user' => $user]);

                // Get data from user model
                $user_role = $user->role;
                $user_role_id = $user->{$user->role}->id;
                $user_role_location_id = $user->{$user->role}->location_id;
                $is_admin = $user_role == 'staff' ? $user->{$user->role}->is_admin : 0;
                // dd($user_role, $user_role_id, $user_role_location_id, $is_admin);

                // Merge data into request
                $request->merge([
                    'role' => $user_role,
                    'role_id' => $user_role_id,
                    'role_isAdmin' => $is_admin,
                    'role_location_id' => $user_role_location_id,
                ]);
                // dd($request->all());

                // Return the request
                return $next($request);
            }

            //
            // If no user is logged in, return error
            else {
                return response()->json([
                    'message' => 'Access denied',
                ], 403);
            }
        }

        // 
        // If no user is logged in, return error
        catch (\Throwable $th) {
            return response()->json([
                'message' => 'AccessControlMiddleware error',
                'error' => $th->getMessage(),
            ], 403);
        }
    }

}