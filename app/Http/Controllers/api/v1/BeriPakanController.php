<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\PakanTernak;
use App\Models\Transaction;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BeriPakanController extends Controller
{
    public function beriPakan(Request $request){
        // $cekTernak=
        $user = Auth::user();
        $validate = Validator::make($request->all(),[
            'pakan_id' => 'required',
            'user_ternak_id' => 'required',
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $pakan = PakanTernak::find($request->pakan_id); 
        $userTernak = UserTernak::with(['ternak','ternak.produk'])->where('user_id',$user->id)->find($request->user_ternak_id);
        $cekInvest = Investment::where(['user_ternak'=>$request->user_ternak_id])->orderByDesc('id')->first(); 
        $cekPakan = PakanTernak::where(['id'=>$request->pakan_id,'ternak_id'=>$userTernak->ternak->id])->first();
        
        if(!$userTernak){
            return response()->json(['status'=>'401','message'=>'Kamu tidak memiliki ternak tersebut',],401);
        }
        if(!$cekPakan){
            return response()->json(['status'=>'401','message'=>'Ternak tidak dapat diberi pakan tsb',],401);
        }
        // if($cekInvest->status == 1){
        //     return response()->json(['status'=>'401','message'=>'Ternak hanya dapat diberi makan 1x sehari!',],401);
        // }
      
        $wallet = UserWallet::getWallet();
        
        if($wallet->pakan < $pakan->pakan){
            return response()->json(['status'=>'401','message'=>'Pakan Tidak Cukup',],401);
        }
        $produkId = $userTernak->ternak->produk->id;

        $buyDate = $userTernak->buy_date;
        $now = Carbon::now();
        $datetime1 = new DateTime($buyDate);
        $datetime2 = new DateTime($now);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a'); // cek sisa umur ternak
        if($days > $userTernak->ternak->duration){
            return response()->json(['status'=>'401','message'=>'Ternak Not Falid',],401);
            $userTernak->update(['status'=>0]);
        }

        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        $productInWallet = $array[$produkId]->qty;

        // $commision = rand($ternak->min_benefit,$ternak->max_benefit);
        $commision = $pakan->benefit;
        $remain = floor($commision / 24);
        
        
        // return $commision;
        DB::beginTransaction();
        try {
            $trxID = Transaction::trxID('BP');
            if($userTernak->ternak->id == 4){
               $investment = Investment::where(['user_id'=>$user->id,'user_ternak'=>$request->user_ternak_id])->first();
               if(!$investment){
                   Investment::create([
                       'user_id' => $user->id,
                       'user_ternak'=>$request->user_ternak_id,
                       'transaction'=>$trxID,
                       'collected'=>0,
                       'remains'=> 0,
                       'commision'=>$commision,
                       'status'=>1
                   ]);
               }else{
                    $investment->update([
                        'commision'=>$investment->commision + $commision
                    ]);
               }
            }else{
                $finalProduc = $productInWallet + $remain;
                $array[$produkId]->qty = $finalProduc;
                Investment::create([
                    'user_id' => $user->id,
                    'user_ternak'=>$userTernak->id,
                    'transaction'=>$trxID,
                    'collected'=>0,
                    'remains'=> $remain,
                    'commision'=>$commision,
                    'status'=>1
                ]);
                UserWallet::create([
                    'user_id'=>$user->id,
                    'diamon'=>$wallet->diamon,
                    'pakan'=>$wallet->pakan - $pakan->pakan,
                    'hasil_ternak' => $wallet->hasil_ternak
                ]);
            }
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Beri Pakan Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;,
        }
    }
}
