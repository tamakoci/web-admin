<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PakanTernak;
use App\Models\Product;
use App\Models\TopupDiamon;
use App\Models\TopupPakan;
use App\Models\TopupPangan;
use App\Models\Transaction;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class TopupController extends Controller
{
    public function TopupDiamon(){
        $diamon = TopupDiamon::where("status",true)->get(["id","diamon","price"]);
        return response()->json([
            'status'    => '200', 
            'msg'       => 'Harga Diamon by Rupiah',
            'data'      => $diamon,
        ],200);
    }
    public function TopupPakan(){
        $pakan = TopupPakan::where("status",true)->get(["id","pakan","diamon"]);
        return response()->json([
            'status'    => '200', 
            'msg'       => 'Harga Pakan by Diamon',
            'data'      => $pakan,
        ],200);
    }
    public function buyDiamon(Request $request){ 
        $validate = Validator::make($request->all(),[
            'diamon_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }   
        // find user login by token
        $user = Auth::user();
        // cek wallet user
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        // return response()->json(['data'=>$wallet]);
        //if payment success
        try {
            $diamon = TopupDiamon::find($request->diamon_id);
            if(!$diamon){
                return response()->json(['status'=>404,'message'=>'Data diamon tidak ditemukan!'],404);
            }
            DB::beginTransaction();
            if(!$wallet){
                Transaction::create([
                    'user_id' => $user->id,
                    'last_amount' => 0,
                    'final_amount'=>$diamon->diamon,
                    'trx_type'=>'+',
                    'detail'=>'Topup Diamon By IDR',
                    'trx_id' => Transaction::trxID('TD')
                ]);
                UserWallet::create([
                    'user_id'=>$user->id,
                    'diamon'=>$diamon->diamon,
                    'pakan'=>0,
                    'hasil_ternak'=> json_encode(Product::produk())
                ]);
            }else{
                Transaction::create([
                    'user_id' => $user->id,
                    'last_amount' => $wallet->diamon,
                    'final_amount'=> $wallet->diamon + $diamon->diamon,
                    'trx_type'=>'+',
                    'detail'=>'Topup Diamon By IDR',
                    'trx_id' => Transaction::trxID('TD')
                ]);
                UserWallet::create([
                    'user_id'=>$user->id,
                    'diamon'=>$wallet->diamon + $diamon->diamon,
                    'pakan'=>$wallet->pakan,
                    'hasil_ternak'=>$wallet->hasil_ternak
                ]);
            }
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Topup Diamon Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;
        }
    }
    public function buyPakan(Request $request){
        $validate = Validator::make($request->all(),[
            'pakan_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $user = Auth::user();
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return response()->json(['status'=>402,'message'=>'Diamon Tidak Ada!'],Response::HTTP_PAYMENT_REQUIRED);
        }
        $pakan = TopupPakan::find($request->pakan_id);
        if(!$pakan){
            return response()->json(['status'=>404,'message'=>'Pakan ternak not found'],404);
        }
        
        DB::beginTransaction();
        try {
            $dm = $wallet->diamon;
            if($dm < $pakan->diamon){
                return response()->json(['status'=>402,'message'=>'Diamon tidak cukup'],Response::HTTP_PAYMENT_REQUIRED);
            }
            Transaction::create([
                'user_id' => $user->id,
                'last_amount' => $wallet->diamon,
                'final_amount'=> $wallet->diamon - $pakan->diamon,
                'trx_type'=>'-',
                'detail'=>'Topup Pakan By Diamon',
                'trx_id' => Transaction::trxID('TP')
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$dm - $pakan->diamon,
                'pakan'=>$wallet->pakan + $pakan->pakan,
                'hasil_ternak' => $wallet->hasil_ternak
            ]);
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Topup Pakan Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;
        }
    }
}
