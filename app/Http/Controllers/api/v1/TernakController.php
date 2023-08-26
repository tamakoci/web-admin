<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\PakanTernak;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\User;
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
        $ternakOld = UserTernak::getUserTernak();
        $t = Ternak::find(1);
        $ternak = UserTernak::with(['ternak'])->where(['user_id'=>$user->id,'status'=>1])->get();
        $maxItemsPerGroup = 100;
        $totalTernak = $ternak->count();
        $numGroups = ceil($totalTernak / $maxItemsPerGroup);

        for ($i = 0; $i < $numGroups; $i++) {
            $startIndex = $i * $maxItemsPerGroup;
            $endIndex = min(($i + 1) * $maxItemsPerGroup, $totalTernak);
            $groupData = $ternak->slice($startIndex, $endIndex - $startIndex);
            
            $groupTernak[] = [
                'nama' => $t->name,
                'durasi'=>$t->duration,
                'avatar'=>$t->avatar,
                'jumlah' => $groupData->count()
            ];
        }
        return response()->json([
            'status'=>200,
            'message'=>'Ternak '.$user->username,
            'total' =>$totalTernak,
            'group'=> $groupTernak,
            'Data'=>$ternakOld,
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


    public function beriPakanGems($pakan_cost=89){
        $type = 1;
        $user = Auth::user();
        $ternak= $user->masterplan_count;
            
        $cost = $ternak * $pakan_cost;
        $commision = $user->masterplan_count;
        $count = $ternak;

        $wallet = UserWallet::getWalletUserId($user->id);
        if($wallet->diamon < $cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Gems']);
        }
        //chek investment active date same
        $invest = Investment::where('user_id', $user->id)
            ->where(['status' => 1])
            ->whereDate('created_at', now()->toDateString())
            ->first();
            
        if($invest){
           return response()->json(['status'=>401,'message'=>'Sudah Memberi Pakan Hari Ini']);
        }

        $trxID = Transaction::trxID('BP');
        DB::beginTransaction();
        try {
            Investment::create([
                'user_id'       => $user->id,
                'user_ternak'   => 1,
                'transaction'   => $trxID,
                'collected'     => 0,
                'remains'       => 0,
                'commision'     => $commision,
                'mark'          => $type,
                'status'        => 1
            ]);
            Transaction::create([
                'user_id'       => $user->id,
                'last_amount'   => $wallet->diamon,
                'trx_amount'    => $cost,
                'final_amount'  => $wallet->diamon - $cost,
                'trx_type'      => '-',
                'detail'        => notifMsg($type,$cost,$count)['title'],
                'trx_id'        => $trxID
            ]);
            UserWallet::create([
                'user_id'   => $user->id,
                'diamon'    => $wallet->diamon - $cost,
                'pakan'     => $wallet->pakan,
                'vaksin'        => $wallet->vaksin,
                'tools'         => $wallet->tools,
                'hasil_ternak'  => $wallet->hasil_ternak
            ]);

            DB::commit();
            
            $title  = notifMsg($type,$cost,$count)['title'];
            $msg    = notifMsg($type,$cost,$count)['msg'];

            makenotif($user->id,$title,$msg);
            return response()->json(['status'=>200,'message'=>"Beri Pakan Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
    public function beriPakanWithPakan($pakan_cost=89){
        $type = 1;
        $user = Auth::user();
        $ternak= $user->masterplan_count;
        $cost = $ternak * $pakan_cost;

        $wallet = UserWallet::getWalletUserId($user->id);
        if($cost > $wallet->pakan){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Pakan']);
        }
        $commision = $user->masterplan_count;
        $count = $ternak;

        $invest = Investment::where('user_id', $user->id)
            ->where(['status' => 1])
            ->whereDate('created_at', now()->toDateString())
            ->first();
            
        if($invest){
           return response()->json(['status'=>401,'message'=>'Sudah Memberi Pakan Hari Ini']);
        }

        $trxID = Transaction::trxID('BP');
        DB::beginTransaction();
        try {
            Investment::create([
                'user_id'       => $user->id,
                'user_ternak'   => 1,
                'transaction'   => $trxID,
                'collected'     => 0,
                'remains'       => 0,
                'commision'     => $commision,
                'mark'          => $type,
                'status'        => 1
            ]);
            // Transaction::create([
            //     'user_id'       => $user->id,
            //     'last_amount'   => $wallet->diamon,
            //     'trx_amount'    => $cost,
            //     'final_amount'  => $wallet->diamon - $cost,
            //     'trx_type'=>'-',
            //     'detail'=>notifMsg($type,$cost,$count)['title'],
            //     'trx_id' => $trxID
            // ]);
            UserWallet::create([
                'user_id'   =>$user->id,
                'diamon'    =>$wallet->diamon,
                'pakan'     =>$wallet->pakan - $cost,
                'vaksin'        => $wallet->vaksin,
                'tools'         => $wallet->tools,
                'hasil_ternak'  => $wallet->hasil_ternak
            ]);

            DB::commit();
            
            $title  = notifMsg($type,$cost,$count,'Pakan')['title'];
            $msg    = notifMsg($type,$cost,$count,'Pakan')['msg'];

            makenotif($user->id,$title,$msg);
            return response()->json(['status'=>200,'message'=>"Beri Pakan Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function beriVaksinGems($vaksin_cost=89){
        $type =2;
        $user = Auth::user();
        $ternak= $user->masterplan_count;
        $cost = $ternak * $vaksin_cost;

        $wallet = UserWallet::getWalletUserId($user->id);
        if($wallet->diamon < $cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Gems.']);
        }
        $count = $ternak;

        DB::beginTransaction();
        try {
            $trxID = Transaction::trxID('BP');
            $invest = Investment::where('user_id',$user->id)->where(['mark'=>1,'status'=>1])->whereDate('created_at', now()->toDateString())->first();
            if($invest){
                UserWallet::create([
                    'user_id'       => $user->id,
                    'diamon'        => $wallet->diamon - $cost,
                    'pakan'         => $wallet->pakan,
                    'vaksin'        => $wallet->vaksin,
                    'tools'         => $wallet->tools,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                Transaction::create([
                    'user_id'       => $user->id,
                    'last_amount'   => $wallet->diamon,
                    'trx_amount'    => $cost,
                    'final_amount'  => $wallet->diamon - $cost,
                    'trx_type'      =>  '-',
                    'detail'        => notifMsg($type,$cost)['title'],
                    'trx_id' => $trxID
                ]);
                $title  = notifMsg($type,$cost,$count)['title'];
                $msg    = notifMsg($type,$cost,$count)['msg'];
            
                $invest->update(['mark'=>$type]);
                DB::commit();
                makenotif($user->id,$title,$msg);
            }else{
                return response()->json(['status'=>500,'message'=>'Tidak Dapat Memberi Vaksin. Taks sebelumnya tidak dilakukan']);
            }
            return response()->json(['status'=>200,'message'=>"Beri Vaksin Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
    public function beriVaksinWithVaksin($vaksin_cost=89){
        $type =2;
        $user = Auth::user();
        $ternak= $user->masterplan_count;
        $cost = $ternak * $vaksin_cost;

        $wallet = UserWallet::getWalletUserId($user->id);
        if($wallet->vaksin < $cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Vaksin.']);
        }
        $count = $ternak;

        DB::beginTransaction();
        try {
            $invest = Investment::where('user_id',$user->id)->where(['mark'=>1,'status'=>1])->whereDate('created_at', now()->toDateString())->first();
            if($invest){
                UserWallet::create([
                    'user_id'       => $user->id,
                    'diamon'        => $wallet->diamon,
                    'pakan'         => $wallet->pakan,
                    'vaksin'        => $wallet->vaksin - $cost,
                    'tools'         => $wallet->tools,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                // Transaction::create([
                //     'user_id'       => $user->id,
                //     'last_amount'   => $wallet->diamon,
                //     'trx_amount'    => $cost,
                //     'final_amount'  => $wallet->diamon - $cost,
                //     'trx_type'      =>  '-',
                //     'detail'        => notifMsg($type,$cost)['title'],
                //     'trx_id' => $trxID
                // ]);
                $title  = notifMsg($type,$cost,$count,'Vaksin')['title'];
                $msg    = notifMsg($type,$cost,$count,'Vaksin')['msg'];
            
                $invest->update(['mark'=>$type]);
                DB::commit();
                makenotif($user->id,$title,$msg);
            }else{
                return response()->json(['status'=>500,'message'=>'Tidak Dapat Memberi Vaksin. Taks sebelumnya tidak dilakukan']);
            }
            return response()->json(['status'=>200,'message'=>"Beri Vaksin Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function bersihKandangGems($tools_cost=89){
        $type=3;
        $user= Auth::user();
        $ternak = $user->masterplan_count;
        $cost   = $ternak * $tools_cost;
        $count  = $ternak;
        $wallet = UserWallet::getWalletUserId($user->id);

        if($wallet->diamon < $cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Gems.']);
        }

        DB::beginTransaction();
        try {
          

            $trxID = Transaction::trxID('BP');

            $invest = Investment::where('user_id',$user->id)->where(['mark'=>2,'status'=>1])->whereDate('created_at', now()->toDateString())->first();
            if($invest){
                UserWallet::create([
                    'user_id'       => $user->id,
                    'diamon'        => $wallet->diamon - $cost,
                    'pakan'         => $wallet->pakan,
                    'vaksin'        => $wallet->vaksin,
                    'tools'         => $wallet->tools,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                Transaction::create([
                    'user_id'       => $user->id,
                    'last_amount'   => $wallet->diamon,
                    'trx_amount'    => $cost,
                    'final_amount'  => $wallet->diamon - $cost,
                    'trx_type'=>'-',
                    'detail'=>notifMsg($type,$cost)['title'],
                    'trx_id' => $trxID
                ]);
                $title  = notifMsg($type,$cost,$count)['title'];
                $msg    = notifMsg($type,$cost,$count)['msg'];
                $invest->update(['mark'=>$type]);

                DB::commit();
                makenotif($user->id,$title,$msg);
            }else{
                return response()->json(['status'=>500,'message'=>'Tidak Dapat Membersihkan Kandang. Taks sebelumnya tidak dilakukan']);
            }
            return response()->json(['status'=>200,'message'=>"Bersih Kandang Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
    public function bersihKandangWithTools($tools_cost=89){
        $type=3;
        $user= Auth::user();
        $wallet = UserWallet::getWalletUserId($user->id);
        $ternak = $user->masterplan_count;
        $cost   = $ternak * $tools_cost;
        $count  = $ternak;
        if($wallet->tools < $cost){
            return response()->json(['status'=>401,'message'=>'Tidak Cukup Tools.']);
        }

        DB::beginTransaction();
        try {
            $invest = Investment::where('user_id',$user->id)->where(['mark'=>2,'status'=>1])->whereDate('created_at', now()->toDateString())->first();

            if($invest){
                UserWallet::create([
                    'user_id'       => $user->id,
                    'diamon'        => $wallet->diamon,
                    'pakan'         => $wallet->pakan,
                    'vaksin'        => $wallet->vaksin,
                    'tools'         => $wallet->tools - $cost,
                    'hasil_ternak'  => $wallet->hasil_ternak
                ]);
                // Transaction::create([
                //     'user_id'       => $user->id,
                //     'last_amount'   => $wallet->diamon,
                //     'trx_amount'    => $cost,
                //     'final_amount'  => $wallet->diamon - $cost,
                //     'trx_type'=>'-',
                //     'detail'=>notifMsg($type,$cost)['title'],
                //     'trx_id' => $trxID
                // ]);
                $title  = notifMsg($type,$cost,$count,'Tools')['title'];
                $msg    = notifMsg($type,$cost,$count,'Tools')['msg'];
                $invest->update(['mark'=>$type]);
                
                makenotif($user->id,$title,$msg);
                DB::commit();
            }else{
                return response()->json(['status'=>500,'message'=>'Tidak Dapat Membersihkan Kandang. Taks sebelumnya tidak dilakukan']);
            }
            return response()->json(['status'=>200,'message'=>"Bersih Kandang Ternak Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function ambiltelur(){
        $type=4;
        $user = Auth::user();
        $ternak= $user->masterplan_count;
        DB::beginTransaction();
        try {
            $count =$ternak;

            $wallet = UserWallet::getWalletUserId($user->id);

            $invest = Investment::where('user_id',$user->id)->where(['mark'=>3,'status'=>1])->whereDate('created_at', now()->toDateString())->first();
            if($invest){
                $hasil_ternak = json_decode($wallet->hasil_ternak);
                $array = (array)$hasil_ternak;
                $productInWallet = $array[1]->qty;
                $finalProduc = $productInWallet + $invest->commision;
                $finalProduc = $invest->commision;
                // $array[1]->qty = $finalProduc;
                // dd($array);

                UserWallet::create([
                    'user_id'   => $user->id,
                    'diamon'        => $wallet->diamon,
                    'pakan'         => $wallet->pakan,
                    'vaksin'        => $wallet->vaksin,
                    'tools'         => $wallet->tools,
                    'hasil_ternak' => '{"1":{"name":"Telur","qty":'.$finalProduc.'}}'
                ]);

                $invest->update([
                    'collected' =>  $invest->commision,
                    'mark'      => $type,
                    'status'    => 0
                ]);
                
                $title  = notifMsg($type,$invest->commision,$count)['title'];
                $msg    = notifMsg($type,$invest->commision,$count)['msg'];
                makenotif($user->id,$title,$msg);
                DB::commit();
            }else{
                return response()->json(['status'=>500,'message'=>'Tidak Ada Ternak Yang Menghasilkan Telur']);
            }
            return response()->json(['status'=>200,'message'=>"Ambil Telur Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'error'=>$e->getMessage()]);
        }
       
    }
    public function dayActivity(){
        $user = Auth::user();
        $act = Investment::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->orderByDesc('id')
            ->first();
        if(!$act){
            return [
            'status' => 400,
            'message' => 'No Activity Today',
            'data' => [
                'is_pakan'      => 0,
                'pakan_date'    => 0,
                'is_vaksin'     => 0,
                'vaksin_date'   => 0,
                'is_kandang'    => 0,
                'kandag_date'   => 0,
                'req'           => 0,
                'running'       => 0,
                'commison'      => 0,
            ]
        ];
        }
        $isPakan    = $act->mark >= 1 ? 1 : 0;
        $pakanDate  = $act->mark == 1 ? $act->updated_at : 0;
        $isVaksin    = $act->mark >= 2 ? 1 : 0;
        $vaksinDate  = $act->mark == 2 ? $act->updated_at : 0;
        $isKandang    = $act->mark >= 3 ? 1 : 0;
        $kandangDate  = $act->mark == 3 ? $act->updated_at : 0;
        if($act->mark == 1){
            $req = 'Beri Vaksin';
        }else if($act->mark == 2){
            $req = 'Bersih Kandang';
        }else if($act->mark == 3){
            $req = 'Ambil Telur';
        }else{
            $req = $act->mark;
        }
        return [
            'status' => 200,
            'message' => 'Activity Satus',
            'data' => [
                'is_pakan'      => $isPakan,
                'pakan_date'    => $pakanDate,
                'is_vaksin'     => $isVaksin,
                'vaksin_date'   => $vaksinDate,
                'is_kandang'    => $isKandang,
                'kandag_date'   => $kandangDate,
                'last_act'      => $act->updated_at->timestamp,
                'req'           => $req,
                'running'       => $act->mark,
                'commison'      => $act->commision,
                
            ]
        ];
    }

}