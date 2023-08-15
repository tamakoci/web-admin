<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function trxID($type){
        return $type . time();
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
}
