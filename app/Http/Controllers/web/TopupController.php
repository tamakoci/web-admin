<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TopupDiamon;
use App\Models\TopupPangan;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function diamon(){
        $data['title'] = 'Topup Diamon';
        $data['table'] = TopupDiamon::all();
        return view('topup.diamon',$data);
    }
    public function pangan(){
        $data['title'] = 'Topup Pangan';
        $data['table'] = TopupPangan::all();
        return view('topup.pangan',$data);
    }
}
