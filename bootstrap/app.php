<?php

use App\Http\Middleware\CheckUserExists;
use App\Http\Middleware\ResponseFormatter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
// use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ResponseFormatter::class);
        $middleware->alias(['checkUserExists', CheckUserExists::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle HTTP exceptions
        $exceptions->render(function (HttpException $exception, Request $request) {
            $status = $exception->getStatusCode();
            $exceptionResponse = $exception->getMessage();

            return response()->json([
                'status' => false,
                'statusCode' => $status,
                'message' => $exceptionResponse ?: 'An error occurred',
                'result' => null,
            ], $status);
        });

        // Handle generic exceptions
        $exceptions->render(function (Throwable $exception, Request $request) {
            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => $exception->getMessage(),
                'result' => null,
            ], 500);
        });
    })->create();
