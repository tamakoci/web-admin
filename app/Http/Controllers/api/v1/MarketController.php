<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\RequestMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    public function market(){
        $market = [];
        $data = Market::with('product')->where('status',true)->orderBy('customer','asc')->get();
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
        $validate = Validator::make($request->all(),[
            'market_id' => 'Required|numeric|exists:markets,id'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>401,'message'=>'validation error','errors'=>$validate->getMessageBag()]);
        }
        $data = Market::with('product')->find($request->market_id);
        // $dataBag = 

    }
}
