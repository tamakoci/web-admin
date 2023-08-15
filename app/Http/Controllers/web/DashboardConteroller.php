<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class DashboardConteroller extends Controller
{
    public function index(){
        $data['title'] = 'Dashboard';
        $data['deposit'] = 'Rp. 1000K';
        $data['withdrawal'] = 'Rp. 600K';
        $data['member'] = User::where('user_role',1)->count();
        return view('dashboard.index',$data);
    }
    public function user(){
        $wallet = UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
        $data['title'] = 'Dashboard';
        $data['diamon'] = $wallet->diamon;
        $data['pakan'] = $wallet->pakan;
        return view('dashboard.user',$data);
    }
    public function chart(){
        $data = Chart::countProductUser();
        return response()->json(['status'=>200,'data'=>$data],200);
    }
    public function wallets(){
        $wallet = UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
        if ($wallet) {
            $product = json_decode($wallet->hasil_ternak,true);
            $telur = $product[1]['qty'];
            $susu = $product[2]['qty'];
            $daging = $product[3]['qty'];
        }else{
            $telur = 0;
            $susu = 0;
            $daging = 0;
        }
        $data = [
            'telur'=>$telur,
            'susu'=>$susu,
            'daging'=>$daging
        ];
        return response()->json(['status'=>200,'data'=>$data],200);
    }
}
