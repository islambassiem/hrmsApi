<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\EntityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::apiResource('entities', EntityController::class)
    ->except('destroy')
    ->middleware('auth:sanctum');

Route::apiResource('branches', BranchController::class)
    ->except('destroy')
    ->middleware('auth:sanctum');
