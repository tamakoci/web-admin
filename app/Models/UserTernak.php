<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTernak extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function ternak(){
        return $this->belongsTo(Ternak::class,'ternak_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function stsPakan(){
        return $this->hasOne(Investment::class,'id')->latest();
    }

    public static function getUserTernak(){
        $user = auth()->user();
        $data = [];
        // cek relasi;
        // $investment = UserTernak('')
        $ternak = UserTernak::with('ternak')->where(['user_id'=> $user->id,'status'=>1])->get();
        foreach ($ternak as $key => $value) {

            $invest  = Investment::where(['user_ternak'=>$value->id])->orderByDesc('id')->first();
            if( isset($invest) && $value->status == 0 && $invest->remains == 0){
                break;
            }
            // return $invest;
            $umur_start = date('Y-m-d H:i:s',strtotime($value->buy_date));
            $umur_end = date('Y-m-d H:i:s',strtotime("+".$value->ternak->duration. " day", strtotime($umur_start)));
            $now = date('Y-m-d H:i:s');
            if(!$invest){
                $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_sts      = 0;
                $remain         = 0;
            }elseif($invest->status == 0){
                $cek_remains    = $invest->remains;
                if($cek_remains > 0){
                    $pakan_sts      = 1;
                }else{
                    $pakan_sts      = 0;
                }
                $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
                $remain         = $invest->remains;
            }else{
                if($value->ternak_id != 4){
                    $makan1     = date("Y-m-d H:i:s", strtotime($invest->created_at));
                }else{
                    $makan1     = date("Y-m-d H:i:s", strtotime($invest->updated_at));
                }

                $makan2     = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                // $end            = new DateTime($makan2);
                // $itsnow         = new DateTime($now);
                // $interval = $end->diff($itsnow);
                if($now > $makan2){
                    $sts = 0;
                }else{
                    $sts = 1;
                }
                $pakan_start    = $makan1;
                $pakan_end      = $makan2;
                $remain         = $invest->remains;
                $pakan_sts      = $sts;
            }
            $data[] = [
                'id'=>$value->id,
                'ternak_id'=>$value->ternak_id,
                'name'=>$value->ternak->name,
                'avatar'=>$value->ternak->avatar,
                'time_now'=>$now,
                'umur_start'=>$umur_start,
                'umur_end'=>$umur_end,
                'pakan_start'=>$pakan_start,
                'pakan_end'=>$pakan_end,
                'pakan_status'=>$pakan_sts,
                'remains'=>$remain,
            ];
        }
        return $data;

    }
    public static function getUserTernakGroup(){
        $user = auth()->user();
        $data = [];
        // cek relasi;
        // $investment = UserTernak('')
        $ternak = UserTernak::with('ternak')->where(['user_id'=> $user->id,'status'=>1])->get();
        $ternak = UserTernak::with('ternak')->where(['user_id'=> $user->id,'status'=>1])->orderByDesc('id')->first();
        foreach ($ternak as $key => $value) {

            $invest  = Investment::where(['user_ternak'=>$value->id])->orderByDesc('id')->first();
            if( isset($invest) && $value->status == 0 && $invest->remains == 0){
                break;
            }
            // return $invest;
            $umur_start = date('Y-m-d H:i:s',strtotime($value->buy_date));
            $umur_end = date('Y-m-d H:i:s',strtotime("+".$value->ternak->duration. " day", strtotime($umur_start)));
            $now = date('Y-m-d H:i:s');
            if(!$invest){
                $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_sts      = 0;
                $remain         = 0;
            }elseif($invest->status == 0){
                $cek_remains    = $invest->remains;
                if($cek_remains > 0){
                    $pakan_sts      = 1;
                }else{
                    $pakan_sts      = 0;
                }
                $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
                $remain         = $invest->remains;
            }else{
                if($value->ternak_id != 4){
                    $makan1     = date("Y-m-d H:i:s", strtotime($invest->created_at));
                }else{
                    $makan1     = date("Y-m-d H:i:s", strtotime($invest->updated_at));
                }

                $makan2     = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                // $end            = new DateTime($makan2);
                // $itsnow         = new DateTime($now);
                // $interval = $end->diff($itsnow);
                if($now > $makan2){
                    $sts = 0;
                }else{
                    $sts = 1;
                }
                $pakan_start    = $makan1;
                $pakan_end      = $makan2;
                $remain         = $invest->remains;
                $pakan_sts      = $sts;
            }
            $data[] = [
                'id'=>$value->id,
                'ternak_id'=>$value->ternak_id,
                'name'=>$value->ternak->name,
                'avatar'=>$value->ternak->avatar,
                'time_now'=>$now,
                'umur_start'=>$umur_start,
                'umur_end'=>$umur_end,
                'pakan_start'=>$pakan_start,
                'pakan_end'=>$pakan_end,
                'pakan_status'=>$pakan_sts,
                'remains'=>$remain,
            ];
        }
        $data = [
            'id'=>$value->id,
            'ternak_id'=>$value->ternak_id,
            'name'=>$value->ternak->name,
            'avatar'=>$value->ternak->avatar,
            'time_now'=>$now,
            'umur_start'=>$umur_start,
            'umur_end'=>$umur_end,
            'pakan_start'=>$pakan_start,
            'pakan_end'=>$pakan_end,
            'pakan_status'=>$pakan_sts,
            'remains'=>$remain,
        ];
    }

    public static function getUserTernakDetail($id){
        $invest  = Investment::where(['user_ternak'=>$id,'status'=>1])->orderByDesc('id')->first();
      
        $userTernak = UserTernak::with('ternak','ternak.produk')->find($id);
        $umur_start = date('Y-m-d H:i:s',strtotime($userTernak->buy_date));
        $umur_end = date('Y-m-d H:i:s',strtotime("+".$userTernak->ternak->duration. " day", strtotime($umur_start)));
        $now = date('Y-m-d H:i:s');
        if(!$invest){
            $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
            $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
            $pakan_sts      = 0;
            $remain         = 0;
        }elseif($invest->status == 0){
            $cek_remains    = $invest->remains;
            if($cek_remains > 0){
                $pakan_sts      = 1;
            }else{
                $pakan_sts      = 0;
            }
            $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
            $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
            $remain         = $invest->remains;
        }else{
            if($userTernak->ternak_id == 4){
                $makan1     = date("Y-m-d H:i:s", strtotime($invest->updated_at));
                $makan2     = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                if($now > $makan2){
                    $sts = 0;
                }else{
                    $sts = 1;
                }
            }else{
                $makan1     = date("Y-m-d H:i:s", strtotime($invest->created_at));
                $makan2     = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                if($now > $makan2){
                    if($invest->status == 1 && $invest->remains != 0){
                        $sts = 1;
                    }else{
                        $sts = 0;
                    }
                }else{
                    $sts = 1;
                }
            }
            $pakan_start    = $makan1;
            $pakan_end      = $makan2;
            $remain         = $invest->remains;
            $pakan_sts      = $sts;
        }
        $data[] = [
            'id'            => $userTernak->id,
            'ternak_id'     => $userTernak->ternak_id,
            'name'          => $userTernak->ternak->name,
            'avatar'        => $userTernak->ternak->avatar,
            'time_now'      => $now,
            'umur_start'    => $umur_start,
            'umur_end'      => $umur_end,
            'pakan_start'   => $pakan_start,
            'pakan_end'     => $pakan_end,
            'pakan_status'  => $pakan_sts,
            'remains'       => $remain,
            'produk'        => $userTernak->ternak->produk->name,
            'satuan'        => $userTernak->ternak->produk->satuan,
            'status'        => $pakan_sts
        ];
        return $data;

    }

}
