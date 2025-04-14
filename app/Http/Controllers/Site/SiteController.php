<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Site\SiteService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected $siteService;
    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function getAllSites(Request $request)
    {
        $user = $request->user();
        $sites = $this->siteService->getAllSites();
        return response()->json([
            'message' => 'Sites retrieved successfully',
            'data' => $sites,
        ]);
    }
    public function createSite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
        ]);

        $site = $this->siteService->createSite($request->all());

        return response()->json([
            'message' => 'Site created successfully',
            'data' => $site
        ]);
    }

    public function findCoachesWithNoSite(Request $request)
    {
        $coaches = $this->siteService->findCoachesWithNoSite();
        return response()->json([
            'message' => 'Coaches with no site',
            'data' => $coaches
        ]);
    }
    public function findOne(string $id)
    {
        $site = $this->siteService->findById($id);
        if (!$site) {
            return response()->json([
                'message' => 'Site not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Site found',
            'data' => $site
        ]);
    }
    public function update(string $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
        ]);
        $site = $this->siteService->update($id, $request->all());
        return response()->json([
            'message' => 'Site updated successfully',
            'data' => $site
        ]);
    }
    public function remove(string $id)
    {
        $site = $this->siteService->remove($id);
        return response()->json([
            'message' => 'Site deleted successfully',
            'data' => $site
        ]);
    }
}
