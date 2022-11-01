<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function makeReferal($user = null){
        if($user == null){
            $userGet = Auth::user();
            $randomString = substr($userGet->username, 0, 4);
            $count = $userGet->id;
        }else{
            $randomString = substr($user, 0, 4);
            $users = User::all();
            $count = $users->count() + 1;
        }
        $ref = strtolower($randomString). sprintf("%03s", $count);
        return $ref;

    }
    public function getAvatar()
    {
        if($this->avatar) {
            return $this->avatar;
        } else {
            return asset('avatar.png');
        }
    }
    public function getStatus(){
        // return $this->status;
        if ($this->status == 1){
            return '<span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span>';
        }else{
            return '<span class="badge bg-gradient-bloody text-white shadow-sm w-100">Inactive</span>';
        }
    }
    public function getRole(){
        $role = UserRole::find($this->user_role);
        return '<span class="badge bg-gradient-blooker text-white shadow-sm w-100">'.$role->role_name.'</span>';
    }
    public function getReferal(){
            return '<span class="badge bg-gradient-quepal text-white shadow-sm w-100">'.$this->user_ref.'</span>';    
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
    public function tree(){
        return $this->hasMany(ReferalTree::class);
    }

    public static function createLevelUser($userID){
        $lv1 = User::where('ref_to','!=',null)->find($userID);
        if($lv1){
            ReferalTree::create([
                'user_id'   => $lv1->ref_to,
                'level'     => 1,
                'user_ref'  => $userID,
                'status'    => true
            ]);
            $lv2 = User::where('ref_to','!=',null)->find($lv1->ref_to);
            if($lv2){
                ReferalTree::create([
                    'user_id'   => $lv2->ref_to,
                    'level'     => 2,
                    'user_ref'  => $userID,
                    'status'    => true
    
                ]);
                $lv3 = User::where('ref_to','!=',null)->find($lv2->ref_to);
                if($lv3){
                    ReferalTree::create([
                        'user_id'   => $lv3->ref_to,
                        'level'     => 3,
                        'user_ref'  => $userID,
                        'status'    => true
                    ]);
                }
            }
        }
        return true;
    }

    public static function getUser(){
        $user = Auth::user();
        return [
            'id'    => $user->id,
            'email' => $user->email,
            'username'  => $user->username,
            'phone'     => $user->phone,
            'avatar'    => $user->avatar != null ? $user->avatar : asset('avatar.png'),
            'active_tutor' => $user->active_tutor,
            'user_ref'  => $user->user_ref,
            'user_role' => $user->user_role
        ];
    }
}
