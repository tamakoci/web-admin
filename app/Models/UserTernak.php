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
            $umur_start = date('Y-m-d H:i:s',strtotime($value->buy_date));
            $umur_end = date('Y-m-d H:i:s',strtotime("+".$value->ternak->duration. " day", strtotime($umur_start)));

           

            
            if(!$invest){
                $pakan_start = 0;
                $pakan_end  = 0;
            }else{
                $makan1 = $invest->created_at;
                $makan2 = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($makan1)));
                $date_now = date("Y-m-d H:i:s"); // this format is string comparable
                if ($date_now > $makan2) {
                    $pakan_start = 0;
                    $pakan_end  = 0;
                }else{
                    $pakan_start = strtotime($makan1);
                    $pakan_end   = strtotime($makan2); 
                }
            }
            $data[] = [
                'id'=>$value->id,
                'ternak_id'=>$value->ternak_id,
                'name'=>$value->ternak->name,
                'avatar'=>$value->ternak->avatar,
                // 'umur'=>[
                //     'start'=>strtotime($umur_start),
                //     'end'=>strtotime($umur_end)
                // ],
                'umur_start'=>strtotime($umur_start),
                'umur_end'=>strtotime($umur_end),
                'pakan_start'=>$pakan_start,
                'pakan_end'=>$pakan_end
                // 'pakan'=> [
                //     'start'=>$pakan_start,
                //     'end'=>$pakan_end
                // ]
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
