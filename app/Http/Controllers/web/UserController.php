<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $data['title'] = 'Profile';
        $data['user'] = Auth::user();
        $wallet = UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
        $produk = Product::produk();
        // return $produk;
        if($wallet){
            $userWallet = [
                'diamond'=>$wallet->diamon,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>$wallet->hasilTernak()
            ];
        }else{
            $userWallet = [
                'diamond'=>0,
                'pakan'=>0,
                'hasil_ternak'=> $produk
                ];
        }
        $data['wallet']= $userWallet;
        $data['table'] = Transaction::where('user_id',auth()->user()->id)->orderByDesc('id')->get();
        return view('user.user-profile',$data);
    }
    public function refGenerate(){
        $ref = User::makeReferal();
        $user = Auth::user();
        $user->user_ref = $ref;
        $user->save();
        return redirect()->back()->with('success','User Referal Genarate');
    }

    public function updateUser(Request $request,$id){
        $user = Auth::user();
        if(isset($request->image)){
                $file = $request->file('image');
                $filename = time(). "." . $file->getClientOriginalExtension();
                $location = "public/files/images/";
                
                $file->move($location,$filename);
            
                $filepath =  asset('')  . "files/images/".$filename;
                $user->avatar = $filepath;
        }
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return redirect()->back()->with('success','User Updated');

    }
}
