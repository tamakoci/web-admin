<?php

use App\Models\Notif;
use App\Models\Product;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

if(!function_exists('active_user')){
    // $user = JWTAuth::authenticate($request->token);

    $user = 'test';
    return $user;
}
function notif(){
    $data = Notif::where('user_id',auth()->user()->id)->orwhere('all_user',1)->orderByDesc('id')->limit(5)->get();
    return ['data'=>$data,'count'=>$data->count()];
}

function tambahEnter($inputString) {
    $words = explode(" ", $inputString);
    $outputString = "";
    
    $charCount = 0;
    foreach ($words as $word) {
        $wordLength = strlen($word);
        
        if ($charCount + $wordLength > 50) {
            $outputString = rtrim($outputString) . "<br>";
            $charCount = 0;
        }
        
        $outputString .= $word . " ";
        $charCount += $wordLength + 1; // Include space
        
        if ($charCount >= 30 && $charCount <= 50) {
            $outputString = rtrim($outputString) . "<br>";
            $charCount = 0;
        }
    }
    
    return rtrim($outputString);
}
function nb($angka) {
    $angka_str = str_replace(",", "", $angka);
    $angka_int = (int) $angka_str;

    $rupiah_format = number_format($angka_int, 0, ',', '.');

    return $rupiah_format;
}
function dt($d){
    return date('d-M H:i',strtotime($d));
}
function makenotif($user_id,$title,$msg){
    Notif::create([
        'title'     => $title,
        'message'   => $msg,
        'user_id'   => $user_id,
        'all_user'  => 0
    ]);
}
function beliAyam($ternak_id,$user_id)
{
    $user = User::find($user_id);
    $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
    if(!$wallet){
        return false;
    }
    $ternak = Ternak::find($ternak_id);
    if(!$ternak){
        return false;
    }
    $dm = $wallet->diamon;
    if($dm < $ternak->price){
        return false;
    }
        
    $trxID = Transaction::trxID('BT');
    Transaction::create([
        'user_id' => $user->id,
        'last_amount' => $dm,
        'trx_amount'   => $ternak->price,
        'final_amount'=> $dm - $ternak->price,
        'trx_type'=>'-',
        'detail'=>'Buy Ternak By Gems',
        'trx_id' => $trxID
    ]);
    UserWallet::create([
        'user_id'=>$user->id,
        'diamon'=>$dm - $ternak->price,
        'pakan'=>$wallet->pakan,
        'hasil_ternak' => $wallet->hasil_ternak
    ]);
    UserTernak::create([
        'user_id'=>$user->id,
        'ternak_id'=>$ternak_id,
        'buy_date'=> date('Y-m-d H:i:s'),
        'status'=>1
    ]);
            
}
function kirimAyamLoop($user,$loop){
    for ($i = 0; $i < $loop; $i++) {
        beliAyam(1,$user->id);
    }
    makenotif($user->id,'Deliver Pembelian Ayam', 'Pembelian sejumlah '.$loop.' ekor ayam sukses dilakukan');
}
function jualTelur($user_id,$productRequest=1){
    $wallet = UserWallet::getWalletUserId($user_id);
    $hasil_ternak = json_decode($wallet->hasil_ternak);
    $array = (array)$hasil_ternak;
    $productInWallet = $array[1]->qty;

    if($productInWallet < $productRequest){
        return false;
    }
    $finalProduc = $productInWallet - $productRequest;
    $array[1]->qty = $finalProduc;

    $product = Product::find(1);
    $profit = $product->dm * $productRequest;

    DB::beginTransaction();
    try {
        Transaction::create([
            'user_id' => $user_id,
            'last_amount' => $wallet->diamon,
            'trx_amount' => $profit,
            'final_amount'=>$wallet->diamon + $profit,
            'trx_type'=>'+',
            'detail'=>'Selling '.$productRequest. ' '. $product->satuan.' '.$product->name. ' with '. $profit . ' Diamon. ( 1 '.  $product->satuan .' '. $product->name .' = '.$product->dm.' Diamon )',
            'trx_id' => Transaction::trxID('TD')
        ]);
        UserWallet::create([
            'user_id'=>$user_id,
            'diamon'=>$wallet->diamon + $profit,
            'pakan'=>$wallet->pakan,
            'hasil_ternak'=>json_encode($array)
        ]);
        makenotif($user_id,'Jual Produk','Selling '.$productRequest. ' '. $product->satuan.' '.$product->name. ' with '. $profit . ' Diamon.');
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
    }

}
