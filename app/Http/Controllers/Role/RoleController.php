<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    /**
     * Construct the controller with RoleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function changeUserRole(Request $request, string $userId)
    {
        $request->validate([
            'roleId' => 'required|uuid|exists:roles,id'
        ]);

        $user = $this->roleService->changeUserRole($userId, $request->roleId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'User role changed successfully',
            'data' => $user
        ]);
    }
}
