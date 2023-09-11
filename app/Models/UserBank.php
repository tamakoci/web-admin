<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'regist',
        "created_at",
        "updated_at"
    ];

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
