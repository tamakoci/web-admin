<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\BeriPakanController;
use App\Http\Controllers\api\v1\MarketController;
use App\Http\Controllers\api\v1\ProdukTernakController;
use App\Http\Controllers\api\v1\ReferalsCntroller;
use App\Http\Controllers\api\v1\ReferalsController;
use App\Http\Controllers\api\v1\TernakController;
use App\Http\Controllers\api\v1\TopupController;
use App\Http\Controllers\api\v1\TransactionController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\WithdrawController;
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
    Route::get('test',[UserController::class,'getUserTernak']);
    Route::get('user-ternak-detail/{id}',[TernakController::class,'userTernakDetail']);
    Route::get('bank-list',[WithdrawController::class,'bankList']);
    Route::get('trx-details/{id}',[TransactionController::class,'trxDetails']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('buy-diamon',[TopupController::class,'buyDiamon']);
        Route::post('buy-pakan',[TopupController::class,'buyPakan']);
        Route::post('buy-ternak',[TernakController::class,'buyTernak']);
        Route::get('user-info', [UserController::class, 'get_user']);
        Route::post('beri-pakan',[BeriPakanController::class,'beriPakan']);
        Route::post('ambil-produk',[ProdukTernakController::class,'collectProduk']);
        Route::get('user-bisnis',[UserController::class,'bisnisUser']);
        Route::get('tutor-update',[UserController::class,'updateTutor']);
        Route::get('user-ternak',[TernakController::class,'userTernak']);
        Route::post('market-sell',[MarketController::class,'sell']);
        Route::post('user-bank-post',[WithdrawController::class,'userBank']);
        Route::get('user-bank-get',[WithdrawController::class,'userBankFind']);
        Route::post('withdraw',[WithdrawController::class,'withdraw']);
        
        Route::get('referals',[ReferalsController::class,'index']);
        Route::get('collect-bonus/{id}',[ReferalsController::class,'collectBonus']);
        Route::get('trx-inquiry',[TransactionController::class,'trxLog']);
        Route::get('wd-inquiry',[WithdrawController::class,'wdLog']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix'=>'v2'],function(){

    Route::get('trx-details/{id}',[TransactionController::class,'trxDetailsV2']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('buy-diamon',[TransactionController::class,'trxDiamon']);
        Route::get('trx-inquiry',[TransactionController::class,'trxLogV2']);
    });
});

