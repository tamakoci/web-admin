<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(){
        $data['title'] = 'Home';
        return view('landing.index',$data);
    }
}
