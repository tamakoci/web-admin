<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferalTree extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class,'user_ref');
    }
    public static function referalGroup(){
        $group = [];
        for ($i=1; $i < 4; $i++) { 
            $group[$i] = ReferalTree::with('user')->where(['user_id'=>auth()->user()->id,'level'=>$i])->get();
        }
        return $group; 
    }
    public static function referalGroupDetail($id){
        $group = [];
        for ($i=1; $i < 4; $i++) { 
            $group[$i] = ReferalTree::with('user')->where(['user_id'=>$id,'level'=>$i])->get();
        }
        return $group; 
    }
}
