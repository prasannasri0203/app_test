<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Validator;
use Kyslik\ColumnSortable\Sortable;
use App\Models\UserRole;
use App\Models\Superadmin\Renewal_details;

class Coupon extends Model
{
	
    use Sortable;
	use HasFactory; 
	protected $fillable = [
		'subscription_plan_type','coupon_code', 'coupon_name','price','discount', 'start_date', 'end_date','status','amount_type','coupon_count' 
	];

	public function saveCoupons($request){  
		date_default_timezone_set('Asia/Kolkata');
		$currentTime = date('Y-m-d H:i:s',time ()); 
		if($request['amount_type']=='price'){
			$amtprice=$request['price'];
			$amtdis=0.00;
		}
		else if($request['amount_type']=='discount'){
			$amtprice=0.00;
			$amtdis=$request['discount'];
		}    
		$startdate=date("Y-m-d", strtotime($request['start_date']));
		$enddate=date("Y-m-d", strtotime($request['end_date']));
 		if($request['coupon_id']==''){ 
			Coupon::create([
				'coupon_name'=>$request['coupon_name'],
				'coupon_code'=>$request['coupon_code'],  
				'amount_type'=>$request['amount_type'],
				'price'=>$amtprice,  
				'discount'=>$amtdis, 
				'start_date'=>$startdate,
				'end_date'=>$enddate,
				'status'=>$request['status'],
				'created_at'=>$currentTime, 
				'coupon_count'=>$request['coupon_count'],
			]);  
			 $status = 'Coupon Added Successfully';

		}else{ 
			Coupon::where('id', $request->coupon_id)->update([
				'coupon_name'=>$request['coupon_name'],
				'coupon_code'=>$request['coupon_code'], 
				'amount_type'=>$request['amount_type'],
				'price'=>$amtprice,  
				'discount'=>$amtdis,  
				'start_date'=>$startdate,
				'end_date'=>$enddate,
				'status'=>$request['status'],
				'updated_at'=>$currentTime,
				'coupon_count'=>$request['coupon_count'], 
			]); 
			 $status = 'Coupon Updated Successfully';

		} 
		 return $status;
	}

	public function addEditCouponValues($id){
		$couponvalue  =   Coupon::where('id',$id)->get();
		return $couponvalue;
	}
 	public function deletecoupon($id){
 		$coupon_applylist=Renewal_details::where('coupon_id',$id)->first(); 
       
        if(!$coupon_applylist)
        {  
	      	$coupon = Coupon::find($id);
	        $coupon->delete();
	        $status = 'Coupon Deleted Successfully';
	    }
	    else{ 
	    	$status='In this Coupon, user has been applied, so you can not able to delete!';
	    }
         return $status;	    
    }
    
    public function userRole() {
	    return $this->belongsTo(UserRole::class, 'subscription_plan_type','id');
	}


	public $sortable = ['id','coupon_name', 'subscription_plan_type','price','discount', 'coupon_code', 'start_date', 'end_date','status','amount_type'];



}
