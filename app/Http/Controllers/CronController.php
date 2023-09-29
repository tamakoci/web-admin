<?php

namespace App\Http\Controllers;

use App\Models\CronFail;
use App\Models\CronLog;
use App\Models\Investment;
use App\Models\Notif;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ReferalTree;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Carbon\Carbon;
use DateTime;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use function PHPSTORM_META\type;

class CronController extends Controller
{
    public function __construct()
    {
        $this->url      = env('KPAYDEVURL');
        $this->app      = env('KPAYAPP');
        $this->pass     = env('KPAYPASS');
        $this->mail     = env('KPAYEMAIL');
    }
    public function runRekAccEvery10Seconds() {
        // DB::table('user_banks')->truncate();

        $user = DB::table('users')
            ->leftJoin('user_banks', 'users.id', '=', 'user_banks.user_id')
            ->where(['users.is_demo' => 0, 'users.user_role' => 1])
            ->whereNull('user_banks.user_id')
            ->select('users.*')
            ->get();
        // User::where(['is_demo' => 0, 'user_role' => 1])->get();
        
        foreach ($user as $key => $value) {
            $this->rekAcc($value);
            sleep(1); // Sleep for 10 seconds before processing the next user
        }
        
        return 'done';
    }

    public function rekAcc($user) {
        $apiUrl = 'https://masterplan.co.id/api/rekening-info/'.$user->username;
        $response = Http::get($apiUrl);
        $userbank = UserBank::where('user_id',$user->id)->first();

        if ($response->successful() && !$userbank) {
            $rs = $response->json();
            UserBank::create([
                'user_id'           => $user->id,
                'bank_id'           => 14,
                'nama_bank'         => $rs['data']['nama_bank'],
                'account_name'      => $rs['data']['nama_akun'],
                'account_number'    => $rs['data']['no_rek'],
                'bank_city'         => $rs['data']['kota_cabang']
            ]);
            echo $user->username ."success \n";
        }
    }

    public function produksiTernak(){

        $invest = Investment::with(['userTernak','userTernak.ternak','userTernak.ternak.produk'])->where('status',1)->get();
     
        foreach ($invest as $key => $value) {
            if($value->userTernak->ternak_id != 4 ){
                $start      = date("Y-m-d H:i:s", strtotime($value->created_at));
            }else{
                $start      = date("Y-m-d H:i:s", strtotime($value->updated_at));
            }
            $now        = date("Y-m-d H:i:s");
            $end        = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($start)));

            $remains    = $value->remains;
            $collected  = $value->collected;
            $total      = $value->commision;
            $perjam     = floor($total / 24);
            $pendapatan = $remains + $collected;
            $kurang     = $total - $pendapatan;

            DB::beginTransaction();
            try {
                if($value->userTernak->ternak_id != 4){ //cek jika userternak bukan domba. domba_id = 4 ;
                    
                    if(($perjam + $remains + $collected) < $total){ //jika fee per jam (pembagian perjam) + remain (pendapatan yg masuk) lebih besar dari total_bisa_dapat
                        $update = [
                            'remains' => $remains + $perjam,
                        ];
                    
                    }else if(($perjam + $remains + $collected) > $total){
                        $update = [
                            'remains' => $remains + $kurang,
                        ];
                    }else{
                        $update = [
                            'remains' => $remains + $perjam,
                            'status'  => 0
                        ];
                    }
                    Investment::find($value->id)->update($update);
                }else{ // juka ternak domba penghasil daging
                    $tgl_beli   = date("Y-m-d H:i:s", strtotime($value->userTernak->buy_date));
                    $tgl_akhir  = date('Y-m-d H:i:s',strtotime("+7 day", strtotime($tgl_beli)));
                    if($now > $tgl_akhir){ //jika sudah melebihi tgl umur ternak maka bonus daging dikirmkan ke wallet
                        Investment::find($value->id)->update([
                                'remains' => $kurang,
                                'status'  => 0
                        ]);
                    }
                        
                }
                $jam =  date("H");
                $tanggal =  date("d M");
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollback();
                CronFail::create([
                    'flag'=>'Cron produksi ternak',
                    'note'=>$e->getMessage(),
                ]);
               
            }
            
        }

        CronLog::create([
            'remains' => $jam,
            'note'    => 'cron distribusi hasil ternak tanggal '.$tanggal .' jam ke '.$jam
        ]);
        return response()->json(['status'=>200,'message'=>'send produksi ternak '. date("Y-m-d H:i:s")]);
    }

    public function trxStatus(){
        $payment = Payment::where('status',1)->get();
        foreach ($payment as $key => $value) {
            $data = [
                'merchantAppCode' => $this->app,
                'merchantAppPassword' => $this->pass,
                'orderNo' => $value->order_no
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => env('KPAYDEVURL').'transaction-detail.php',
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => '',
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 0,
                CURLOPT_FOLLOWLOCATION  => true,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => 'POST',
                CURLOPT_POSTFIELDS      => json_encode($data),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $py     = Payment::find($value->id);
            $rs     = json_decode($response,true);
            $now    = date("Y-m-d H:i:s");
            $end    = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($py->created_at)));
            if($rs['success']== 1){
                $py->update([
                    'status'    => 2,
                    'trx_no'    => $rs['result']['transaction']['transactionNo'],
                    'trx_date'  => $rs['result']['transaction']['transactionDate'],
                    'pay_method'=> $rs['result']['transaction']['paymentMethod']
                ]);
                $dm     = $py->diamon;
                $user_id= $py->user_id;
                $trx    = $py->order_no;
                $diamon = TopupDiamon::where('diamon',$dm)->first();
                $this->sendDiamon($diamon,$user_id,$trx);
            }elseif($now > $end){
                $py->update([
                    'status'=>3
                ]);
            }
        }
    }

    public function umurTernak(){
        $ternak = UserTernak::with(['ternak'])->get();
        $update = 1;
        foreach ($ternak as $key => $value) {
           
            if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($value->buy_date."+".$value->ternak->duration ." days"))) {
                // UserTernak::find($value->id)->update([
                //     'status' => 0
                // ]);
                $userTernak = UserTernak::with(['ternak','ternak.produk'])->find($value->id);
                $invest = Investment::with('userTernak')->where('user_ternak',$value->id)->first();
                if ($invest) {
                    $wallet = UserWallet::getWalletUserId($invest->user_id);
                    $hasil_ternak = json_decode($wallet->hasil_ternak);
                    $array = (array)$hasil_ternak;
                    $produkId = $userTernak->ternak->produk->id;
                    $productInWallet = $array[$produkId]->qty;
                    $total      = $invest->commision;
                    $remain     = $invest->remains;
                    $collected  = $invest->collected;
                    $kurang     = $remain + $collected;

                    if($invest->userTernak->ternak_id == 4){
                        $finalProduc = $productInWallet + $total;
                        $array[$produkId]->qty = $finalProduc;
                        UserWallet::create([
                            'user_id'=>$invest->user_id,
                            'diamon'=>$wallet->diamon,
                            'pakan'=>$wallet->pakan,
                            'hasil_ternak' => json_encode($array)
                        ]);
                        $invest->update([
                            'collected' => $total,
                            'status'    => 0
                        ]);
                        
                    }else{
                        $finalProduc = $productInWallet + ($total - $kurang);
                        $array[$produkId]->qty = $finalProduc;
                        UserWallet::create([
                            'user_id'=>$invest->user_id,
                            'diamon'=>$wallet->diamon,
                            'pakan'=>$wallet->pakan,
                            'hasil_ternak' => json_encode($array)
                        ]);
                        $invest->update([
                            'collected' => $total - $kurang,
                            'status'    => 0
                        ]);
                    };
                }
                $userTernak->update([
                        'status' => 0
                ]);
                $update += 1;
            }
        }
        $jam =  date("H");
        $tanggal =  date("d M");
        CronLog::create([
            'remains' => $jam,
            'note'    => 'cron update umur ternak tanggal '.$tanggal .' jam ke '.$jam . ' success update ' . $update . ' record'
        ]);
    }
    
    public function sendDiamon($diamon,$user_id,$trx)
    { 
        $cek = Transaction::where('trx_id',$trx)->first();
        if($cek){
            return false;
        }
        // cek wallet user
        $wallet = UserWallet::where('user_id',$user_id)->orderByDesc('id')->first();
        try {
            DB::beginTransaction();
            if(!$wallet){
                Transaction::create([
                    'user_id' => $user_id,
                    'last_amount' => 0,
                    'trx_amount' => $diamon->diamon,
                    'final_amount'=>$diamon->diamon,
                    'trx_type'=>'+',
                    'detail'=>'Topup Diamon By IDR',
                    'trx_id' => $trx
                ]);
                UserWallet::create([
                    'user_id'=>$user_id,
                    'diamon'=>$diamon->diamon,
                    'pakan'=>0,
                    'hasil_ternak'=> json_encode(Product::produk())
                ]);
            }else{
                Transaction::create([
                    'user_id' => $user_id,
                    'last_amount' => $wallet->diamon,
                    'trx_amount' => $diamon->diamon,
                    'final_amount'=> $wallet->diamon + $diamon->diamon,
                    'trx_type'=>'+',
                    'detail'=>'Topup Diamon By IDR',
                    'trx_id' => $trx
                ]);
                UserWallet::create([
                    'user_id'=>$user_id,
                    'diamon'=>$wallet->diamon + $diamon->diamon,
                    'pakan'=>$wallet->pakan,
                    'hasil_ternak'=>$wallet->hasil_ternak
                ]);
            }
            ReferalTree::distribusiBonus($user_id,$diamon->diamon,$trx); // send bonus diamon
            DB::commit();
            return ['status'=>1];
        } catch (\Exception $e) {
            DB::rollback();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }

    // ==================================
   
    public function beriPakan($pakan_cost=89){
        $type=1;
        $user= User::where('user_role',1)->where('masterplan_count','!=',0)->where('is_auto',1)->get();
        foreach ($user as $key => $value) {
            $ternak= $value->masterplan_count;
            
            $cost = $ternak * $pakan_cost;
            $commision = $value->masterplan_count;
            $count = $ternak;

            $wallet = UserWallet::getWalletUserId($value->id);

            $trxID = Transaction::trxID('BP');
            Investment::create([
                'user_id'       => $value->id,
                'user_ternak'   => 1,
                'transaction'   => $trxID,
                'collected'     => 0,
                'remains'       => 0,
                'commision'     => $commision,
                'mark'          => $type,
                'status'        => 1
            ]);
            Transaction::create([
                'user_id'       => $value->id,
                'last_amount'   => $wallet->diamon,
                'trx_amount'    => $cost,
                'final_amount'  => $wallet->diamon - $cost,
                'trx_type'=>'-',
                'detail'=>notifMsg($type,$cost,$count)['title'],
                'trx_id' => $trxID
            ]);
            UserWallet::create([
                'user_id'=>$value->id,
                'diamon'=>$wallet->diamon - $cost,
                'pakan'=>0,
                'hasil_ternak' => $wallet->hasil_ternak
            ]);
            
            $title  = notifMsg($type,$cost,$count)['title'];
            $msg    = notifMsg($type,$cost,$count)['msg'];

            makenotif($value->id,$title,$msg);
        }
        return response()->json(['status'=>200,'message'=>"Beri Pakan Success"]);
    }


    public function beriVaksin($vaksin_cost=89){
        $type=2;
        $user= User::where('user_role',1)->get();
        foreach ($user as $key => $value) {
            $ternak= $value->masterplan_count;
            
            $cost = $ternak * $vaksin_cost;
            $commision = $ternak * 1;
            $count = $ternak;

            $wallet = UserWallet::getWalletUserId($value->id);

            $trxID = Transaction::trxID('BP');

            $invest = Investment::where('user_id',$value->id)->where('mark',1)->update(['mark'=>$type]);
            if($invest){
                UserWallet::create([
                    'user_id'       => $value->id,
                    'diamon'        => $wallet->diamon - $cost,
                    'pakan'         => 0,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                Transaction::create([
                    'user_id'       => $value->id,
                    'last_amount'   => $wallet->diamon,
                    'trx_amount'    => $cost,
                    'final_amount'  => $wallet->diamon - $cost,
                    'trx_type'=>'-',
                    'detail'=>notifMsg($type,$cost)['title'],
                    'trx_id' => $trxID
                ]);
                $title  = notifMsg($type,$cost,$count)['title'];
                $msg    = notifMsg($type,$cost,$count)['msg'];
            
                makenotif($value->id,$title,$msg);
            }

           
        }
       
        return response()->json(['status'=>200,'message'=>"Beri Vaksin Success"]);
    }
    public function bersihKandang($tools_cost=89){
        $type=3;
        $user= User::where('user_role',1)->get();
        foreach ($user as $key => $value) {
            $ternak= $value->masterplan_count;
            $cost =$ternak * $tools_cost;
            $commision =$ternak * 1;
            $count =$ternak;

            $wallet = UserWallet::getWalletUserId($value->id);

            $trxID = Transaction::trxID('BP');

            $invest = Investment::where('user_id',$value->id)->where('mark',2)->update(['mark'=>$type]);
            if($invest){
                UserWallet::create([
                    'user_id'       => $value->id,
                    'diamon'        => $wallet->diamon - $cost,
                    'pakan'         => 0,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                Transaction::create([
                    'user_id'       => $value->id,
                    'last_amount'   => $wallet->diamon,
                    'trx_amount'    => $cost,
                    'final_amount'  => $wallet->diamon - $cost,
                    'trx_type'=>'-',
                    'detail'=>notifMsg($type,$cost)['title'],
                    'trx_id' => $trxID
                ]);
                $title  = notifMsg($type,$cost,$count)['title'];
                $msg    = notifMsg($type,$cost,$count)['msg'];
                makenotif($value->id,$title,$msg);
            }

            
            
        }
        return response()->json(['status'=>200,'message'=>"Bersih Kandang Success"]);
    }

    public function ambiltelur(){
        $type=4;
        $user= User::where('user_role',1)->get();
        foreach ($user as $key => $value) {
            $ternak= $value->masterplan_count;
            $count =$ternak;

            $wallet = UserWallet::getWalletUserId($value->id);

            $invest = Investment::where('user_id',$value->id)->where('mark',3)->orderByDesc('id')->first();
            if($invest){

                $hasil_ternak = json_decode($wallet->hasil_ternak);
                $array = (array)$hasil_ternak;
                $productInWallet = $array[1]->qty;
                $finalProduc = $productInWallet + $invest->commision;
                $finalProduc = $invest->commision;
                // $array[1]->qty = $finalProduc;
                // dd($array);

                UserWallet::create([
                    'user_id'   => $value->id,
                    'diamon'    =>  $wallet->diamon,
                    'pakan'     =>0,
                    'hasil_ternak' => '{"1":{"name":"Telur","qty":'.$finalProduc.'}}'
                ]);
                
                $invest->update([
                    'collected' =>  $invest->commision,
                    'mark'      => $type,
                    'status'    => 0
                ]);
                $title  = notifMsg($type,$invest->commision,$count)['title'];
                $msg    = notifMsg($type,$invest->commision,$count)['msg'];
                makenotif($value->id,$title,$msg);
            }
        }
        return 'success';
       
    }

    public function kirimBanyakAyam(){
        // return 'disabled';
        $user = User::where('user_role',1)->get();
        foreach ($user as $u) {
            $ut     = UserTernak::where('user_id',$u->id)->where('status',1)->get();
            $m_c    = $u->masterplan_count;
            $count  = $ut->count();

            $min    = $m_c - $count;
            $no     = 0 ;
            for ($i = 0; $i < $min; $i++) {
                beliAyam(1,$u->id,497000);
                $no += 1;
            }
            makenotif($u->id,'Deliver Sisa Pembelian Ayam', 'Deliver sisa pembelian sejumlah '.$no.' ekor ayam, dari total '.$m_c.' ekor. Sukses Dikirim!');
        }
        return 'success';
    }

    public function jualTelur(){
        $user = User::where('user_role',1)->get();
        foreach ($user as $key => $value) {
            $wallet = UserWallet::getWalletUserId($value->id);
            // UserWallet::create([
            //     'user_id'=>$value->id,
            //     'diamon'=>0,
            //     'pakan'=>$wallet->pakan,
            //     'hasil_ternak'=>$wallet->hasil_ternak
            // ]);

            jualTelur($value->id,1);
        }
       echo 'success-jual';
    }
    public function resetWallet(){
        $user = User::where('user_role',1)->get();
        //reset tabel wallet;
        UserWallet::truncate();
        foreach ($user as $key => $value) {
            $dm = 537 * $value->masterplan_count;
            $telur = $value->masterplan_count;
            UserWallet::create([
                'user_id'=>$value->id,
                'diamon'=>0,
                'hasil_ternak'=>'{"1":{"name":"Telur","qty":'.$value->masterplan_count.'}}'
            ]);
        }
        return response()->json(['message' => 'All data in UserWallet table has been reset'], 200);
    }
    public function createDemoAccountAll(){
        $user = User::where('user_role',1)->where('masterplan_count','!=',0)->where('id',37)->get();
        // dd($user);
        foreach ($user as $key => $value) {
           createDemoAccount($value);
        }
        return 'demo success';
    }
    public function walletUser(){
        $user = User::where(['is_demo'=>0,'user_role'=>1])->get();
        foreach ($user as $key => $value) {
            $gems = GemsUser($value->id);
            $value->gems = $gems;
            $value->save();
        }
        return 'done';
    }
    public function jualTelurId(Request $request){
        // dd($request->id1);
        // dd(10<10);
        $data = [];
        foreach ($request->all() as $key => $value) {
            $data[] = $value;
            // dd($value);
            jualTelur($value,1);

        }
        return [
            'msg' => 'success',
            'data'=> $data
        ];
    }
    public function PakanTernakId(Request $request){
        // dd($request->id1);
        // dd(10<10);
        $type=1;
        $pakan_cost = 89;
        $data = [];
        foreach ($request->all() as $key => $value) {
            $data[] = $value;
            $user = User::find($value);
            $ternak= $user->masterplan_count;
            
            $cost = $ternak * $pakan_cost;
            $commision = $ternak;
            $count = $ternak;

            $wallet = UserWallet::getWalletUserId($value);

            $trxID = Transaction::trxID('BP');
            Investment::create([
                'user_id'       => $user->id,
                'user_ternak'   => 1,
                'transaction'   => $trxID,
                'collected'     => 0,
                'remains'       => 0,
                'commision'     => $commision,
                'mark'          => $type,
                'status'        => 1
            ]);
            Transaction::create([
                'user_id'       => $user->id,
                'last_amount'   => $wallet->diamon,
                'trx_amount'    => $cost,
                'final_amount'  => $wallet->diamon - $cost,
                'trx_type'=>'-',
                'detail'=>notifMsg($type,$cost,$count)['title'],
                'trx_id' => $trxID
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$wallet->diamon - $cost,
                'pakan'=>0,
                'hasil_ternak' => $wallet->hasil_ternak
            ]);
            
            $title  = notifMsg($type,$cost,$count)['title'];
            $msg    = notifMsg($type,$cost,$count)['msg'];

            makenotif($value->id,$title,$msg);

        }

        return [
            'msg' => 'success beripakan userid :',
            'data'=> $data
        ];
    }
}
