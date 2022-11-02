<?php

namespace App\Http\Controllers;

use App\Models\CronLog;
use App\Models\Investment;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    public function produksiTernak(){
        // cek di invest;
        $jam =  date("H");
        $tanggal =  date("d M");
        // dd($tanggal);
        $invest = Investment::with(['userTernak','userTernak.ternak','userTernak.ternak.produk'])->where('status',1)->get();
        // dd($invest);
        foreach ($invest as $key => $value) {
            $start      = date("Y-m-d H:i:s", strtotime($value->created_at));
            $now        = date("Y-m-d H:i:s");
            $end        = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($start)));

            $remains    = $value->remains;
            $total      = $value->commision;
            $perjam     = floor($total / 24);
            $kurang     = $total - $remains;
            
            $produkId   = $value->userTernak->ternak->produk->id;
            $wallet = UserWallet::where('user_id',$value->user_id)->orderByDesc('id')->first();
            $hasil_ternak = json_decode($wallet->hasil_ternak);
            $array = (array)$hasil_ternak;
            $productInWallet = $array[$produkId]->qty;
           

            DB::beginTransaction();
            if($value->userTernak->ternak_id != 4){ //cek jika userternak bukan domba. domba_id = 4 ;
                
                if($end > $now){ // cek tanggal berakhir jika lebih besar dari hari ini berarti masih berjalan;
                    try {
                        if(($perjam + $remains) < $total){
                            $update = [
                                'remains' => $remains + $perjam,
                            ];
                            $finalProduc = $productInWallet + $perjam;
                            $array[$produkId]->qty = $finalProduc;
                        }else{
                            $update = [
                                'remains' => $remains + $kurang,
                                'status'  => 0
                            ];
                            $finalProduc = $productInWallet + $kurang;
                            $array[$produkId]->qty = $finalProduc;
                        }
                        Investment::find($value->id)->update($update);

                        UserWallet::create([
                            'user_id'=>$value->user_id,
                            'diamon'=>$wallet->diamon,
                            'pakan'=>$wallet->pakan,
                            'hasil_ternak'=>json_encode($array)
                        ]);

                        
                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollback();
                        dd($e->getMessage());
                    }
                }else{ //jika tidak, berikan sisa remain dan ubah ke nonaktif
                    try {
                        Investment::find($value->id)->update([
                            'remains' => $remains + $kurang,
                            'status'  => 0
                        ]);
                        $finalProduc = $productInWallet + $kurang;
                        $array[$produkId]->qty = $finalProduc;
                        UserWallet::create([
                            'user_id'=>$value->user_id,
                            'diamon'=>$wallet->diamon,
                            'pakan'=>$wallet->pakan,
                            'hasil_ternak'=>json_encode($array)
                        ]);

                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollback();
                        dd($e->getMessage());
                    }
                }
            }else{
                $tgl_beli   = date("Y-m-d H:i:s", strtotime($value->userTernak->buy_date));
                $tgl_akhir  = date('Y-m-d H:i:s',strtotime("+7 day", strtotime($tgl_beli)));
                if($now > $tgl_akhir){ //jika sudah melebihi tgl umur ternak maka bonus daging dikirmkan ke wallet
                    Investment::find($value->id)->update([
                            'remains' => $total,
                            'status'  => 0
                    ]);
                    $finalProduc = $productInWallet + $total;
                    $array[$produkId]->qty = $finalProduc;
                    UserWallet::create([
                        'user_id'=>$value->user_id,
                        'diamon'=>$wallet->diamon,
                        'pakan'=>$wallet->pakan,
                        'hasil_ternak'=>json_encode($array)
                    ]);
                }
            }
        }
        CronLog::create([
            'remains' => $jam,
            'note'    => 'cron distribusi hasil ternak tanggal '.$tanggal .' jam ke '.$jam
        ]);
        return response()->json(['status'=>200,'message'=>'send produksi ternak '. date("Y-m-d H:i:s")]);
    }
}
