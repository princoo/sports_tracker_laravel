<?php

namespace App\Http\Middleware\TestSession;

use Closure;
use Illuminate\Http\Request;
use App\Services\TestSession\TestSessionService;

use Symfony\Component\HttpFoundation\Response;

class CheckSessionInUse
{
    protected $testSessionService;
    public function __construct(TestSessionService $testSessionService)
    {
        $this->testSessionService = $testSessionService;
    }
    public function handle(Request $request, Closure $next): Response
    {

        $id = $request->route('session_id') ?? $request->input('session_id');
        if (!$id) {
            return response()->json(['error' => 'ID for test session is required'], 400);
        }
        $testSession = $this->testSessionService->findOne($id);
        if ($testSession->status === 'ACTIVE') {
            return response()->json(['error' => 'Cannot delete an active session'], 400);
        }
        return $next($request);
    }
}
