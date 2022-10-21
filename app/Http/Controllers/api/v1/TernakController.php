<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\PakanTernak;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TernakController extends Controller
{
    public function getTernak(){
        $ternak = Ternak::where('status',1)->get();
        return response()->json([
            'status'=>200,
            'message'=>'Data Ternak',
            'data'=>$ternak],
            Response::HTTP_OK);
    }
    public function getPakanTernak($id){
        $pakan = PakanTernak::with(['ternak','ternak.produk'])->where('ternak_id',$id)->get();
        $data = [];
        foreach ($pakan as $key => $value) {
            $benefitFinal  = $value->benefit *  $value->ternak->produk->dm;
            $data[] = [
                'id'=>$value->id,
                'ternak_id'=>$value->ternak_id,
                'pakan'=>$value->pakan,
                'benefit'=>$value->benefit,
                'ternak' => $value->ternak->name,
                'produk'=>$value->ternak->produk->name,
                'satuan'=>$value->ternak->produk->satuan,
                'db_benefit'=>$benefitFinal,
                'text'=>'Menghasilkan '.$value->benfit. ' '.$value->ternak->produk->satuan.'/Hari Sejumlah '. $benefitFinal.' Diamond'
            ]; 
        }

        $ternak = Ternak::find($id);
        if ($pakan->count() <= 0) {
            return response()->json([
                'status'=>404,
                'message'=>'Data Pakan Ternak Tidak Ditemukan'],
            Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status'=>200,
            'message'=>'Data Pakan Ternak',
            'data'=>[
                'ternak'=>$ternak,
                'pakan'=>$data
            ]],
            Response::HTTP_OK);
    }
    public function buyTernak(Request $request){
        $validate = Validator::make($request->all(),[
            'ternak_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $user = Auth::user();
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return response()->json(['status'=>402,'message'=>'Diamon Tidak Ada!'],Response::HTTP_PAYMENT_REQUIRED);
        }
        $ternak = Ternak::find($request->ternak_id);
        if(!$ternak){
            return response()->json(['status'=>404,'message'=>'Data ternak not found'],404);
        }
        
        DB::beginTransaction();
        try {
            $dm = $wallet->diamon;
            if($dm < $ternak->price){
                return response()->json(['status'=>402,'message'=>'Diamon tidak cukup'],Response::HTTP_PAYMENT_REQUIRED);
            }
            
            $trxID = Transaction::trxID('BT');
            Transaction::create([
                'user_id' => $user->id,
                'last_amount' => $dm,
                'trx_amount'   => $ternak->price,
                'final_amount'=> $dm - $ternak->price,
                'trx_type'=>'-',
                'detail'=>'Buy Ternak By Diamon',
                'trx_id' => $trxID
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$dm - $ternak->price,
                'pakan'=>$wallet->pakan,
                'hasil_ternak' => $wallet->hasil_ternak
            ]);
            UserTernak::create([
                'user_id'=>$user->id,
                'ternak_id'=>$request->ternak_id,
                'buy_date'=> date('Y-m-d H:i:s'),
                'status'=>1
            ]);
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Buy Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;
        }
    }
    public function userTernak(){
        $user = Auth::user();
        $ternak = UserTernak::getUserTernak();
        return response()->json([
            'status'=>200,
            'message'=>'Ternak '.$user->username,
            'Data'=>$ternak
        ]);
    }
    public function beriPakan(Request $request){
        $user = Auth::user();
        $validate = Validator::make($request->all(),[
            'pakan_id' => 'required',
            'ternak_id'=>'required'
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $pakan = PakanTernak::find($request->pakan_id); 
        $userTernak = UserTernak::where('ternak_id',$pakan->ternak_id)->where('ternak_id',$request->ternak_id)->where('status',1)->first();
        if(!$userTernak){
            return response()->json(['status'=>'404','message'=>'Anda tidak memiliki ternak dengan pakan tsb!',],404);
        }
        $wallet = UserWallet::getWallet();
        if($wallet->pakan < $pakan->pakan){
            return response()->json(['status'=>'401','message'=>'Pakan Tidak Cukup',],401);
        }
        $ternak = Ternak::with('produk')->find($request->ternak_id);
        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        $productInWallet = $array[$ternak->produk->id]->qty;

        $buyDate = $userTernak->buy_date;
        $now = Carbon::now();
        $datetime1 = new DateTime($buyDate);
        $datetime2 = new DateTime($now);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a'); // cek sisa umur ternak
        if($days > $ternak->duration){
            return response()->json(['status'=>'401','message'=>'Ternak Not Falid',],401);
            $userTernak->update(['status'=>0]);
        }
        // $commision = rand($ternak->min_benefit,$ternak->max_benefit);
        $commision = $pakan->benefit;
        $remain = floor($commision / 24);
        
        $finalProduc = $productInWallet + $remain;
        $array[$ternak->produk->id]->qty = $finalProduc;

        // return $commision;
        DB::beginTransaction();
        try {
            $trxID = Transaction::trxID('BP');
            Investment::create([
                'user_id' => $user->id,
                'user_ternak'=>$userTernak->id,
                'transaction'=>$trxID,
                'remains'=> $remain,
                'commision'=>$commision,
                'status'=>1
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$wallet->diamon + $commision,
                'pakan'=>$wallet->pakan - $pakan->pakan,
                'hasil_ternak' => json_encode($array)
            ]);
            DB::commit();
            return response()->json(['status'=>200,'message'=>"Beri Pakan Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
            // throw $e;
        }
    }
}
