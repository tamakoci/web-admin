<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public static function produk(){
        $produk = Product::where('status',1)->get();
        $prod=[];
        foreach ($produk as $key => $value) {
            $prod[$value->id] = [ "name" => $value->name,'qty'=>0];
        }
        return $prod;
    }
    public static function produkTelur(){
        $produk = Product::where('status',1)->get();
        $prod=[];
        foreach ($produk as $key => $value) {
            $prod[$value->id] = [ "name" => $value->name,'qty'=>1];
        }
        return $prod;
    }
    public function market(){
        return $this->hasMany(Market::class);
    }
    public function ternak(){
        return $this->hasMany(Ternak::class);
    }
}
