<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/signup', [UserController::class, 'register']);

Route::get('/ok', function (Request $request, Response $response) {
    return response()->json([
        'status' => 'success',
        'message' => 'This is a JSON response',
    ]);
});
