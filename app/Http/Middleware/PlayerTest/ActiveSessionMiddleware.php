<?php

namespace App\Http\Middleware\PlayerTest;

use App\Services\PlayerTest\PlayerTestService;
use App\Services\TestSession\TestSessionService;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveSessionMiddleware
{
    protected $testSessionService;

    public function __construct(TestSessionService $testSessionService)
    {
        $this->testSessionService = $testSessionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there is an active session
        $activeSession = $this->testSessionService->findActiveSessions();

        if (!$activeSession) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active session available.',
            ], 400);
        }

        // Add session ID to the request for later use in controllers
        $request->merge(['session_id' => $activeSession->id]);

        return $next($request);
    }
}
