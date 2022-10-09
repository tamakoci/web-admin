<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = Auth::user();
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        $produk = Product::produk();
        // return $produk;
        if($wallet){
            $userWallet = [
                'diamon'=>$wallet->diamon,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>$wallet->hasilTernak()
            ];
        }else{
            $userWallet = [
                'diamon'=>0,
                'pakan'=>0,
                'hasil_ternak'=> $produk
                ];
        }
        return response()->json([
            'status'=>200,
            'message'=>'User Info Data',
            'Data'=>[
                'user_active'=>$user,
                'user_wallet'=>$userWallet,
            ]
        ]);
    }
}
