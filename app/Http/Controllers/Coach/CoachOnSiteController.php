<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\CoachOnSiteService;
use Illuminate\Http\Request;

class CoachOnSiteController extends Controller
{
    protected $CoachOnSiteService;
    public function __construct(CoachOnSiteService $CoachOnSiteService)
    {
        $this->CoachOnSiteService = $CoachOnSiteService;
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'site_id' => 'required|uuid|exists:sites,id',
        ]);
        $coachOnSite =  $this->CoachOnSiteService->create($request->all());

        return response()->json([
            'message' => 'Coach on site created successfully',
            'data' => $coachOnSite
        ]);
    }

    public function findAll()
    {
        $coachOnSite =  $this->CoachOnSiteService->findAll();

        return response()->json([
            'message' => 'Coaches on site retrieved successfully',
            'data' => $coachOnSite
        ]);
    }
    public function findOne(string $id)
    {
        $coachOnSite =  $this->CoachOnSiteService->findOne($id);
        return response()->json([
            'message' => 'Coach on site retrieved successfully',
            'data' => $coachOnSite
        ]);
    }
    public function remove(string $user_id)
    {
        $coachOnSite =  $this->CoachOnSiteService->remove($user_id);
        return response()->json([
            'message' => 'Coach on site removed successfully',
            'data' => $coachOnSite
        ]);
    }
}
