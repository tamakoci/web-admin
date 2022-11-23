<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{   
    public function __construct()
    {
        $this->url = env('KPAYDEVURL').'transaction-process.php';
        $this->app = env('KPAYAPP');
        $this->pass   = env('KPAYPASS');
        $this->mail   = env('KPAYEMAIL');
    }

    public function trxDiamon(Request $request){
        // $validate = Validator::make($request->all(),[
        //     'diamon_id' => 'required'
        // ]);
        // if($validate->fails()){
        //     return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        // }
        $diamon = TopupDiamon::find(3);
        // if(!$diamon){
        //     return response()->json(['status'=>404,'message'=>'Data diamon tidak ditemukan!'],404);
        // }   
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
            'userEmail'     => $this->mail,
            'orderNo'       => $order_no,
            'orderAmt'      => $order_amount,
            'additionalMsg' => $msg,
            'productNo'     => $product_no,
            'productDesc'   => $product_desc,
            "productQty"    => $product_qty,
            "productAmt"    => $product_amount,
            "discountAmt"   => 0,
            "processURL"    => url('process'),
            "cancelURL"     => url('cancel'),
            "successURL"    => url('success')
        ];
        return $this->send($this->url,json_encode($data));
    }

    public function send($url,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$data,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
