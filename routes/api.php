<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LeaveTypeController;
use App\Http\Controllers\Api\LeaveBalanceController;

// PUBLIC ROUTES
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // Logged-in user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // USER ONLY ROUTES
    Route::middleware('role:user')->group(function () {
        Route::get('/user-dashboard-data', function () {
            return response()->json([
                'message' => 'User dashboard API working'
            ]);
        });
    });

    // ADMIN ONLY ROUTES
    Route::middleware('role:admin')->group(function () {

        /**
         * USER MANAGEMENT
         */
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);


        /**
         * LEAVE TYPE MANAGEMENT
         */
        Route::get('/leave-types', [LeaveTypeController::class, 'index']);
        Route::post('/leave-types', [LeaveTypeController::class, 'store']);
        Route::put('/leave-types/{id}', [LeaveTypeController::class, 'update']);
        Route::delete('/leave-types/{id}', [LeaveTypeController::class, 'destroy']);


        /**
         * LEAVE BALANCE MANAGEMENT
         * (Assign yearly leave quota per user)
         */
        Route::get('/users/{id}/leave-balances', [LeaveBalanceController::class, 'getLeaveBalances']);
        Route::post('/users/{id}/leave-balances', [LeaveBalanceController::class, 'assignLeaveBalance']);
    });

    // Logout
    Route::post('/logout', [AuthApiController::class, 'logout']);
});
