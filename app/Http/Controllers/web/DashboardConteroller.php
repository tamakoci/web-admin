<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\User;
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
        $data['title'] = 'Dashboard';
        $data['diamon'] = '1.000';
        $data['pakan'] = '5.000';
        $data['telur'] = '100';
        $data['susu'] = '700';
        $data['daging'] = '10';
        return view('dashboard.user',$data);
    }
    public function chart(){
        $data = Chart::countProductUser();
        return response()->json(['status'=>200,'data'=>$data],200);
    }
}
