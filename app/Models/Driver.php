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

 public function option()
   {
      return $this->belongsTo(Option::class);
   }

}
