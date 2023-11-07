<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests {
  public function handle($request, Closure $next) {
    // Log the request data
    Log::info('Request Data:', ['request' => $request->all()]);

    return $next($request);
  }
}