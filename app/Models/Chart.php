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
        $persen_telur = $telur/1000;
        $persen_susu = $susu/10;
        return [
            'telur' => round($persen_telur),
            'susu'  => round($persen_susu),
            'daging'=> $daging,
        ];

    }
}
 