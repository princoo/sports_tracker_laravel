<?php

namespace App\Http\Controllers\TestSession;

use App\DTOs\CreateTestSessionDto;
use App\DTOs\UpdateTestSessionDto;
use App\Http\Controllers\Controller;
use App\Services\TestSession\TestSessionService;
use Illuminate\Http\Request;

class TestSessionController extends Controller
{
    protected $testSessionService;
    public function __construct(TestSessionService $testSessionService)
    {
        $this->testSessionService = $testSessionService;
    }
    public function create(Request $request)
    {
        //validate the request
        $request->validate([
            'tests' => 'required|array',
            'date' => 'required|date',
        ]);
        $dto = new CreateTestSessionDto(
            tests: $request->input('tests'),
            date: $request->input('date')
        );
        $testSession = $this->testSessionService->create($dto);
        return response()->json([
            'message' => 'Test session created successfully',
            'data' => $testSession
        ]);
    }
    public function findActive()
    {
        $activeSessions = $this->testSessionService->findActiveSessions();
        return response()->json([
            'message' => 'Active Session retrieved successfully',
            'data' => $activeSessions
        ]);
    }
    public function findAll()
    {
        $testSessions = $this->testSessionService->findAll();
        return response()->json([
            'message' => 'Test sessions retrieved successfully',
            'data' => $testSessions
        ]);
    }
    public function findOne(string $session_id)
    {
        $testSession = $this->testSessionService->findOne($session_id);
        return response()->json([
            'message' => 'Session retrieved successfully',
            'data' => $testSession
        ]);
    }
    public function update(string $session_id, Request $request)
    {
        //validate the request
        $request->validate([
            'tests' => 'array',
            'date' => 'date',
        ]);
        $dto = new UpdateTestSessionDto(
            tests: $request->input('tests'),
            date: $request->input('date')
        );
        $testSession = $this->testSessionService->update($session_id, $dto);
        return response()->json([
            'message' => 'Test session updated successfully',
            'data' => $testSession
        ]);
    }
    public function remove(string $session_id)
    {
        $this->testSessionService->remove($session_id);
        return response()->json([
            'message' => 'Test session deleted successfully',
        ]);
    }
}
