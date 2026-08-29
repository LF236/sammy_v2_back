<?php

use App\Http\Controllers\MagicToken\MagicTokenController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonTypeController;
use App\Http\Controllers\RolsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signup', [UserController::class, 'store']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::prefix('magic_token')->group(function () {
	Route::post('/validate_user', [ MagicTokenController::class, 'validateUser' ]);
	Route::post('/generate_new_token', [ MagicTokenController::class, 'generateNewToken' ]);
});


Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
	Route::get('/me', [UserController::class, 'me']);
	Route::post('/logout', [UserController::class, 'logout']);
	Route::post('/refresh', [UserController::class, 'refresh']);
});


Route::prefix('permission')->group(function () {
	Route::post('/', [PermissionController::class, 'create']);
	Route::get('/', [PermissionController::class, 'get']);
	Route::get('/{id}', [PermissionController::class, 'findById']);
	Route::put('/{id}', [PermissionController::class, 'update']);
	Route::get('/count/get', [PermissionController::class, 'count']);
	Route::delete('/{id}', [PermissionController::class, 'delete']);
});


Route::prefix('roles')->group(function () {
	Route::get('/', [RolsController::class, 'get']);
	Route::get('/count/get', [RolsController::class, 'count']);
	Route::post('/', [RolsController::class, 'create']);
	Route::put('/{id}', [RolsController::class, 'update']);
	Route::get('/{id}', [RolsController::class, 'findById']);
	Route::delete('/{id}', [RolsController::class, 'delete']);
});

Route::prefix('user')->middleware('auth:sanctum')->group(function() {
	Route::get('/', [UserController::class, 'getUsers']);
	Route::get('/count/get', [UserController::class, 'countUsers']);
	Route::get('/find-one/{id}', [UserController::class, 'findById'])->where('id', '[0-9]+');
	Route::post('/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
});

Route::prefix('person')->middleware('auth:sanctum')->group(function() {
	Route::post('/', [PersonController::class, 'store']);
	Route::put('/', [PersonController::class, 'update']);
});

Route::prefix('catalogs')->group(function() {
	Route::get('/person_types', [PersonTypeController::class, 'get']);
	Route::post('/person_types', [PersonTypeController::class, 'store']);
	Route::get('/person_types/{id}', [PersonTypeController::class, 'getOne']);
	Route::put('person_types/{id}', [PersonTypeController::class, 'update']);
});