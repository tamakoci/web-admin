<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'status',
        "created_at",
        "updated_at"
    ];
    public function userbank(){
        return $this->hasMany(UserBank::class);
    }
}
