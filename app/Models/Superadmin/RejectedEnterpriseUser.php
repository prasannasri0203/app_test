<?php

namespace App\Models\Superadmin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Superadmin\Renewal_details;

class RejectedEnterpriseUser extends Authenticatable
{ 
      use HasApiTokens, HasFactory, Notifiable; 
    use Sortable; 

     public $sortable = [
       'name', 'user_id', 'email','user_role_id','login_at','created_at','organization_name','contact_no'];

    public function userDetail()
    {
      return $this->hasOne('App\Models\UserDetail','user_id','user_id');
    }
  public function userRenewalDatail()
    {
      return $this->hasOne('App\Models\Superadmin\Renewal_details','user_id','user_id');
    }
}
