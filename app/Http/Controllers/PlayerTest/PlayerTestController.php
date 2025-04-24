<?php

namespace App\Http\Controllers\PlayerTest;

use App\Http\Controllers\Controller;
use App\Services\PlayerTest\PlayerTestService;
use Illuminate\Http\Request;

class PlayerTestController extends Controller
{
    protected $playerTestService;
    public function __construct(PlayerTestService $playerTestService)
    {
        $this->playerTestService = $playerTestService;
    }

    public function create(
        Request $request,
        // string $site_id,
        string $test_id,
        string $player_id,
        // array $createPlayerTestDto
    ) {
        $data = $this->playerTestService->create(
            $test_id,
            $player_id,
            $request->session_id,
            $request->user()->id,
            $request->required_metrics,
            $request->all()
        );

        return response()->json([
            'message' => 'PlayerTest created successfully',
            'data' => $data
        ]);
    }

    public function findAll()
    {
        $data = $this->playerTestService->findAll();
        return response()->json([
            'message' => 'PlayerTests retrieved successfully',
            'data' => $data
        ]);
    }

    public function findAllByPlayer(string $player_id)
    {
        $data = $this->playerTestService->findAllByPlayerId($player_id);
        return response()->json([
            'message' => 'PlayerTests for player retrieved successfully',
            'data' => $data
        ]);
    }

    public function findBySession(string $session_id)
    {
        $data = $this->playerTestService->findBySessionId($session_id);
        return response()->json([
            'message' => 'PlayerTests for session retrieved successfully',
            'data' => $data
        ]);
    }

    public function findOne(string $player_test_id)
    {
        $data = $this->playerTestService->findOne($player_test_id);
        return response()->json([
            'message' => 'PlayerTest retrieved successfully',
            'data' => $data
        ]);
    }

    public function update(string $site_id, string $player_metric_id, Request $request)
    {
        $data = $this->playerTestService->update($player_metric_id, $request->required_metrics, $request->all());
        return response()->json([
            'message' => 'PlayerTest updated successfully',
            'data' => $data
        ]);
    }
    public function remove(string $player_test_id)
    {
        $this->playerTestService->remove($player_test_id);
        return response()->json([
            'message' => 'PlayerTest deleted successfully',
        ]);
    }
}
