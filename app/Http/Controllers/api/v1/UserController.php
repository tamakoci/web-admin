<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Database\QueryException;
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
            $new = UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>0,
                'pakan'=>0,
                'hasil_ternak'=> json_encode(Product::produk())
            ]);
            $userWallet = [
                'diamon'=>$new->diamon,
                'pakan'=>$new->pakan,
                'hasil_ternak'=>$new->hasilTernak()
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

    public function cekTutor(){
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'message'=>'User Tutorial',
            'data'=> [
                'tutor'=> $user->active_tutor == 1 ? true : false
            ]
        ]);
    }
    public function updateTutor(){
        $id = Auth::user()->id;
        $data = User::find($id);
        if(!$data){
            return response()->json([
                'status'=>404,
                'message'=>'User not found!'
            ]);
        }else{
           try {
            $data->update([
                'active_tutor'=>0
            ]);
            return response()->json([
                'status'=>200,
                'message'=>'User Updated'
            ]);
           } catch (QueryException $th) {
               return response()->json([
                'status'=>500,
                'message'=>'Server Error',
                'errors'=>$th->getMessage()
            ]); 
           }
        }
    } 
    public function bisnisUser(){
        $data['user'] = Auth::user();
        $data['wallet'] = UserWallet::getWallet();
        $data['bank'] = [
            'bank' => 'BRI',
            'account_name' => 'Jhon dre',
            'account_number' => '9982668162898'
        ]; 
        $data['trx'] = Transaction::where('user_id',auth()->user()->id)->orderByDesc('id')->get();
        return response()->json([
            'status'=>200,
            'message'=>'User Bisnis Info',
            'data'=>$data
        ]);
    }

    public function getUserTernak(){
    }

}
