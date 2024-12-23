<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FileOperationController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login');
});

Route::controller(UserController::class)->middleware(['auth:api'])->group(function () {
    Route::post('refresh', 'refresh');
    Route::get('all_users', 'all_users');
    Route::post('logout', 'logout');
});

Route::controller(GroupController::class)->middleware(['auth:api'])->prefix('group')->group(function () {
    Route::get('get', 'get');
    Route::get('users_in_group', 'users_in_group');
});

Route::controller(FileController::class)->middleware(['auth:api'])->prefix('file')->group(function () {
    Route::get('get', 'get');
    Route::get('download', 'download');
    Route::get('get_file_versions', 'get_file_versions');
    Route::get('download_version', 'download_version');
});

Route::controller(FileOperationController::class)->middleware(['auth:api'])->prefix('file_operation')->group(function () {
    Route::get('get_file_operations', 'get_file_operations');
    Route::get('get_user_operations', 'get_user_operations');

    Route::middleware(['RequestFlow'])->group(function () {
        Route::get('export_file_operations', 'export_file_operations');
        Route::get('export_user_operations', 'export_user_operations');
    });
});


Route::controller(\App\Http\Controllers\Admin\FileOperationController::class)->middleware(['auth:api'])->prefix('file_operation')->group(function () {
    Route::get('get_all_user_operations', 'get_all_user_operations');
});

