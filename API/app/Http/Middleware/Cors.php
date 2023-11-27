<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors {
    public function handle(Request $request, Closure $next): Response {
        // dd('cors middleware');
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        // ->header('Access-Control-Allow-Credentials', 'true');
    }
}



// ->header('Access-Control-Allow-Origin', '*')
// ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
// ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');