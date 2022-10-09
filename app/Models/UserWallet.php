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
    public function hasilTernak()
    {
        return json_decode($this->hasil_ternak);
    }
}
