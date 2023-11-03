<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register() {

        $this->reportable(function (Throwable $e) {
            //
        });

        // better error message for ModelNotFoundException
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $input = $e->getMessage();
            $pattern = '/No query results for model \[App\\\\Models\\\\(.*?)\] (\d+)/';
            // Use preg_match to find matches
            if (preg_match($pattern, $input, $matches)) {
                $modelName = $matches[1];
                $id = $matches[2];
                $output = "$modelName $id not found";
            } else {
                $output = $input;
            }
            return response()->json([
                'message' => $output,
            ], 404);
        });
    }


}