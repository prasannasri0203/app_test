<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DegradedSubUser extends Model
{
    use HasFactory; 
    
    public function userRole()
    {
      return $this->hasOne('App\Models\UserRole','id','user_role_id');
    }
    public function userTeamRole()
    {
      return $this->hasOne('App\Models\TeamUserRole','id','user_role_id');
    }
}
