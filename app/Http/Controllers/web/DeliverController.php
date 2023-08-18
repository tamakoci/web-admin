<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverController extends Controller
{
    public function ternakUser(){
        $data['title'] = 'Ternak User';
        $data['table'] = UserTernak::with(['user','ternak'])->get();
        // dd($data);
        $data['user']  = User::with(['wallet'])->where('user_role',1)->get();
        $data['ternak']  = Ternak::where('status',1)->get();
        return view('admin.ternakuser',$data);
    }
    public function beliAyamPost(Request $request){
        $request->validate([
            'ternak_id' => 'required',
            'user_id' => 'required',
        ]);
       
        // if($request->ternak_id == 1){
        //     return response()->json(['status'=>'405','message'=>'Cannot Buy Free Ternak'],Response::HTTP_METHOD_NOT_ALLOWED);
        // }
        $user = User::find($request->user_id);
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return redirect()->back()->with('error','Wallet Not Found');
        }
        $ternak = Ternak::find($request->ternak_id);
        if(!$ternak){
            return redirect()->back()->with('error','Ternak Not Found');
        }
        $dm = $wallet->diamon;
        if($dm < $ternak->price){
            return redirect()->back()->with('error','Gems Tidak Cukup');
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
                'detail'=>'Buy Ternak By Gems',
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
            return redirect()->back()->with('success','Beli Ternak Sukses');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
            // throw $e;
        }
    }
}
