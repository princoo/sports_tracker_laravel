<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;




class UserController extends Controller
{
    //
    public function register(Request $request)
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

        // Hash the password
        $input = $request->all();
        $hashedPassword = Hash::make($request->password);
        $user = User::create([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'password' => $hashedPassword,
            'role_id' => $defaultRole->id ?? null,  // Assign role ID
        ]);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return response()->json([
            'status' => 'success',
            'message' => "User register successfully. => {$success}" // String concatenation
        ]);
        // Log::info('Request Data:', $request->all());

    }
}
