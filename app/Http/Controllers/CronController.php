<?php

namespace App\Http\Controllers;

use App\Models\CronFail;
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
            $collected  = $value->collected;
            $total      = $value->commision;
            $perjam     = floor($total / 24);
            $pendapatan = $remains + $collected;
            $kurang     = $total - $pendapatan;

            DB::beginTransaction();
            try {
                if($value->userTernak->ternak_id != 4){ //cek jika userternak bukan domba. domba_id = 4 ;
                    
                    if($end > $now){ // cek tanggal berakhir jika lebih besar dari hari ini berarti masih berjalan;
                    
                        if(($perjam + $remains + $collected) < $total){ //jika fee per jam (pembagian perjam) + remain (pendapatan yg masuk) lebih besar dari total_bisa_dapat
                            $update = [
                                'remains' => $remains + $perjam,
                            ];
                        
                        }else{
                           if($remains == 0 && $collected == $total){
                                $update = [
                                    'remains' => $remains + $kurang, //berikan sisa belum dapat
                                    'status'  => 0
                                ];
                           }else{
                                $update = [
                                    'remains' => $remains + $perjam,
                                ];
                           }
                          
                        }
                        Investment::find($value->id)->update($update);
                    }else{ //jika tanggal berakhir jika lebih kecil dari hari ini, berikan sisa remain dan ubah ke nonaktif
                        Investment::find($value->id)->update([
                            'remains' => $remains + $kurang,
                            'status'  => 0
                        ]);

                    }
                }else{ // juka ternak domba penghasil daging
                    $tgl_beli   = date("Y-m-d H:i:s", strtotime($value->userTernak->buy_date));
                    $tgl_akhir  = date('Y-m-d H:i:s',strtotime("+7 day", strtotime($tgl_beli)));
                    if($now > $tgl_akhir){ //jika sudah melebihi tgl umur ternak maka bonus daging dikirmkan ke wallet
                        Investment::find($value->id)->update([
                                'remains' => $remains + $kurang,
                                'status'  => 0
                        ]);
                    }
                        
                }
                CronLog::create([
                    'remains' => $jam,
                    'note'    => 'cron distribusi hasil ternak tanggal '.$tanggal .' jam ke '.$jam
                ]);
                DB::commit();
                return response()->json(['status'=>200,'message'=>'send produksi ternak '. date("Y-m-d H:i:s")]);
            } catch (\Throwable $e) {
                DB::rollback();
                CronFail::create([
                    'flag'=>'Cron produksi ternak',
                    'note'=>$e->getMessage(),
                ]);
                dd($e->getMessage());
            }

        }
    }
}
