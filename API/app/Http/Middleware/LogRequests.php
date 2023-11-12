<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests {
  public function handle($request, Closure $next) {
    // Log the request data
    // add post data to log
    // Log::info("Request Data: " . json_encode($request->all()));
    // Log::info("Your log message here" . PHP_EOL);
    // Log::info("URL: " . $request->url());

    Log::info([
      'url' => $request->url(),
      'method' => $request->method(),
      // 'fullUrl' => $request->fullUrl(),
      // 'getRequestUri' => $request->getRequestUri(),
      'request' => $request->all()]
    );

    return $next($request);
  }
}