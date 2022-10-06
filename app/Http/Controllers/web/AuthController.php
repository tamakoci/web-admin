<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginView(){
        $data['title'] = 'Login';
        return view('master.auth.login',$data);
    }
    public function registView(){
        $data['title'] = 'Register';
        return view('master.auth.register',$data);
    }
    public function loginPost(Request $request){
        dd($request->all());
    }
}
