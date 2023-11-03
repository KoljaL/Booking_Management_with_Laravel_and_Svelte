<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        // custom macro for JSON response
        Response::macro('customJson', function ($data = [], $name = 'data', $message = '', $status = 200) {
            if (is_countable($data)) {
                $data_count = count($data);
            }
            // data is an Exception object
            // so we want to return the error message
            else {
                $data_count = null;
                $data = [
                    'message' => $data->getMessage(),
                    'file' => $data->getFile(),
                    'line' => $data->getLine(),
                    'code' => $data->getCode(),
                    // 'string' => $data->__toString(),
                    // 'trace' => $data->getTrace(),
                ];
            }
            return response()->json(['message' => $message, 'data_count' => $data_count, $name => $data], $status);

        });
    }
}
