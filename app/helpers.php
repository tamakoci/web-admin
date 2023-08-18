<?php

use App\Models\Notif;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

if(!function_exists('active_user')){
    // $user = JWTAuth::authenticate($request->token);

    $user = 'test';
    return $user;
}
function notif(){
    $data = Notif::where('user_id',auth()->user()->id)->orwhere('all_user',1)->orderByDesc('id')->limit(5)->get();
    return ['data'=>$data,'count'=>$data->count()];
}

function tambahEnter($inputString) {
    $words = explode(" ", $inputString);
    $outputString = "";
    
    $charCount = 0;
    foreach ($words as $word) {
        $wordLength = strlen($word);
        
        if ($charCount + $wordLength > 50) {
            $outputString = rtrim($outputString) . "<br>";
            $charCount = 0;
        }
        
        $outputString .= $word . " ";
        $charCount += $wordLength + 1; // Include space
        
        if ($charCount >= 30 && $charCount <= 50) {
            $outputString = rtrim($outputString) . "<br>";
            $charCount = 0;
        }
    }
    
    return rtrim($outputString);
}

