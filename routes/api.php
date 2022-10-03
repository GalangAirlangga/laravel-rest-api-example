<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobHistoriesController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    //auth route
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
    });
    //route with middleware auth:sanctum
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::controller(AuthController::class)->prefix('auth')->group(function () {
            Route::get('/logout', 'logout');
        });
        Route::controller(DepartmentController::class)->prefix('departments')->group(function () {
            Route::get('/', 'index')->middleware(['ability:department-index']);
            Route::get('/{id}/show', 'show')->middleware(['ability:department-show'])->where('id', '[0-9]+');
            Route::post('/', 'store')->middleware(['ability:department-create']);
            Route::put('/{id}', 'update')->middleware(['ability:department-edit'])->where('id', '[0-9]+');
            Route::delete('/{id}', 'destroy')->middleware(['ability:department-delete'])->where('id', '[0-9]+');
        });
        Route::controller(PositionController::class)->prefix('positions')->group(function () {
            Route::get('/', 'index')->middleware(['ability:position-index']);
            Route::get('/{id}/show', 'show')->middleware(['ability:position-show'])->where('id', '[0-9]+');
            Route::post('/', 'store')->middleware(['ability:position-create']);
            Route::put('/{id}', 'update')->middleware(['ability:position-edit'])->where('id', '[0-9]+');
            Route::delete('/{id}', 'destroy')->middleware(['ability:position-delete'])->where('id', '[0-9]+');
        });
        Route::controller(JobHistoriesController::class)->prefix('jobs-history')->group(function () {
            Route::post('/', 'store');
            Route::get('/{idEmployee}/employee', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
        Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
            Route::get('/', 'index')->middleware(['ability:employee-index']);
            Route::get('/{id}/show', 'show')->middleware(['ability:employee-show'])->where('id', '[0-9]+');
            Route::post('/', 'store')->middleware(['ability:employee-create']);
            Route::put('/{id}', 'update')->middleware(['ability:employee-edit'])->where('id', '[0-9]+');
            Route::delete('/{id}', 'destroy')->middleware(['ability:employee-delete'])->where('id', '[0-9]+');
        });
    });
});

