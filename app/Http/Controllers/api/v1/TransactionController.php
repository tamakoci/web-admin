<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use App\Models\UserWallet;
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

    public function trxLog(Request $request){
        $user = Auth::user();
        $data = Payment::where('user_id',$user->id)->orderByDesc('id')->get();
        return response()->json(['status'=>200,'msg'=>'Transaction inquiry','data'=>$data]);
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
        }else{
            return response()->json(['status'=>500,'msg'=>'Transaction Failed','data'=>$arr],500);
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
