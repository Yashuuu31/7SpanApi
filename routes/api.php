<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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

Route::group(['prefix' => "auth"], function($val){
    Route::post("/login", [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:api', 'is_user_active']], function(){
    Route::group(['prefix' => "user", 'middleware' => ['is_admin']], function(){
        Route::post('/', [UserController::class, 'index']);
        Route::post('store', [UserController::class, 'store']);
        Route::post('update', [UserController::class, 'update']);
        Route::post('destroy', [UserController::class, 'destroy']);
        Route::post('show', [UserController::class, 'show']);
    });

    Route::post('profile/update', [UserController::class, 'profile_update']);
});
