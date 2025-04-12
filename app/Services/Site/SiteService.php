<?php

namespace App\Services\Site;

use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Collection;

class SiteService
{
    /**
     * Get all sites
     */
    public function getAllSites()
    {
        return Site::with([
            'coaches.user',
            'coaches.user.role',
            'coaches.user.profile',
        ])->get();
    }

    public function createSite(array $data): Site
    {
        return Site::create($data);
    }

    // this will check the user that has the role (inside role role name will be coach) and has no site
    public function findCoachesWithNoSite(): Collection
    {
        return User::with('role', 'profile')
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'COACH');
            })
            // ->doesntHave('site')
            ->get();
    }
    public function findById(string $id): ?Site
    {
        return Site::find($id);
    }
    public function update(string $id, array $data): ?Site
    {
        $site = Site::find($id);
        if (!$site) {
            return null;
        }
        $site->update($data);
        return $site;
    }
    public function remove(string $id): ?bool
    {
        $site = Site::find($id);
        if (!$site) {
            return null;
        }
        return $site->delete();
    }
}
