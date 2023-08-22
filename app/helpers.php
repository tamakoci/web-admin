<?php

use App\Models\Investment;
use App\Models\Notif;
use App\Models\Product;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

function notifMsg($type,$cost,$count=1){
    if($type==1){
        $title  = "Beri Pakan Ternak";
        $msg    = 'Beripakan ternak setara '.$cost.' gems untuk '.$count.' ayam sukses dilakukan.';
    }if($type==2){
        $title  = "Beri Vaksin Ternak";
        $msg    = 'Beri Vaksin setara '.$cost.' gems  untuk '.$count.' ayam sukses dilakukan.';
    }
    if($type==3){
        $title = 'Bersihkan Kandang Ternak';
        $msg = 'Sukses Bersihkan Kandang  Untuk '.$count.' Ayam Setara '.$cost.' Gems.';
    }
    if($type==4){
        $title  = 'Panen Telur';
        $msg    = 'Panen Hasil '.$count.' Ekor Ayam dan Menghasilkan '.$cost.' Butir Telur, Sukses.';
    }
    return ['title'=>$title,'msg'=>$msg];
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
function beliAyam($ternak_id,$user_id,$price=0)
{
    $ternak = Ternak::find($ternak_id);
    $priceTernak = $price==0?$ternak->price:$price;

    $user = User::find($user_id);

    $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
    if(!$wallet){
        return false;
    }
    if(!$ternak){
        return false;
    }
    $dm = $wallet->diamon;
    if($dm < $priceTernak){
        return false;
    }
        
    $trxID = Transaction::trxID('BT');
    Transaction::create([
        'user_id' => $user->id,
        'last_amount' => $dm,
        'trx_amount'   => $priceTernak,
        'final_amount'=> $dm - $priceTernak,
        'trx_type'=>'-',
        'detail'=>'Buy Ternak By Gems',
        'trx_id' => $trxID
    ]);
    UserWallet::create([
        'user_id'=>$user->id,
        'diamon'=>$dm - $priceTernak,
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
function kirimAyamLoop($user,$loop,$price=0){
    for ($i = 0; $i < $loop; $i++) {

        $ternak = Ternak::find(1);
        $priceTernak = $price==0?$ternak->price:$price;

        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return false;
        }
        if(!$ternak){
            return false;
        }
        $dm = $wallet->diamon;
        if($dm < $priceTernak){
            return false;
        }
            
        $trxID = Transaction::trxID('BT');
        Transaction::create([
            'user_id' => $user->id,
            'last_amount' => $dm,
            'trx_amount'   => $priceTernak,
            'final_amount'=> $dm - $priceTernak,
            'trx_type'=>'-',
            'detail'=>'Buy Ternak By Gems',
            'trx_id' => $trxID
        ]);
        UserWallet::create([
            'user_id'=>$user->id,
            'diamon'=>$dm - $priceTernak,
            'pakan'=>$wallet->pakan,
            'hasil_ternak' => $wallet->hasil_ternak
        ]);
        UserTernak::create([
            'user_id'=>$user->id,
            'ternak_id'=>1,
            'buy_date'=> date('Y-m-d H:i:s'),
            'status'=>1
        ]);
    }
    makenotif($user->id,'Deliver Pembelian Ayam', 'Pembelian sejumlah '.$loop.' ekor ayam sukses dilakukan');
}
function jualTelur($user_id,$productRequest=1){
    $wallet = UserWallet::getWalletUserId($user_id);
    $hasil_ternak = json_decode($wallet->hasil_ternak);
    $array = (array)$hasil_ternak;
    $productInWallet = $array[1]->qty;
    $productRequest = $productInWallet;
    
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
            'detail'=>'Jual '.$productRequest. ' '. $product->satuan.' '.$product->name. ' setara '. $profit . ' Gems. ( 1 '.  $product->satuan .' '. $product->name .' = '.$product->dm.' Diamon )',
            'trx_id' => Transaction::trxID('TD')
        ]);
        UserWallet::create([
            'user_id'=>$user_id,
            'diamon'=>$wallet->diamon + $profit,
            'pakan'=>$wallet->pakan,
            'hasil_ternak'=>json_encode($array)
        ]);
        makenotif($user_id,'Jual Produk','Jual '.$productRequest. ' '. $product->satuan.' '.$product->name. ' setara '. $profit . ' Gems.');
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
    }

}
function livingCost($type,$living_cost=89){
    // dd($type);
    $user= User::where('user_role',1)->get();
    foreach ($user as $key => $value) {
        $ternak= UserTernak::with(['user'])->where('user_id',$value->id)->get();
        $user_ternak = $ternak->first();
        
        $cost = $ternak->count() * $living_cost;
        $commision = $ternak->count() * 1;
        $count = $ternak->count();

        $wallet = UserWallet::getWalletUserId($value->id);

        $trxID = Transaction::trxID('BP');

        if($type=1){
            Investment::create([
                'user_id'       => $value->id,
                'user_ternak'   => $user_ternak->id,
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

        }elseif($type==4){
            $invest = Investment::where('user_id',$value->id)->first();
            $invest->collected  = $invest->commision;
            $invest->mark       = $type;
            $invest->status     = 0;
            $invest->save();

            $hasil_ternak = json_decode($wallet->hasil_ternak);
            $array = (array)$hasil_ternak;
            $productInWallet = $array[1]->qty;
            $finalProduc = $productInWallet + $invest->commision;
            $array[1]->qty = $finalProduc;
            // dd($array);

            UserWallet::create([
                'user_id'=>$value->id,
                'diamon'=>$wallet->diamon,
                'pakan'=>0,
                'hasil_ternak' => json_encode($array)
            ]);
            $title  = notifMsg($type,$invest->commision,$count)['title'];
            $msg    = notifMsg($type,$invest->commision,$count)['msg'];
        }else{
            $invest = Investment::where('user_id',$value->id)->first();
            $invest->mark       = $type;
            $invest->save();

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
        }
        makenotif($value->id,$title,$msg);
    }
}

function createDemoAccount($user){
    DB::beginTransaction();
    try {
        $create = User::create([
            'email'     => $user->email,
            'username'  => $user->username.'_demo',
            'phone'     => $user->phone.rand(1,100),
            'user_ref'  => User::makeReferal($user->username.'_demo'),
            'ref_to'    => null,
            'masterplan_count'=>$user->masterplan_count,
            'password'  =>  Hash::make(123456),
            'is_demo'   => 1,
        ]);

        //last wallet user copy;
        $wallet = UserWallet::getWalletUserId($user->id);
        UserWallet::create([
            'user_id'=>$create->id,
            'diamon'=>$wallet->diamon,
            'pakan'=>$wallet->pakan,
            'hasil_ternak' => $wallet->hasil_ternak
        ]);
        $ternak = UserTernak::where('user_id',$user->id)->get();
        foreach ($ternak as $t) {
            UserTernak::create([
                'user_id'   => $create->id,
                'ternak_id' => $t->ternak_id,
                'buy_date'  => $t->buy_date,
                'status'    => $t->status,
                'created_at'=> $t->created_at,
                'updated_at'=> $t->updated_at
            ]);
        }

        DB::commit();
    } catch (\Throwable $th) {
        DB::rollBack();
        dd($th->getMessage());
    }
}

