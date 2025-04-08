<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json(['message' => 'Please Login'], 401);
            }
            
            // Find user by token using Laravel's token system
            $user = User::where('api_token', hash('sha256', $token))->with('role')->first();
            
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            // Set authenticated user
            Auth::login($user);
            
            // Also attach to request for compatibility with your existing code
            $request->merge(['user' => $user]);
            
            return $next($request);
            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}