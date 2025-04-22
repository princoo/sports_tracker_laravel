<?php

namespace App\Http\Middleware\TestSession;

use Closure;
use Illuminate\Http\Request;
use App\Services\TestSession\TestSessionService;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSessions
{
    protected $testSessionService;
    public function __construct(TestSessionService $testSessionService)
    {
        $this->testSessionService = $testSessionService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Date for test session is required'], 400);
        }
        $activeSessions = $this->testSessionService->findActive($date);
        if ($activeSessions->count() > 0) {
            return response()->json(['error' => 'You already have an active session.'], 400);
        }
        $sessionsOnSameDay = $this->testSessionService->findOnSameDay($date);
        if ($sessionsOnSameDay->count() > 0) {
            return response()->json(['error' => 'You have already set a session for this day.'], 400);
        }
        return $next($request);
    }
}
