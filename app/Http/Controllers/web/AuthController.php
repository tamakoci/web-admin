<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function registPost(Request $request){
        $validate = $request->validate([
            'username'=>'required|min:5',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required'
        ]);
        try {
            User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' =>  Hash::make($request->password)
            ]);
            
            return redirect()->intended('/')->with('success','User Created');

        } catch (QueryException $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error','register failed '.$e->getMessage());
        }
    }
    public function loginPost(Request $request){
        if (empty($request->username) || empty($request->password)) {
            return redirect()->back()->with('error','Email atau password tidak boleh kosong');
        }
        $user = User::where('username',$request->username)->first();
        if(!$user){
            $user = User::where('email',$request->username)->first();
        }
        if(!$user){
            return redirect()->back()->with('error','User tidak ditemukan');
        }
        if(!Hash::check($request->password,$user->password)){
            return redirect()->back()->with('error','Username / password salah');  
        }
        if(Auth::attempt($request->only('username','password'))){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success','Selamat Datang Kembali');
        }
        return redirect()->back()->with('error',"Login Gagal !");

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
