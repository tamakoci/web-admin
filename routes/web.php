<?php

use App\Http\Controllers\api\v1\TransactionController as V1TransactionController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BankController;
use App\Http\Controllers\web\DashboardConteroller;
use App\Http\Controllers\web\LandingController;
use App\Http\Controllers\web\ManageUserController;
use App\Http\Controllers\web\MarketConteroller;
use App\Http\Controllers\web\PakanTernakController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ReferalsController;
use App\Http\Controllers\web\TernakController;
use App\Http\Controllers\web\TopupDiamonController;
use App\Http\Controllers\web\TopupPakanController;
use App\Http\Controllers\web\TopupPanganController;
use App\Http\Controllers\web\TransactionController;
use App\Http\Controllers\web\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/cron-produksi-ternak',[CronController::class,'produksiTernak']);


Route::get('payment',[PaymentGatewayController::class,'payment']);
Route::get('process',[PaymentGatewayController::class,'process']);
Route::get('success',[PaymentGatewayController::class,'success']);
Route::get('cancel',[PaymentGatewayController::class,'cancel']);

Route::get('tt',[V1TransactionController::class,'trxDiamon']);


Route::get('/',[LandingController::class,'index']);
Route::get('/login', [AuthController::class,'loginView'])->name('login');
Route::post('/login-post',[AuthController::class,'loginPost']);
Route::get('/register', [AuthController::class,'registView']);
Route::post('/register-post',[AuthController::class,'registPost'])->name('regist');
Route::get('/chart', [DashboardConteroller::class,'chart']);
Route::get('user-pie',[DashboardConteroller::class,'wallets']);

Route::group(["middleware"=>"auth"],function(){
    Route::group(['prefix'=>'user'],function(){
        Route::get('/dashboard',[DashboardConteroller::class,'user']);
        Route::resource('referal',ReferalsController::class);
    });
    Route::group(['prefix'=>'admin'],function(){
        Route::get('/dashboard',[DashboardConteroller::class,'index']);
        Route::resource('/request-market',MarketConteroller::class);
        Route::resource('/topup-diamon',TopupDiamonController::class);
        Route::resource('/topup-pakan',TopupPakanController::class);
        Route::resource('/product',ProductController::class);
        Route::resource('/ternak',TernakController::class);
        Route::resource('/pakan-ternak',PakanTernakController::class);
        Route::resource('/bank',BankController::class);

        Route::resource('/manage-user',ManageUserController::class);
        Route::resource('/transaction',TransactionController::class);
    });
    Route::get('user-profile',[UserController::class,'index']);
    Route::get('user-profile/{id}',[UserController::class,'getUser']);
    Route::put('user-profile/{id}',[UserController::class,'updateUser']);
    Route::get('generate-referal',[UserController::class,'refGenerate']);
    Route::post('/logout',[AuthController::class,'logout']);
   
});

