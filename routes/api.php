<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionReversalController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/deposit', [WalletController::class, 'deposit']);
    Route::post('/transfer', [TransactionController::class, 'transfer']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/reverse/{id}', [TransactionReversalController::class, 'reverse']);
});
