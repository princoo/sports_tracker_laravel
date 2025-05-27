<?php

namespace App\Http\Middleware\PlayerTest;

use App\Services\PlayerTest\PlayerTestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDuplicateSessionTest
{
    protected $playerTestService;
    public function __construct(PlayerTestService $playerTestService)
    {
        $this->playerTestService = $playerTestService;
    }
    public function handle(Request $request, Closure $next): Response
    {

        $playerId = $request->route('player_id');
        // $sessionId = $request->route('session_id');
        $sessionId = $request->session_id;
        $testId = $request->route('test_id');
        $playerTest = $this->playerTestService->findUnique($sessionId, $testId, $playerId);
        if ($playerTest) {
            return response()->json([
                'status' => 'error',
                'message' => 'PlayerTest for this session already exists.',
            ], 400);
        }
        return $next($request);
    }
}
