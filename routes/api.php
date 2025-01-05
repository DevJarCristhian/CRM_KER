<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Access\RolesController;
use App\Http\Controllers\Api\Access\UsersController;
use App\Http\Controllers\Api\People\DependentController;

// use App\Http\Controllers\Api\Users\RolesController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'get']);
        Route::post('store', [UsersController::class, 'store']);
        Route::put('update/{id}', [UsersController::class, 'update']);
        Route::get('roles', [UsersController::class, 'getRoles']);
    });
    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'get']);
        Route::post('store', [RolesController::class, 'store']);
        Route::put('update/{id}', [RolesController::class, 'update']);
        Route::get('permissions', [RolesController::class, 'getPermissions']);
    });
    Route::prefix('dependent')->group(function () {
        Route::get('/', [DependentController::class, 'get']);
        Route::post('store', [DependentController::class, 'store']);
        Route::put('update/{id}', [DependentController::class, 'update']);
        // Route::get('permissions', [DependentController::class, 'getPermissions']);
    });
    //     // Route::prefix('logistic')->group(function () {
    //     //     Route::prefix('stock')->group(function () {
    //     //         Route::prefix('ess')->group(function () {
    //     //             Route::get('/', [StockEssController::class, 'get']);
    //     //             Route::post('export-to-excel', [StockEssController::class, 'exportToExcel']);
    //     //         });
    //     //     });
    //     // });
});
