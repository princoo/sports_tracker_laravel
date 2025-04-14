<?php

namespace App\Http\Middleware\Player;

use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlayerExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $player_id = $request->route('player_id') ?? $request->input('player_id');
        if (!$player_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID for player is required.',
            ], 400);
        }

        if (!Player::where('id', $player_id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Player with this ID does not exist.',
            ], 404);
        }
        return $next($request);
    }
}
