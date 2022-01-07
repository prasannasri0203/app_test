<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Kyslik\ColumnSortable\Sortable;

class Renewal_details extends Model
{
    use HasFactory; 
    use Sortable;

   public $sortable = ['coupon_id','user_id','plan_id' ,'renewal_date', 'description','payment_type','created_at']; 

    public function user()
    {
       return $this->belongsTo('App\Models\User','user_id','id');
    }  
      public function userDetails()
    {
      return $this->belongsTo('App\Models\UserDetail','user_id','user_id');
    }
    public function subscription()
    {
       return $this->belongsTo('App\Models\Superadmin\Subscription','plan_id','id');
    }
    public function coupon()
    {
       return $this->belongsTo('App\Models\Superadmin\Coupon','coupon_id','id');
    }
    public function deletedUser()
    {
       return $this->belongsTo('App\Models\DeletedUser','user_id','user_id');
    }
}
