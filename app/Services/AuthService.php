<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class AuthService
{
    /**
     * Validate the JWT token and return the user
     * 
     * @param string $token
     * @return User|null
     */
    public function validateToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key(config('auth.jwt_secret'), 'HS256'));
            
            // Find the user by ID from the decoded token
            $user = User::with('role')->find($decoded->sub);
            
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Invalid token');
        }
    }
}