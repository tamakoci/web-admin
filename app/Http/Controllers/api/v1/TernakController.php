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
        $ternak = Ternak::where('status',1)->where('id','!=',1)->get();
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
                'dm_benefit'=>$benefitFinal,
                'text'=>'Menghasilkan '. $value->ternak->produk->name .' '. $value->benefit. ' '.$value->ternak->produk->satuan.'/Hari Sejumlah '. $benefitFinal.' Diamond'
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
        // if($request->ternak_id == 1){
        //     return response()->json(['status'=>'405','message'=>'Cannot Buy Free Ternak'],Response::HTTP_METHOD_NOT_ALLOWED);
        // }
        $user = Auth::user();
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return response()->json(['status'=>402,'message'=>'Diamon Tidak Ada!'],Response::HTTP_PAYMENT_REQUIRED);
        }
        $ternak = Ternak::find($request->ternak_id);
        if(!$ternak){
            return response()->json(['status'=>404,'message'=>'Data ternak not found'],404);
        }
        $dm = $wallet->diamon;
        if($dm < $ternak->price){
            return response()->json(['status'=>402,'message'=>'Diamon tidak cukup'],Response::HTTP_PAYMENT_REQUIRED);
        }
        DB::beginTransaction();
        try {
            
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
    public function userTernakDetail($id){
       
        $ternak = UserTernak::getUserTernakDetail($id);
        return response()->json([
            'status'=>200,
            'message'=>'Detail Ternak ' .$id,
            'Data'=>$ternak
        ]);
    }
    
}
