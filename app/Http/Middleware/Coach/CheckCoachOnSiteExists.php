<?php

namespace App\Http\Middleware\Coach;

use App\Services\Coach\CoachOnSiteService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCoachOnSiteExists
{
    /**
     * The coach on site service instance.
     *
     * @var CoachOnSiteService
     */
    protected $coachOnSiteService;

    /**
     * Create a new middleware instance.
     *
     * @param CoachOnSiteService $coachOnSiteService
     * @return void
     */
    public function __construct(CoachOnSiteService $coachOnSiteService)
    {
        $this->coachOnSiteService = $coachOnSiteService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = $request->input('user_id') ?? $request->route('user_id');

        if (!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID is required.',
            ], 400);
        }

        $user = $this->coachOnSiteService->findSiteCoach($user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this ID not found',
            ], 400);
        }

        if ($user->role->role_name !== 'COACH') {

            return response()->json([
                'status' => 'error',
                'message' => 'User must be a coach',
            ], 400);
        }

        if ($user->coach && $request->isMethod('post')) {
            return response()->json([
                'status' => 'error',
                'message' => "Selected coach already assigned to a site ({$user->coach->site->name}).",
            ], 400);
        }

        if (!$user->coach && $request->isMethod('delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selected coach is not assigned to any site.',
            ], 400);
        }

        return $next($request);
    }
}
