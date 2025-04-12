<?php

namespace App\Services\Coach;

use App\Models\CoachOnCenter;
use App\Models\User;
use Illuminate\Support\Collection;

class CoachOnSiteService
{
    public function create(array $data): CoachOnCenter
    {
        return CoachOnCenter::create($data);
    }

    public function findSiteCoach(string $id): ?User
    {
        return User::with('coach.site', 'role')->find($id);
    }
    public function findAll()
    {
        return CoachOnCenter::with('user')->get();
    }
    public function findOne(string $id): ?CoachOnCenter
    {
        return CoachOnCenter::with('user', 'site')->find($id);
    }

    public function checkCoachOnSite(string $userId, string $siteId): ?CoachOnCenter
    {
        return CoachOnCenter::where('user_id', $userId)->where('site_id', $siteId)->first();
    }

    public function isCoachRelatedToPlayer(string $userId, string $player_id)
    {
        $coachOnSite = CoachOnCenter::where('user_id', $userId)
            ->whereHas('site.players', function ($query) use ($player_id) {
                $query->where('id', $player_id);
            })
            ->first();

        return (bool) $coachOnSite;
    }

    public function remove(string $user_id)
    {
        $coachOnSite = CoachOnCenter::where('user_id', $user_id)->first();
        if (!$coachOnSite) {
            return null;
        }
        return $coachOnSite->delete();
    }
}