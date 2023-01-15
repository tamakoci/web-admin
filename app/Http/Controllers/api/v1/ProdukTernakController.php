<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProdukTernakController extends Controller
{
    public function collectProduk(Request $request){
        $user = Auth::user();
        $validate = Validator::make($request->all(),[
            'user_ternak_id' => 'required',
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $invest = Investment::where('user_ternak',$request->user_ternak_id)->first();
        if(!$invest){
            return response()->json(['status'=>'401','message'=>'User Ternak Not Found',],401);
        }
        $userTernak = UserTernak::with(['ternak','ternak.produk'])->where('user_id',$user->id)->find($request->user_ternak_id);
        if(!$userTernak){
            return response()->json(['status'=>'401','message'=>'Kamu tidak memiliki ternak tersebut',],401);
        }
        if($userTernak->ternak->id == 4){
            return response()->json(['status'=>'401','message'=>'Produk daging dapat diambil hanya saat ternak berusia 7 hari',],401);
        }
        
        $produkId = $userTernak->ternak->produk->id;

        $wallet = UserWallet::getWallet();
        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        
        $remain     = $invest->remains;
        $collected  = $invest->collected;
        $newCollect = $collected + $remain;
        $total      = $invest->commision;
        $productInWallet = $array[$produkId]->qty;

        DB::beginTransaction();
        try {
            // send hasil ternak to Wallet
            $finalProduc = $productInWallet + $remain;
            $array[$produkId]->qty = $finalProduc;
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$wallet->diamon,
                'pakan'=>$wallet->pakan,
                'hasil_ternak' => json_encode($array)
            ]);
            //update investment user remains to 0;
            if(($remain + $collected) == $total){
                $invest->update([
                    'remains'   => 0,
                    'collected' => $newCollect,
                    'status'    => 0
                ]);
            }else{
                $invest->update([
                    'remains'   => 0,
                    'collected' => $newCollect,
                ]);
            }
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Colect Produk Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;,
        }
    }
}
