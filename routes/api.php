<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EntityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::apiResource('entity', EntityController::class);
