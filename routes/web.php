<?php

use App\Http\Controllers\api\v1\TransactionController as V1TransactionController;
use App\Http\Controllers\api\v1\WithdrawController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BankController;
use App\Http\Controllers\web\DashboardConteroller;
use App\Http\Controllers\Web\DeliverController;
use App\Http\Controllers\web\LandingController;
use App\Http\Controllers\web\ManageUserController;
use App\Http\Controllers\web\MarketConteroller;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\web\PakanTernakController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ReferalsController;
use App\Http\Controllers\web\TernakController;
use App\Http\Controllers\web\TopupDiamonController;
use App\Http\Controllers\web\TopupPakanController;
use App\Http\Controllers\web\TopupPanganController;
use App\Http\Controllers\web\TransactionController;
use App\Http\Controllers\web\UserController;
use App\Models\Ternak;
use App\Models\User;
use App\Models\UserTernak;
use Illuminate\Support\Facades\Artisan;
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
Route::get('living-cost',[CronController::class,'livingCost']);

Route::get('reset-wallet',[CronController::class,'resetWallet']);

Route::get('beri-pakan-pagi',[CronController::class,'beriPakan']);
Route::get('beri-vaksin',[CronController::class,'beriVaksin']);
Route::get('bersih-kandang',[CronController::class,'bersihKandang']);
Route::get('ambil-telur',[CronController::class,'ambiltelur']); //need check

Route::get('count-ayam',[CronController::class,'kirimBanyakAyam']);
Route::get('jual-telur',[CronController::class,'jualTelur']);

Route::get('/cron-umur-ternak',[CronController::class,'umurTernak']);
Route::get('/cron-produksi-ternak',[CronController::class,'produksiTernak']);
Route::get('/cron-status-transaksi',[CronController::class,'trxStatus']);

Route::get('payment',[V1TransactionController::class,'payment']);
Route::get('process',[V1TransactionController::class,'process']);
Route::get('success',[V1TransactionController::class,'success']);
Route::get('cancel',[V1TransactionController::class,'cancel']);

Route::get('tt',[V1TransactionController::class,'trxDiamon']);
Route::get('log/{kode}',[V1TransactionController::class,'inquiry']);

Route::get('/',[LandingController::class,'index']);
Route::get('/login', [AuthController::class,'loginView'])->name('login');
Route::post('/login-post',[AuthController::class,'loginPost']);
Route::post('/login-post-masterplan',[AuthController::class,'loginPost']);
Route::get('/register', function(){
    return redirect('login');
});
// Route::get('/register', [AuthController::class,'registView']);
Route::post('/register-post',[AuthController::class,'registPost'])->name('regist');
Route::post('/register-post-masterplan',[AuthController::class,'loginPostMasterplan'])->name('regist.masterplan');

Route::get('/chart', [DashboardConteroller::class,'chart']);
Route::get('user-pie',[DashboardConteroller::class,'wallets']);

Route::get('wd',[WithdrawController::class,'wd']);
Route::get('wd/{id}',[WithdrawController::class,'wdq']);

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
        
        // Route::resource('/notif',NotificationController::class);
        Route::get('notif',[TransactionController::class,'notifIndex']);
        Route::post('notif-post',[TransactionController::class,'notifStore'])->name('notif.post');
        Route::put('notif-edit/{id}', [TransactionController::class,'notifUpdate'])->name('notif.put');
        Route::delete('notif-delete/{id}', [TransactionController::class,'notifDel'])->name('notif.delete');
        
        Route::get('/ternak-user',[TransactionController::class,'ternakUser']);
        Route::post('beli-ayam',[TransactionController::class,'beliAyamPost'])->name('beliayam');
    });
    Route::get('user-profile',[UserController::class,'index']);
    Route::get('user-profile/{id}',[UserController::class,'getUser']);
    Route::put('user-profile/{id}',[UserController::class,'updateUser']);
    Route::get('generate-referal',[UserController::class,'refGenerate']);
    Route::post('/logout',[AuthController::class,'logout']);
   
});
