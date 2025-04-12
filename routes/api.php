<?php

use App\Http\Controllers\Coach\CoachOnSiteController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserExists;
use App\Http\Middleware\Coach\CheckCoachOnSiteExists;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\Site\CheckSiteExists;
use App\Http\Middleware\Site\CheckSiteIdExists;
use App\Http\Middleware\Site\CheckUpdatedNameExists;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [UserController::class, 'register'])->middleware(CheckUserExists::class);
Route::post('/login', [UserController::class, 'login']);
Route::patch('/roles/{userId}', [RoleController::class, 'changeUserRole'])->middleware('auth:api', RoleMiddleware::class . ':HSO');

// site routes
Route::get('/site', [SiteController::class, 'getAllSites'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH');
Route::post('/site', [SiteController::class, 'createSite'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH', CheckSiteExists::class);
Route::get('/site/{siteId}', [SiteController::class, 'findOne'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH', ); // make a middleware to check if the site id in the params exists
Route::patch('/site/{siteId}', [SiteController::class, 'update'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH', CheckUpdatedNameExists::class); 
Route::delete('/site/{siteId}', [SiteController::class, 'remove'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH'); // make a middleware to check if the site id in the params exists
Route::get('/site/free-coach', [SiteController::class, 'findCoachesWithNoSite'])->middleware('auth:api', RoleMiddleware::class . ':USER,HSO,FOOTBALL_DIRECTOR,CEO,TECHNICIAN,COACH');

// coach on site routes
Route::post('/coach-on-site', [CoachOnSiteController::class, 'create'])->middleware('auth:api', RoleMiddleware::class . ':HSO,FOOTBALL_DIRECTOR',CheckCoachOnSiteExists::class,CheckSiteIdExists::class);
Route::get('/coach-on-site', [CoachOnSiteController::class, 'findAll'])->middleware('auth:api', RoleMiddleware::class . ':HSO,FOOTBALL_DIRECTOR');
Route::get('/coach-on-site/{id}', [CoachOnSiteController::class, 'findOne'])->middleware('auth:api', RoleMiddleware::class . ':HSO,FOOTBALL_DIRECTOR');
Route::delete('/coach-on-site/{user_id}', [CoachOnSiteController::class, 'remove'])->middleware('auth:api', RoleMiddleware::class . ':HSO,FOOTBALL_DIRECTOR',checkCoachOnSiteExists::class);

