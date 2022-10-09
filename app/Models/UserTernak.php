<?php

namespace App\Models;

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

    public function ternak(){
        return $this->belongsTo(Ternak::class,'ternak_id');
    }
}
