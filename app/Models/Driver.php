<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class Driver extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   
   protected $guarded = [];

   protected $hidden = [
      'password',
      'remember_token',
   ];

      // Append the photo_url attribute to JSON responses
    protected $appends = ['photo_url'];
    
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
    
 public function option()
   {
      return $this->belongsTo(Option::class);
   }

}
