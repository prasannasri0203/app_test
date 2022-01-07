<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;

class Admin extends Model implements AuthContract 
{ 
    use Authenticatable,Notifiable;
	protected $guard = 'superadmin';
 
     protected $fillable = [
        'name', 'email','email_otp','mobile','admin_role', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
     protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
	