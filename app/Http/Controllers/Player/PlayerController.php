<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Services\Player\PlayerService;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected $playerService;
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }
    public function create(Request $request,string $site_id)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|integer',
            'dob' => 'required|date',
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'foot' => 'required|string',
            'nationality' => 'required|string',
            'acad_status' => 'required|string',
            'positions' => 'required|array',
            'gender' => 'required|string',
        ]);

        // Create the player using the service
        $player = $this->playerService->create($request->all(),$site_id);

        return response()->json([
            'message' => 'Player created successfully',
            'data' => $player
        ]);
    }

    public function findAll()
    {
        $players = $this->playerService->findAll();
        return response()->json([
            'message' => 'Players retrieved successfully',
            'data' => $players
        ]);
    }

    public function findAllBySite(string $site_id)
    {
        $players = $this->playerService->findAllBySite($site_id);
        return response()->json([
            'message' => 'Players retrieved successfully',
            'data' => $players
        ]);
    }

    public function findOne(string $player_id)
    {
        $player = $this->playerService->findOne($player_id);
        if (!$player) {
            return response()->json([
                'message' => 'Player not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Player found',
            'data' => $player
        ]);
    }

    public function update(string $player_id, Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|integer',
            'dob' => 'required|date',
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'foot' => 'required|string',
            'nationality' => 'required|string',
            'acad_status' => 'required|string',
            'positions' => 'required|array',
            'gender' => 'required|string',
        ]);

        // Update the player using the service
        // log the request data
        $player = $this->playerService->update($player_id, $request->all());

        return response()->json([
            'message' => 'Player updated successfully',
            'data' => $player
        ]);
    }

    public function remove(string $player_id)
    {
        // Remove the player using the service
        $this->playerService->remove($player_id);

        return response()->json([
            'message' => 'Player removed successfully'
        ]);
    }
}
