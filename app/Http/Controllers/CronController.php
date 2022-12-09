<?php

namespace App\Http\Controllers;

use App\Models\CronFail;
use App\Models\CronLog;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ReferalTree;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    public function __construct()
    {
        $this->url      = env('KPAYDEVURL');
        $this->app      = env('KPAYAPP');
        $this->pass     = env('KPAYPASS');
        $this->mail     = env('KPAYEMAIL');
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
                dd($e->getMessage());
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
}
