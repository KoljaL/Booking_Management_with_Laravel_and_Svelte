<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class QueryLogMiddleware {
    public function handle($request, Closure $next) {
        // Start the timer
        $start = microtime(true);

        // Enable the query log
        DB::connection()->enableQueryLog();

        // Run the request and collect the response
        $response = $next($request);

        // Disable and get the query log
        $queryLog = DB::getQueryLog();

        // Calculate the execution time
        $executionTime = round((microtime(true) - $start) * 1000, 2);

        // Add the query log to the JSON response
        if ($response->headers->get('Content-Type') === 'application/json') {
            $content = json_decode($response->getContent(), true);
            $content['debug']['response_time'] = $executionTime . ' ms';
            $content['debug']['request'] = $request->all();
            $content['debug']['query_log'] = $queryLog;
            $response->setContent(json_encode($content));
        }

        return $response;
    }
}