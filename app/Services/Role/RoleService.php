<?php

namespace App\Services\Role;

use App\Models\Role;
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
    // public function assignRoleToUser(string $userId, string $roleName): bool
    // {
    //     // try {
    //     //     $role = $this->getRoleByName($roleName);
    //     //     if (!$role) {
    //     //         return false;
    //     //     }

    //     //     $user = app()->make(\App\Services\User\UserService::class)->getUserById($userId);
    //     //     if (!$user) {
    //     //         return false;
    //     //     }

    //     //     $user->roles()->syncWithoutDetaching([$role->id]);
    //     //     return true;
    //     // } catch (\Exception $e) {
    //     //     $this->handleException($e, 'Error assigning role to user');
    //     //     return false;
    //     // }
    // }
}
