<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware {
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string {
        // to-do better error message
        // add json header 
        header('Content-Type: application/json');
        echo '{"error": "Unauthenticated."}';
        exit;
        // dd($request);
        // return response()->json(['error' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
    }
}