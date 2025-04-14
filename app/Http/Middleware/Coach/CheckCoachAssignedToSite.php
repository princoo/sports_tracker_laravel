<?php

namespace App\Http\Middleware\Coach;

use App\Services\Coach\CoachOnSiteService as CoachCoachOnSiteService;
use Closure;
use Illuminate\Http\Request;
use App\Services\CoachOnSiteService;
use Symfony\Component\HttpFoundation\Response;

class CheckCoachAssignedToSite
{
    protected $coachOnSiteService;

    public function __construct(CoachCoachOnSiteService $coachOnSiteService)
    {
        $this->coachOnSiteService = $coachOnSiteService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->role->role_name === 'COACH') {
            $site_id = $request->route('site_id'); // Get the siteId from the route parameters
            $coachAssigned = $this->coachOnSiteService->checkCoachOnSite($user->id, $site_id);

            if (!$coachAssigned) {
                return response()->json([
                    'message' => 'You do not have permission to access this site',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}