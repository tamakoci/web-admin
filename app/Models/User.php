<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
