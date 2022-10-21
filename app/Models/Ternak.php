<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ternak extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function pakan(){
        return $this->hasMany(PakanTernak::class);
    }
    public function user_ternak(){
        return $this->hasMany(UserTernak::class);
    }
    public function produk(){
        return $this->belongsTo(Product::class,'produk_id');
    }
}
