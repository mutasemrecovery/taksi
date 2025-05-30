<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    
     protected $guarded = [];
    
     
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
