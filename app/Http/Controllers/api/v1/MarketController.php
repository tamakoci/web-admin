<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\RequestMarket;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function market(){
        $data = RequestMarket::orderBy('customer','asc')->get(['id','customer','product','qty','satuan']);
        return response()->json([
            'status'    => 200,
            'msg'       => 'Request customer on marker',
            'data'      => $data,
        ],200);
    }
}
