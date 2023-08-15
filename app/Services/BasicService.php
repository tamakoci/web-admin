<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Support\Facades\DB;

/**
 * Class BasicService
 * @package App\Services
 */
class BasicService
{
    public function buyTernak($user,$data){
        $wallet  = $this->getWallet($user);

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

    public function getWallet($user)
    {
        return UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
    }
}
