<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function payment(){
        $url = env('KPAYDEVURL').'transaction-process.php';
        // dd($url);
        $app = env('KPAYAPP');
        $pass   = env('KPAYPASS');
        $mail   = 'miartayasa01@gmail.com';
        $order_no = '123123skm';
        $order_amount = 10000;
        $msg = 'testing';
        $nama_produk = 'Tamakoci 1000 Diamond';
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
            'merchantAppCode' => $app,
            'merchantAppPassword' => $pass,
            'userEmail'     => $mail,
            'orderNo'       => $order_no,
            'orderAmt'      => $order_amount,
            'additionalMsg' => $msg,
            'productNo'     => $product_no,
            'productDesc'   => $product_desc,
            "productQty"    => $product_qty,
            "discountAmt"   => $product_amount,
            "processURL"    => url('process'),
            "cancelURL"     => url('cancel'),
            "successURL"    => url('success')
        ];
        return ($data);
        return $this->send($url,json_encode($data));
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

    public function success(){
        echo 'success';
    }
    public function process(){
        echo 'process';
    }
    public function cancel(){
       return Chart::countProductUser();
    }

}
