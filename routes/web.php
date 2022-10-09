<?php
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\DashboardConteroller;
use App\Http\Controllers\web\MarketConteroller;
use App\Http\Controllers\web\PakanTernakController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\TernakController;
use App\Http\Controllers\web\TopupDiamonController;
use App\Http\Controllers\web\TopupPakanController;
use App\Http\Controllers\web\TopupPanganController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class,'loginView'])->name('login');
Route::post('/login-post',[AuthController::class,'loginPost']);
Route::get('/register', [AuthController::class,'registView']);
Route::post('/register-post',[AuthController::class,'registPost'])->name('regist');

Route::group(["middleware"=>"auth"],function(){
    Route::get('/dashboard',[DashboardConteroller::class,'index']);

    Route::resource('/request-market',MarketConteroller::class);
    Route::resource('/topup-diamon',TopupDiamonController::class);
    Route::resource('/topup-pakan',TopupPakanController::class);
    Route::resource('/product',ProductController::class);
    Route::resource('/ternak',TernakController::class);
    Route::resource('/pakan-ternak',PakanTernakController::class);
    Route::post('/logout',[AuthController::class,'logout']);
});