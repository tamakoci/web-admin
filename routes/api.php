<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\MarketController;
use App\Http\Controllers\api\v1\TernakController;
use App\Http\Controllers\api\v1\TopupController;
use App\Http\Controllers\api\v1\UserController;
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

Route::group(['prefix'=>'v1'],function(){
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('harga-pakan',[TopupController::class,'TopupPakan']);
    Route::get('harga-diamon',[TopupController::class,'TopupDiamon']);
    Route::get('request-market',[MarketController::class,'market']);
    Route::get('get-ternak',[TernakController::class,'getTernak']);
    Route::get('get-pakan-ternak/{id}',[TernakController::class,'getPakanTernak']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('buy-diamon',[TopupController::class,'buyDiamon']);
        Route::post('buy-pakan',[TopupController::class,'buyPakan']);
        Route::post('buy-ternak',[TernakController::class,'buyTernak']);
        Route::get('user-info', [UserController::class, 'get_user']);
        Route::get('user-ternak',[TernakController::class,'userTernak']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

