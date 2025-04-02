<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;





class UserController extends Controller
{
    public function register(Request $request, RoleService $roleService)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'user_name' => 'required|string|unique:users,user_name',
            'password' => 'required|min:6',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string',
            'nationality' => 'nullable|string',
            'gender' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // get all roles
        $defaultRole = $roleService->getRoleByName('USER');

        // Hash the password
        $hashedPassword = Hash::make($request->password);

        $user = User::create([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'password' => $hashedPassword,
            'role_id' => $defaultRole->id,
        ]);
        $userprofile = Profile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'gender' => $request->gender,
        ]);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->user_name;
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'profile' => $userprofile,
            'toke' => $success
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $user->createToken('MyApp')->accessToken;
        return response()->json([
            'message' => 'User login successfully',
            'token' => $token,
            'user' => $user->name
        ]);
    }
}
