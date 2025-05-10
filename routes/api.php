<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\CollegeController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\EntityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});

Route::apiResource('entities', EntityController::class)->except('destroy');
Route::apiResource('branches', BranchController::class)->except('destroy');
Route::apiResource('colleges', CollegeController::class)->except('destroy');
Route::apiResource('departments', DepartmentController::class)->except('destroy');
