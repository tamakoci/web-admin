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
use Illuminate\Support\Facades\Http;
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
                'diamon'        => $wallet->diamon,
                'pakan'         => $wallet->pakan,
                'vaksin'        => $wallet->vaksin,
                'tools'         => $wallet->tools,
                'hasil_ternak'  => $wallet->hasilTernak()
            ];
        }else{
            $new = UserWallet::create([
                'user_id'       => $user->id,
                'diamon'        => 0,
                'pakan'         => 0,
                'vaksin'        => 0,
                'tools'         => 0,
                'hasil_ternak'=> json_encode(Product::produk())
            ]);
            $userWallet = [
                'diamon'        => $new->diamon,
                'pakan'         => $new->pakan,
                'vaksin'        => $new->vaksin,
                'tools'         => $new->tools,
                'hasil_ternak'=>$new->hasilTernak()
            ];
        }
        return response()->json([
            'status'=>200,
            'message'=>'User Info Data',
            'Data'=>[
                'user_active'=>User::getUser(),
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

    public function getNotif(){
        return [
            'status'=>200,
            'message'=>'Notification User',
            'data'=>notifApi()
        ];
    }
    public function checkRekening(){

        $user   = Auth::user();
        $apiUrl = 'https://masterplan.co.id/api/rekening-info/'.$user->username;
        
        $response = Http::get($apiUrl);
        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Failed to fetch data from the API'], $response->status());
        }

    }
}
