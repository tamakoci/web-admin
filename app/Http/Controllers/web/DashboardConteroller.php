<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardConteroller extends Controller
{
    public function index(){
        $data['title'] = 'Dashboard';
        return view('dashboard.index',$data);
    }
}
