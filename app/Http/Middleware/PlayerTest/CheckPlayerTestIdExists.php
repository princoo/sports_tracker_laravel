<?php

namespace App\Http\Middleware\PlayerTest;

use App\Services\PlayerTest\PlayerTestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlayerTestIdExists
{
    protected $playerTestService;
    public function __construct(PlayerTestService $playerTestService)
    {
        $this->playerTestService = $playerTestService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $playerTestId = $request->route('player_test_id');
        $playerTest = $this->playerTestService->findOne($playerTestId);
        if (!$playerTest) {
            return response()->json([
                'status' => 'error',
                'message' => 'PlayerTest does not exists',
            ], 400);
        }
        return $next($request);
    }
}
