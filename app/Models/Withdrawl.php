<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getStatus(){
        // return $this->status;
        if ($this->status == 1){
            return '<span class="badge bg-secondary text-white shadow-sm w-100">Pending</span>';
        }elseif($this->status == 2){
            return '<span class="badge bg-success text-white shadow-sm w-100">Success</span>';
        }else{
            return '<span class="badge bg-danger text-white shadow-sm w-100">Cancel</span>';
        }
    }
}
