<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\RequestMarket;
use Illuminate\Http\Request;

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
}
