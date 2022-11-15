<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    public static function countProductUser(){
        $susu   = 0;
        $telur  = 0;
        $daging = 0;
        $user = User::where('user_role',1)->get();

        foreach ($user as $key => $value) {
            $wallet = UserWallet::where('user_id',$value->id)->orderByDesc('id')->first();
            if ($wallet) {
                $product = json_decode($wallet->hasil_ternak,true);
                $telur += $product[1]['qty'];
                $susu += $product[2]['qty'];
                $daging += $product[3]['qty'];
            }
        }
        return [
            'telur' => $telur,
            'telur_persen' => $telur/1000,
            'susu'  => $susu,
            'susu_persen'  => $susu/10,
            'daging'=>$daging,
            'daging_persen'=>$daging
        ];

    }
}
