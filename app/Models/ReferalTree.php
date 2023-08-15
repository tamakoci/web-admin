<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ReferalTree extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class,'user_ref');
    }
    public static function referalGroup(){
        $group = [];
        for ($i=1; $i < 4; $i++) { 
            $group[$i] = ReferalTree::with('user')->where(['user_id'=>auth()->user()->id,'level'=>$i])->get();
        }
        return $group; 
    }
     public static function referalGroupAPI(){
        $group = [];
        for ($i=1; $i < 4; $i++) { 
            $ref = ReferalTree::with('user')->where(['user_id'=>auth()->user()->id,'level'=>$i])->get();
            $data = [];
            foreach ($ref as $key => $value) {
                $data[] = [
                    'ref_id'    => $value->id,
                    'level'     => $value->level,
                    'user_id'   => $value->user->id,
                    'email'     => $value->user->email,
                    'username'  => $value->user->username,
                    'avatar'    => $value->user->getAvatar(),
                    'user_ref'  => $value->user->user_ref,
                    'dm_bonus'  => $value->bonus
                ];
            }
            $group[$i] = $data; 
        }
        return $group; 
    }
    public static function referalGroupDetail($id){
        $group = [];
        for ($i=1; $i < 4; $i++) { 
            $group[$i] = ReferalTree::with('user')->where(['user_id'=>$id,'level'=>$i])->get();
        }
        return $group; 
    }

    public static function distribusiBonus($userId,$diamon,$trxID) {
        $ref = ReferalTree::where('user_ref',$userId)->get();
        foreach ($ref as $key => $value) {
            DB::beginTransaction();
            try {
                if($value->level == 1){
                    $bonus = 30;
                }elseif($value->level == 2){
                    $bonus = 20;
                }else{
                    $bonus = 10;
                }
                
                $amunt = $bonus / 100 * $diamon;
                $set = ReferalTree::find($value->id);
                $set->update([
                    'bonus' => $set->bonus + $amunt
                ]);
                $bonuses = [
                    'from_user_id'  => $userId,
                    'to_user_id'    => $value->user_id,
                    'amount'        => $amunt,
                    'transaction'   => $trxID,
                    'type'          => '+',
                    'remaks'        => 'Distribusi Bonus Diamon Level '.$value->level
                ];
                $setBonus = ReferalBonus::create($bonuses);
                // return $;
                // return $setBonus;
                DB::commit();
            } catch (QueryException $th) {
                DB::rollBack();
                return $th->getMessage();
            }
        }
    }
}
