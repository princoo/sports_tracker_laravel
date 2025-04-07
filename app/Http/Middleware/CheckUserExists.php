<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $user_name = $request->input('user_name');

        if (User::where('email', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists.',
            ], 409); 
        } elseif (User::where('user_name', $user_name)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username already exists.',
            ], 409);
        }
        return $next($request);
    }
}
