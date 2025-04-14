<?php

use App\Http\Middleware\CheckUserExists;
use App\Http\Middleware\Coach\CheckCoachAssignedToSite;
use App\Http\Middleware\Coach\CheckCoachOnSiteExists;
use App\Http\Middleware\Coach\CheckPlayerCoach;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\Player\CheckPlayerExists;
use App\Http\Middleware\ResponseFormatter;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\Site\CheckSiteExists;
use App\Http\Middleware\Site\CheckSiteIdExists;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
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
        $middleware->alias([
            'checkUserExists',
            CheckUserExists::class,
            'jwt.auth',
            JwtMiddleware::class,
            'role',
            RoleMiddleware::class,
            'CheckSiteExists',
            CheckSiteExists::class,
            'CheckSiteIdExists',
            CheckSiteIdExists::class,
            'CheckCoachOnSiteExists',
            CheckCoachOnSiteExists::class,
            'CheckPlayerExists',
            CheckPlayerExists::class,
            'CheckCoachAssignedToSite',
            CheckCoachAssignedToSite::class,
            'CheckPlayerCoach',
            CheckPlayerCoach::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle Authentication exceptions
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return response()->json([
                'status' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized. Authentication required.',
                'result' => null,
            ], 401);
        });
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
