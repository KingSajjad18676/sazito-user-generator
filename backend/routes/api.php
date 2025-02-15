<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //prefix admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::post('/create-users', [UserController::class, 'createUsers']);
    });

    Route::get('/online-users', [UserController::class, 'onlineUsers']);
    Route::get('/messages', [UserController::class, 'messages']);

    Route::post('/ping', [UserController::class, 'ping']);

});
