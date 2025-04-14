<?php

namespace App\Http\Middleware\Coach;

use App\Services\Coach\CoachOnSiteService as CoachCoachOnSiteService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CoachOnSiteService;

class CheckPlayerCoach
{
    protected $coachOnSiteService;

    public function __construct(CoachCoachOnSiteService $coachOnSiteService)
    {
        $this->coachOnSiteService = $coachOnSiteService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user->role->role_name === 'COACH') {
            $player_id = $request->route('player_id'); // Get the playerId from the route parameters
            $isPlayerCoach = $this->coachOnSiteService->isCoachRelatedToPlayer($user->id, $player_id);

            if (!$isPlayerCoach) {
                return response()->json([
                    'message' => 'You do not have permission to access this player',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}