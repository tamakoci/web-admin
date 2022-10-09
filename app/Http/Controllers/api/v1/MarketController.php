<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\RequestMarket;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function market(){
        $data = Market::with('product')->where('status',true)->orderBy('customer','asc')->get();
        return response()->json([
            'status'    => 200,
            'msg'       => 'Request customer on marker',
            'data'      => $data,
        ],200);
    }
}
