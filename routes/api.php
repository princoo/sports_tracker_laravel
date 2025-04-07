<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserExists;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [UserController::class, 'register'])->middleware(CheckUserExists::class);
Route::post('/login', [UserController::class, 'login']);

Route::get('/ok', function (Request $request, Response $response) {
    return response()->json([
        'status' => 'success',
        'message' => 'This is a JSON response',
    ]);
});
