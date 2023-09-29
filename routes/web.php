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
use App\Http\Controllers\api\v1\TransactionController as TransactionControllerApi;
use App\Http\Controllers\web\TelurController as WebTelurController;
use App\Http\Controllers\web\UserController;
use App\Http\Controllers\web\WithdrawlController;
use App\Models\Ternak;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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
Route::get('test-query',function(){
    $userBanks = DB::table('user_banks')
            ->select('nama_bank', 'account_name', 'account_number', DB::raw('COUNT(account_name) as COUNT'))
            ->where('nama_bank','!=','BANK DUMMY')
            ->groupBy('nama_bank', 'account_name', 'account_number')
            ->havingRaw('COUNT(account_name) > 1')
            ->get();
        $table = [];
        foreach ($userBanks as $key => $value) {
            $checkSame = UserBank::join('users','user_banks.user_id','=','users.id')
            ->where(['nama_bank'=>$value->nama_bank,'account_name'=>$value->account_name])
            ->get();
            $table[$key+1] =[];
            foreach ($checkSame as $v) {
                    $table[$key] = [
                        'username'  => $v->username,
                        'avatar'    => $v->avatar,
                        'jml_ternak'=> $v->jml_ternak,
                        'nama_bank' => $v->nama_bank,
                        'account_name'=> $v->account_name,
                        'account_number'=> $v->account_number
                    ];
            }
        }
        dd($table);
});

Route::get('is-demo',function(){
    $user = User::where('is_demo',1)->get();
    foreach ($user as $key => $value) {
        $value->username = replaceDemo($value->username);
        $value->save();
    }
    return 'success';
});
Route::get('jam-server',function(){
    return [
        'status' => 'Jam server',
        'data'  => "The time is " . date("h:i:sa")
    ];
});

Route::get('cron-wallet',[CronController::class,'walletUser']);

Route::get('rek-acc',[CronController::class,'runRekAccEvery10Seconds']);

Route::get('create-demo',[CronController::class,'createDemoAccountAll']);

Route::get('living-cost',[CronController::class,'livingCost']);

Route::get('jualtelur/',[CronController::class,'jualTelurId']);
Route::get('peripakanID/',[CronController::class,'PakanTernakId']);

Route::get('beri-pakan-pagi',[CronController::class,'beriPakan']); //9
Route::get('beri-vaksin',[CronController::class,'beriVaksin']); //1
Route::get('bersih-kandang',[CronController::class,'bersihKandang']);//5
Route::get('ambil-telur',[CronController::class,'ambiltelur']); //6


Route::get('count-ayam',[CronController::class,'kirimBanyakAyam']);

Route::get('jual-telur',[CronController::class,'jualTelur']); //7

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

Route::get('last-telur',[WebTelurController::class,'lastHarga']);

Route::group(["middleware"=>"auth"],function(){

    Route::group(['prefix'=>'user'],function(){
        Route::get('/dashboard',[DashboardConteroller::class,'user']);
        Route::resource('referal',ReferalsController::class);
        Route::get('bank-account',[UserController::class,'bankAcc']);
        Route::resource('withdrawl',WithdrawlController::class);
        Route::get('beli-tools',[TransactionControllerApi::class,'beliToolsHarian']);
    });
    Route::group(['prefix'=>'admin'],function(){
        Route::get('/dashboard',[DashboardConteroller::class,'index']);
        Route::resource('/request-market',MarketConteroller::class);
        Route::resource('/topup-diamon',TopupDiamonController::class);
        Route::resource('/topup-pakan',TopupPakanController::class);
        // Route::resource('/product',ProductController::class);
        Route::resource('/telur',WebTelurController::class);
        Route::resource('/ternak',TernakController::class);
        Route::resource('/pakan-ternak',PakanTernakController::class);
        Route::resource('/bank',BankController::class);
        Route::resource('/transaction',TransactionController::class);
        
        // Route::resource('/notif',NotificationController::class);
        Route::get('notif',[TransactionController::class,'notifIndex']);
        Route::post('notif-post',[TransactionController::class,'notifStore'])->name('notif.post');
        Route::put('notif-edit/{id}', [TransactionController::class,'notifUpdate'])->name('notif.put');
        Route::delete('notif-delete/{id}', [TransactionController::class,'notifDel'])->name('notif.delete');
        
        Route::get('/ternak-user',[TransactionController::class,'ternakUser']);
        Route::post('beli-ayam',[TransactionController::class,'beliAyamPost'])->name('beliayam');
        
        Route::resource('/manage-user',ManageUserController::class);

        Route::get('group-by-rekening',[ManageUserController::class,'groupUser']);

        Route::get('withdraw/{status}',[WithdrawlController::class,'adminCheck']);
        Route::get('standby-withdraw',[WithdrawlController::class,'standby']);
        // Route::post('withdraw/{status}',[WithdrawlController::class,'adminCheckCommit']);
        Route::post('withdraw-post',[WithdrawlController::class,'adminCheckCommit']);

        Route::get('cek-bank',[WithdrawlController::class,'checkBank']);

    });
    Route::get('user-profile',[UserController::class,'index']);
    Route::get('user-profile/{id}',[UserController::class,'getUser']);
    Route::put('user-profile/{id}',[UserController::class,'updateUser']);
    Route::get('generate-referal',[UserController::class,'refGenerate']);
    Route::post('/logout',[AuthController::class,'logout']);
   
});

Route::get('login-web',[AuthController::class,'loginPostMasterplan']);
