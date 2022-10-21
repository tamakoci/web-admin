<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Product;
use App\Models\RequestMarket;
use App\Models\Transaction;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    public function market(){
        $market = [];
        $data = Market::with('product')->where('status',true)->orderBy('id','asc')->get();
        foreach ($data as $key => $value) {
            $market[] = [
                'id'=>$value->id,
                'avatar'=>$value->avatar,
                'customer'=>$value->customer,
                'product'=>$value->product->name,
                'qty'=>$value->qty,
                'satuan'=>$value->product->satuan,
                'text'=> "Saya Mau Pesan ".$value->product->name." " . $value->qty. " ".$value->product->satuan." Apakah Kamu Menjualnya?"
            ];
        }
        return response()->json([
            'status'    => 200,
            'msg'       => 'Request customer on marker',
            'data'      => $market,
        ],200);
    }

    public function sell(Request $request){
        $user = Auth::user();
        $validate = Validator::make($request->all(),[
            'market_id' => 'Required|numeric|exists:markets,id'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>401,'message'=>'validation error','errors'=>$validate->getMessageBag()]);
        }
        $data = Market::with('product')->find($request->market_id);
        $productRequest = $data->qty;
        $profit = $data->qty * $data->product->dm;
        $wallet = UserWallet::getWallet();
        
        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        $productInWallet = $array[$data->product->id]->qty;

        if($productInWallet < $productRequest){
            return response()->json(['status'=>401,'message'=>'Not have enough product']);
        }
        $finalProduc = $productInWallet - $productRequest;
        $array[$data->product->id]->qty = $finalProduc;

        DB::beginTransaction();
        try {
            Transaction::create([
                'user_id' => $user->id,
                'last_amount' => $wallet->diamon,
                'trx_amount' => $profit,
                'final_amount'=>$wallet->diamon + $profit,
                'trx_type'=>'+',
                'detail'=>'Selling '.$productRequest. ' '. $data->product->satuan.' '.$data->product->name. ' with '. $profit . ' Diamon. ( 1 '.  $data->product->satuan .' '. $data->product->name .' = '.$data->product->dm.' Diamon )',
                'trx_id' => Transaction::trxID('TD')
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$wallet->diamon + $profit,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>json_encode($array)
            ]);
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Selling Market Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;
        }

    }
}
