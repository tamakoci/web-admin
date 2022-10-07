<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\MarketController;
use App\Http\Controllers\api\v1\TopupController;
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
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/topup-pangan',[TopupController::class,'TopupPangan']);
    Route::get('/topup-diamon',[TopupController::class,'TopupDiamon']);
    Route::get('/request-market',[MarketController::class,'market']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('get-user', [AuthController::class, 'get_user']);
    });
});

