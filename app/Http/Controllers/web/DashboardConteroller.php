<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
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
}
