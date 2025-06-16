<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Service extends Model
{
    use HasFactory;

     protected $guarded=[];
    protected $appends = ['name','photo_url'];

  
    
    // Add a custom accessor for the photo URL
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Use the APP_URL from the .env file
            $baseUrl = rtrim(config('app.url'), '/');
            return $baseUrl . '/assets/admin/uploads/' . $this->photo;
        }
        
        return null;
    }
    
    public function getNameAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }
    public function getName()
    {
        $locale = App::getLocale();
        return $locale == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function servicePayments()
    {
        return $this->hasMany(ServicePayment::class);
    }

     public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_services')
            ->withPivot('status')
            ->withTimestamps();
    }

    // Add a direct relationship to driver_services
    public function driverServices()
    {
        return $this->hasMany(DriverService::class);
    }
    
    
    
    /**
     * Get the type of commission text.
     * 
     * @return string
     */
    public function getCommisionTypeText()
    {
        return $this->type_of_commision == 1 
               ? __('messages.Fixed_Amount') 
               : __('messages.Percentage');
    }

    /**
     * Get the payment method text.
     * 
     * @return string
     */
    public function getPaymentMethodText()
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
}
