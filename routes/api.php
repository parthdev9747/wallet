<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\WalletController;

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);

    Route::middleware('auth:api')->group(function () {

        Route::post('/add-money', [WalletController::class, 'addMoney']);
        Route::post('/buy-cookie', [WalletController::class, 'buyCookie']);
        Route::get('/user-profile', [AuthenticationController::class, 'userProfile']);

        Route::post('/logout', [AuthenticationController::class, 'logout']);
    });
});
