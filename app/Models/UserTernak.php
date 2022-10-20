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
        $data = [];
        $ternak = UserTernak::with(['ternak'])->where(['user_id'=>auth()->user()->id,'status'=>true])->get();
        foreach ($ternak as $key => $value) {
            $invest  = Investment::where('user_ternak',$value->ternak->id)->orderByDesc('id')->first();
            if(!$invest){
                $percent = 0;
            }else{
                $now = Carbon::now();
                $datetime1 = new DateTime($invest->created_at);
                $datetime2 = new DateTime($now);
                $diff = $datetime1->diff($datetime2);

                $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                $hoursInSecs = $diff->h * 60 * 60;
                $minsInSecs = $diff->i * 60;

                $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
                if($seconds > 86400){
                    $percent = 0;
                }else{
                    $percent = 100 - round($seconds / 864);
                }
            }
            $data[] = [
                'id'=>$value->id,
                'name'=>$value->ternak->name,
                'avatar'=>$value->ternak->avatar,
                'percent_pakan'=> (int)$percent 
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
