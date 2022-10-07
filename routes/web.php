<?php
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\DashboardConteroller;
use App\Http\Controllers\web\MarketConteroller;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\TopupController;
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

Route::get('/', [AuthController::class,'loginView']);
Route::post('/login-post',[AuthController::class,'loginPost']);
Route::get('/register', [AuthController::class,'registView']);

Route::get('/dashboard',[DashboardConteroller::class,'index']);
Route::resource('/request-market',MarketConteroller::class);
Route::get('/topup-diamon',[TopupController::class,'diamon']);
Route::get('/topup-pangan',[TopupController::class,'pangan']);
Route::resource('/product',ProductController::class);