<?php

use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserExists;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [UserController::class, 'register'])->middleware(CheckUserExists::class);
Route::post('/login', [UserController::class, 'login']);
Route::patch('/roles/{userId}', [RoleController::class, 'changeUserRole'])->middleware('auth:api',RoleMiddleware::class.':USER');