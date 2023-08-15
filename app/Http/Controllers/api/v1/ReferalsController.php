<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\ReferalBonus;
use App\Models\ReferalTree;
use App\Models\Transaction;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ReferalsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $referals = ReferalTree::referalGroupAPI();
        return response()->json(['status'=>200,'message'=>'Referals Tree '.$user->username,'data'=>$referals],200 );
    }
    public function collectBonus($id){
        $user = Auth::user();
        $ref = ReferalTree::find($id);
        if($ref->bonus <= 0){
            return response()->json(['status'=>500,'message'=>'Bonus Diamon Tidak Ada Atau 0'],500);
        }
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        $trx = Transaction::trxID('CD');

        DB::beginTransaction();
        try {
            Transaction::create([
                'user_id' => $user->id,
                'last_amount' => $wallet->diamon,
                'trx_amount' => $ref->bonus,
                'final_amount'=> $wallet->diamon + $ref->bonus,
                'trx_type'=>'+',
                'detail'=>'Collect Bonus Diamon From Referals',
                'trx_id' => $trx
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$wallet->diamon + $ref->bonus,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>$wallet->hasil_ternak
            ]);
            $ref->update(['bonus'=>0]);
            DB::commit();
            return response()->json(['status'=>200,'messege'=>'Bonus Diamon Collected'],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>500,'message'=>'Error Collect Diamond','errors'=>$th->getMessage()],500);
        }
    }
}
