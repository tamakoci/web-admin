<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user_ternak(){
        return $this->belongsTo(UserTernak::class,'user_ternak');
    }
}
