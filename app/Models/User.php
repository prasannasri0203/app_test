<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Superadmin\Renewal_details;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use Sortable; 
         
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'otp',
        'otp_is_verified',
        'verify_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     public $sortable = [
       'name', 'user_id', 'email','user_role_id','login_at','created_at'];

       protected $dates = ['login_at'];


    public function userDetail()
    {
      return $this->hasOne('App\Models\UserDetail','user_id','id');
    }
    public function userRole()
    {
      return $this->hasOne('App\Models\UserRole','id','user_role_id');
    }
    public function userTeamRole()
    {
      return $this->hasOne('App\Models\TeamUserRole','id','user_role_id');
    }
    public function userRenewalDatail()
    {
      return $this->hasOne('App\Models\Superadmin\Renewal_details','user_id','id')->latest();
    }
    
    public function userTemplate()
    {
      return $this->belongsTo('App\Models\User_template','user_id','id');
    }
    public function planDetail()
    {
      return $this->hasOne('App\Models\Superadmin\Subscription','id','plan_id');
    }
    public function user()
    {
       return $this->belongsTo('App\Models\Note','user_id','id');
    }

    public function team($id)
    {
       $team =  User::find($id);
       return optional($team)->name;

    }
}
