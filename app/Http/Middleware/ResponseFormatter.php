<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class ResponseFormatter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // If the response is not JSON, return it as is.
        if (!$response instanceof JsonResponse) {
            return $response;
        }

        // Format the JSON response
        return response()->json([
            // 'status' => true,
            'statusCode' => $response->getStatusCode(),
            'result' => $response->getData(),
        ], $response->getStatusCode());
    }
}
