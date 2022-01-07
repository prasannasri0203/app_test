<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripRenwalRecords extends Model
{
    use HasFactory;
    use HasFactory,SoftDeletes;
     protected $table = "strip_renwal_records";
    protected $fillable = [
            'user_plan_id',
            'user_id',
            'user_role_id',
            'product_id',
            'price_id',
            'coupon_id',
            'customer_id',
            'subcription_id',
            'charge_id',
            'card_id',
            'status'
	];
}
 