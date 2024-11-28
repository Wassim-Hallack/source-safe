<?php

use App\Http\Controllers\AddFileRequestController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupInvitationController;
use App\Http\Controllers\FileOperationController;
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

Route::controller(UserController::class)->middleware(['auth:api'])->group(function () {
    Route::post('refresh', 'refresh');
    Route::post('logout', 'logout');
});

Route::controller(GroupController::class)->middleware(['auth:api'])->prefix('group')->group(function () {
    Route::post('create', 'create');
    Route::get('get', 'get');
    Route::get('users_out_group', 'users_out_group');
    Route::get('users_in_group', 'users_in_group');
});

Route::controller(GroupInvitationController::class)->middleware(['auth:api'])->prefix('group_invitation')->group(function () {
    Route::post('create', 'create');
    Route::post('invitation_response', 'invitation_response');
    Route::get('get', 'index');
});

Route::controller(FileController::class)->middleware(['auth:api'])->prefix('file')->group(function () {
    Route::get('get', 'get');
    Route::post('add', 'add');
    Route::post('edit', 'edit');
    Route::delete('destroy', 'destroy');
    Route::post('check_in', 'check_in');
    Route::get('download', 'download');
});

Route::controller(AddFileRequestController::class)->middleware(['auth:api'])->prefix('add_file_request')->group(function () {
    Route::get('get', 'get');
    Route::post('response', 'response');
});

Route::controller(FileOperationController::class)->middleware(['auth:api'])->prefix('file_operation')->group(function () {
    Route::get('get_file_operations', 'get_file_operations');
    Route::get('get_user_operations', 'get_user_operations');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
