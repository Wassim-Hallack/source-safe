<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(UserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::controller(UserController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('get_users', 'get_users');
    Route::post('logout', 'logout');
});

Route::controller(GroupController::class)->middleware(['auth:sanctum'])->prefix('group')->group(function () {
    Route::get('get', 'get');
});

Route::controller(FileController::class)->middleware(['auth:sanctum'])->prefix('file')->group(function () {
    Route::get('get', 'get');
    Route::post('add', 'add');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
