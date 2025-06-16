<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'payment_method'];

    protected $appends = ['payment_method_text'];

    public function getPaymentMethodTextAttribute()
    {
        switch ($this->payment_method) {
            case 1:
                return __('messages.Cash');
            case 2:
                return __('messages.Visa');
            case 3:
                return __('messages.Wallet');
            default:
                return __('messages.Unknown');
        }
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
