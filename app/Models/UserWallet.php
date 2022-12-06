<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
    public static function giveFreeDiamond($id,$qty = 500){
        UserWallet::create([
            'user_id'=>$id,
            'diamon'=> $qty,
            'pakan'=>0,
            'hasil_ternak'=> json_encode(Product::produk())
        ]);
    }


    public static function getWallet(){
        return UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
    }
    public static function test(){
        return UserWallet::where('user_id',2)->orderByDesc('id')->first();
    }

    public function hasilTernak()
    {
        return json_decode($this->hasil_ternak);
    }
}
