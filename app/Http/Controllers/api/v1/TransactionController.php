<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{   
    public function __construct()
    {
        $this->url      = env('KPAYDEVURL');
        $this->app      = env('KPAYAPP');
        $this->pass     = env('KPAYPASS');
        $this->mail     = env('KPAYEMAIL');
    }
    public function inquiry(Request $request,$kode){
        $data = [
            'merchantAppCode' => $this->app,
            'merchantAppPassword' => $this->pass,
            'orderNo' => $kode
        ];
        return $this->send($this->url.'transaction-detail.php',json_encode($data));
    }
    public function beliToolsHarian($qty){
        $daycost            = 89;
        $user               = Auth::user();
        $cost_per_day       = ($daycost * 3) * $user->masterplan_count;
        $global_cost        = $cost_per_day * $qty;
        $wallet = UserWallet::getWalletUserId($user->id);
        if($wallet->diamon < $global_cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Gems']);
        }
        $task = $global_cost / 3;
        DB::beginTransaction();
        try {
            UserWallet::create([
                'user_id'   => $user->id,
                'diamon'    => $wallet->diamon - $global_cost,
                'pakan'     => $wallet->pakan + $task,
                'vaksin'        => $wallet->vaksin + $task,
                'tools'         => $wallet->tools + $task,
                'hasil_ternak'  => $wallet->hasil_ternak
            ]);
            Transaction::create([
                'user_id'       => $user->id,
                'last_amount'   => $wallet->diamon,
                'trx_amount'    => $global_cost,
                'final_amount'  => $wallet->diamon - $global_cost,
                'trx_type'      => '-',
                'detail'        => 'Beli Perlengkapan Harian',
                'trx_id'        => Transaction::trxID('BT')
            ]);
            
            DB::commit();
            makenotif($user->id,'
                            Beli Perlengkapan Harian','Beli '.$task.' Pakan + '.$task.' Vaksin + '.$task.' Tools untuk '.
                            $user->masterplan_count. ' ternak selama '.$qty.' hari, Setara '.$global_cost.' Gems Success!');
            return response()->json(['status'=>200,'message'=>"Beli Pakan Vaksin Tools Untuk ".$qty." Hari Success!"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
     public function beliToolsInfo(){
        $daycost            = 89;
        $user               = Auth::user();
        $cost_per_day       = ($daycost * 3) * $user->masterplan_count;
        $global_cost        = $cost_per_day;
        return [
            'status'    => 200,
            'message'   => 'Beli Tools Info '.$user->masterplan_count.' Ternak',
            'data'      => [
                ['day'=>1,'cost'=>$global_cost * 1],
                ['day'=>2,'cost'=>$global_cost * 2],
                ['day'=>3,'cost'=>$global_cost * 3],
            ]
        ];
    }

    public function trxLog(Request $request){
        $user = Auth::user();
        $data = Payment::where(['mark'=>'TD','user_id'=>$user->id])->orderByDesc('id')->get();
        return response()->json(['status'=>200,'msg'=>'Topup Transaction inquiry','data'=>$data]);
    }
    public function trxLogV2(Request $request){
        $user = Auth::user();
        $pay = Payment::where(['mark'=>'TD','user_id'=>$user->id])->orderByDesc('id')->limit(5)->get();
        $data = [];
        foreach ($pay as $key => $value) {
            if($value->status == 1){
                $status = 'Transaction Created';
            }elseif($value->status == 2){
                $status = 'Transaction Success';
            }else{
                $status = 'Transaction Expired';
            }
            $data[] = [
                'id'        => $value->id,
                'user_id'   => $value->user_id,
                'mark'      => $value->mark=='TD' ? 'Topup Diamon':'-',
                'order_no'  => $value->order_no,
                'amount'    => $value->amount,
                'diamon'    => $value->diamon,
                'desc'      => $value->desc,
                'expired'   => $value->expired,
                'checkout_url'=>$value->checkout_url,
                'status'    => $status
            ];
        }
        return response()->json(['status'=>200,'msg'=>'Topup Transaction inquiry V2','data'=>$data]);
    }

    public function trxDetails($id){
        $data =  Payment::find($id);
        if($data){
            return response()->json(['status'=>200,'message'=>'Detail Transaction','data'=>$data],200);
        }else{
            return response()->json(['status'=>404,'message'=>'Data Not Found'],404);
        }
    }
     public function trxDetailsV2($id){
        $log =  Payment::find($id);
        if($log){
            if($log->status == 1){
                $status = 'Transaction Created';
            }elseif($log->status == 2){
                $status = 'Transaction Success';
            }else{
                $status = 'Transaction Expired';
            }
            $log['mark'] = $log->mark == 'TD' ? 'Topup Diamon':'Withdraw Diamon';
            $log['status'] = $status;
            return response()->json(['status'=>200,'message'=>'Detail Transaction','data'=>$log],200);
        }else{
            return response()->json(['status'=>404,'message'=>'Data Not Found'],404);
        }
    }

    public function trxDiamon(Request $request){
        $validate = Validator::make($request->all(),[
            'diamon_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $diamon = TopupDiamon::find($request->diamon_id);
        if(!$diamon){
            return response()->json(['status'=>404,'message'=>'Data diamon tidak ditemukan!'],404);
        }   
        // find user login by token
        $user = Auth::user();
        $order_no = Transaction::trxID('TD');
        $order_amount = $diamon->price;
        $msg = 'Tamakoci Topup';
        $nama_produk = 'Tamakoci '.$diamon->diamon.' Diamond';
        $product_no = [
            1
        ];
        $product_desc = [
            $nama_produk
        ];
        $product_qty = [
            1
        ];
        $product_amount = [
            $order_amount
        ];
        $data = [
            'merchantAppCode' => $this->app,
            'merchantAppPassword' => $this->pass,
            'userEmail'     => 'miartayasa10@gmail.com',
            'orderNo'       => $order_no,
            'orderAmt'      => $order_amount,
            'additionalMsg' => $msg,
            'productNo'     => $product_no,
            'productDesc'   => $product_desc,
            "productQty"    => $product_qty,
            "productAmt"    => $product_amount,
            "discountAmt"   => 0,
            "processURL"    => url('proccess'),
            "cancelURL"     => url('cancel'),
            "successURL"    => url('success')
        ];
        $res = $this->send($this->url.'transaction-process.php',json_encode($data));
        $arr = json_decode($res,true);
        if($arr['success'] == 1){
            DB::beginTransaction();
            try {
               Payment::create([
                    'user_id'   => $user->id,
                    'order_no'  => $arr['result']['ref'],
                    'amount'    => $arr['result']['amount'],
                    'diamon'    => $diamon->diamon,
                    'desc'      => 'Topup '.$diamon->diamon.' Diamon',
                    'expired'   => $arr['result']['expired'],
                    'checkout_url'=>$arr['result']['checkoutURL'],
                    'status'    => 1
                ]);
                DB::commit();
                return response()->json([
                    'status'=> 200,
                    'msg'   => 'Transaction Created',
                    'data'  =>  [
                        'token'=>$arr['result']['token'],
                        'amount'=>$arr['result']['amount'],
                        'trxID'=>$arr['result']['ref'],
                        'url'=>$arr['result']['checkoutURL'],
                        'expired'=>$arr['result']['expired']
                    ]
                ],200);
            } catch (QueryException $e) {
                DB::rollBack();
                return response()->json(['status'=>500,'msg'=>'Transaction Failed','errors'=>$e->getMessage()],500);
            }
            
        }else{
            return response()->json(['status'=>500,'msg'=>'Transaction Failed','errors'=>$arr],500);
        }      
    }

    public function send($url,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      => $data,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function process(){
        return redirect('https://coba-ayam-e5vl.vercel.app/');
    }
    public function success(){
        return redirect('https://coba-ayam-e5vl.vercel.app/');
    }
    public function cancel(){
        return redirect('https://coba-ayam-e5vl.vercel.app/');
    }
    public function payment(){
        echo 'payment';
    }
}
