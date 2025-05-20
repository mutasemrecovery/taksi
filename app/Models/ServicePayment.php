<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'payment_method'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
