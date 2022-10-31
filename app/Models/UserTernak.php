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
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public static function getUserTernak(){
        $user = auth()->user();
        $data = [];
        $ternak = UserTernak::where(['user_id'=> $user->id, 'status'=>true])->get();
        // return $ternak;
        // dd($ternak);
        foreach ($ternak as $key => $value) {

            $invest  = Investment::where(['user_ternak'=>$value->id,'status'=>1])->first();
            // return $invest;
            $umur_start = date('Y-m-d H:i:s',strtotime($value->buy_date));
            $umur_end = date('Y-m-d H:i:s',strtotime("+".$value->ternak->duration. " day", strtotime($umur_start)));
            
            if(!$invest){
                $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_end      =  date("Y-m-d H:i:s"); // this format is string comparable
                $pakan_sts      = 0;
            }else{
                $makan1     = date("Y-m-d H:i:s", strtotime($invest->created_at));
                $makan2     = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                $date_now   = date("Y-m-d H:i:s"); // this format is string comparable
                if ($date_now > $makan2) {
                    $pakan_start    = date("Y-m-d H:i:s"); // this format is string comparable
                    $pakan_end      = date("Y-m-d H:i:s"); // this format is string comparable
                    $pakan_sts      = 0;

                }else{
                    $pakan_start    = $makan1;
                    $pakan_end      = $makan2;
                    $pakan_sts      = 1;

                }
            }
            $data[] = [
                'id'=>$value->id,
                'ternak_id'=>$value->ternak_id,
                'name'=>$value->ternak->name,
                'avatar'=>$value->ternak->avatar,
                'time_now'=>date('Y-m-d H:i:s'),
                'umur_start'=>$umur_start,
                'umur_end'=>$umur_end,
                'pakan_start'=>$pakan_start,
                'pakan_end'=>$pakan_end,
                'pakan_status'=>$pakan_sts
            ];
        }
        return $data;

    }

    public function ternak(){
        return $this->belongsTo(Ternak::class,'ternak_id');
    }
    public function stsPakan(){
        return $this->hasOne(Investment::class,'id')->latest();
    }
}
