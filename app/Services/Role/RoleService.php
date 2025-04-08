<?php

namespace App\Services\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

class RoleService
{
    /**
     * Get all roles
     */
    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    /**
     * Get role by name
     */
    public function getRoleByName(string $roleName): ?Role
    {
        return Role::where('role_name', $roleName)->first();
    }

    /**
     * Assign role to user
     */
    public function changeUserRole(string $userId, string $roleId)
    {
        $user = User::find($userId);

        if (!$user) {
            return null;
        }

        $user->role_id = $roleId;
        $user->save();

        // Return the updated user with the role relationship
        return User::with('role')->find($userId);
    }
    public function getUserRole(string $userId)
    {
        $user = User::with('role')->find($userId);
        if (!$user) {
            return null;
        }

        return ['role' => $user->role];
    }
}
