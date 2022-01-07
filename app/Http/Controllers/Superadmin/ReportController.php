<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Superadmin\Enterprise;
use App\Models\Superadmin\Coupon;
use App\Models\Superadmin\RejectedEnterpriseUser;
use App\Models\Superadmin\Renewal_details;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use App\Mail\EnterpriseUserMail;
use Kyslik\ColumnSortable\Sortable;
use DB;
use App\Models\Superadmin\Subscription; 
use Mail;  
use Carbon\Carbon;
use App\Exports\LoginReportExport;  
use App\Exports\PaymentReportExport;  
use App\Models\Notification;
class ReportController extends Controller
{
    use Sortable;

	public function getLoginUser()
	{
		$teamlist['module'] = 'login report';  
		$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
		$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
		$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
		$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $login_status = (isset($_GET['login_status']) && $_GET['login_status'] != '') ? $_GET['login_status'] : '';     
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';  
        $export = (isset($_GET['export']) && $_GET['export'] != '') ? $_GET['export'] : '0';  

        
   
		 $users = User::with(['userRenewalDatail'=> function ($query) {
	            $query->where('is_activate','1')->where('status','1');
	            },'userDetail'])
			    ->when(request()->has('email') && request()->email,function($query){
                    $query->where('email','like', '%' . request()->email. '%'); 
                })->when(request()->has('user_name') && request()->user_name,function($query){
                   $query->where('name','like', '%' . request()->user_name. '%');
                })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 2 ),function($query){
                    $query->where('status','like', '%' . request()->status. '%');
                })->when(request()->has('login_status') && (request()->login_status),function($query){
                    if(request()->login_status == 1){ 
                          $query->where('login_at','!=',null);
                    }elseif(request()->login_status == 2)
                    {   
                        $query->where('login_at',null);
                    }
                })->when($to_date && $end_date,function($q) use($to_date,$end_date)
                {
                       $todate = Carbon::createFromFormat('Y-m-d', $to_date)->startOfDay();
                       $enddate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
                       $q->whereBetween('login_at', [$todate, $enddate]); 
                });
                if($mobile != ''){                    
                    $users->whereHas('userDetail' , function($query) use ($mobile) {
                      $query->where('contact_no','like', '%' .$mobile. '%');
                    });
                }
                $users->whereHas('userRenewalDatail' , function($query){
                  $query->where('is_activate','1')->where('status','1');
                }); 
        if($export== 1){
            $users = $users->orderBy('created_at', 'desc')->get(); 
            return Excel::download(new LoginReportExport($users), 'loginExport.xlsx');
        }
		$users = $users->sortable()->orderBy('created_at', 'desc')->paginate(20); 
		 
         
        $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'desc')->paginate(20);
        // dd($plans);
		return view('super-admin.report.login-report',compact('users','plans'),['Module'=>$teamlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status);	
	}

	public function getStillNotLoginUser()
	{
		$teamlist['module'] = 'login not report'; 
		$user_details=UserRole::where('role','Team')->first(); 
		$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
		$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
		$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
		$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';

 
         
        $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'desc')->paginate(20);
        // dd($plans);
		$users = User::with('userDetail')->where('user_role_id',$user_details->id);
		 $users = User::with(['userRenewalDatail'=> function ($query) {
	            $query->where('is_activate','1')->where('status','1');
	            },'userDetail'])
			    ->when(request()->has('email') && request()->email,function($query){
                    $query->where('email','like', '%' . request()->email. '%'); 
                })->when(request()->has('user_name') && request()->user_name,function($query){
                   $query->where('name','like', '%' . request()->user_name. '%');
                })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 2 ),function($query){
                    $query->where('status','like', '%' . request()->status. '%');
                });
                if($mobile != ''){                    
                    $users->whereHas('userDetail' , function($query) use ($mobile) {
                      $query->where('contact_no','like', '%' .$mobile. '%');
                    });
                }
                $users->whereHas('userRenewalDatail' , function($query){
                  $query->where('is_activate','1')->where('status','1');
                }); 

		$users = $users->where('login_at',null)->sortable()->orderBy('created_at', 'desc')->paginate(20); 
		
		return view('super-admin.report.not-login-report',compact('users','plans'),['Module'=>$teamlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status);	
	}
    public function payment(Request $request)
    {
        $module['module'] = 'report-payment';
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        // $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : '';
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
        $exportpayment = (isset($_GET['exportpayment']) && $_GET['exportpayment'] != '') ? $_GET['exportpayment'] : '0';  
        $nid = (isset($_GET['nid']) && $_GET['nid'] != '') ? $_GET['nid'] : '';
        if($nid != ''){
            Notification::where('id',$nid)->where('status',0)->update(['status'=>'1']);
        } 
        $userids=[];
        $userids =  User::where('parent_id',0)->where('status',1)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
        //print_r($userids);die;
        $payments = Renewal_details::whereIn('user_id',$userids)->with('user','user.userDetail','subscription','coupon')->where('status',1)
            ->when($from_date && $to_date,function($q) use($from_date,$to_date)
            {
                   $fromdate = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
                   $enddate = Carbon::createFromFormat('Y-m-d', $to_date)->endOfDay();
              $q->where('created_at', '>=', $fromdate)->where('created_at', '<=', $enddate);
            }); 
            if($user_name!=''){
                $payments->whereHas('user',function($q) use($user_name)
                { 
                    $q->where('name','like', '%' . $user_name. '%');
                });
            }
            if($mobile!=''){
                $payments->whereHas('user.userDetail',function($q) use($mobile)
                { 
                    $q->where('contact_no','like', '%' . $mobile. '%');
                });
            } 
              if($exportpayment== 1){
                    $payments = $payments->latest()->get();   
                    return Excel::download(new PaymentReportExport($payments), 'PaymentExport.xlsx');
                }
        $payments=$payments->sortable()->latest()->paginate(20);  

        return view('super-admin.report.payment',compact('payments'),['Module'=>$module]);
    }

    public function loginExport() 
    {
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $login_status = (isset($_GET['login_status']) && $_GET['login_status'] != '') ? $_GET['login_status'] : '';     
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';   
   
       //dd($login_status); die;

        $users = User::with(['userRenewalDatail'=> function ($query) {
                $query->where('is_activate','1')->where('status','1');
                },'userDetail'])
                ->when(request()->has('email') && request()->email,function($query){
                    $query->where('email','like', '%' . request()->email. '%'); 
                })->when(request()->has('user_name') && request()->user_name,function($query){
                   $query->where('name','like', '%' . request()->user_name. '%');
                })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 2 ),function($query){
                    $query->where('status','like', '%' . request()->status. '%');
                })->when(request()->has('login_status') && (request()->login_status),function($query){
                    if(request()->login_status == 1){ 
                          $query->where('login_at','!=',null);
                    }elseif(request()->login_status == 2)
                    {   
                        $query->where('login_at',null);
                    }
                })->when($to_date && $end_date,function($q) use($to_date,$end_date)
                {
                       $todate = Carbon::createFromFormat('Y-m-d', $to_date)->startOfDay();
                       $enddate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
                       $q->whereBetween('login_at', [$todate, $enddate]); 
                });
                if($mobile != ''){                    
                    $users->whereHas('userDetail' , function($query) use ($mobile) {
                      $query->where('contact_no','like', '%' .$mobile. '%');
                    });
                }
                $users->whereHas('userRenewalDatail' , function($query){
                  $query->where('is_activate','1')->where('status','1');
                }); 

        $users = $users->orderBy('created_at', 'desc')->get();        
        
        return Excel::download(new LoginReportExport($users), 'loginExport.xlsx');

    }
    public function paymentExport() 
    {
         $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        // $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : '';
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
        $userids=[];
        $userids =  User::where('parent_id',0)->where('status',1)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
        //print_r($userids);die;
        $payments = Renewal_details::whereIn('user_id',$userids)->with('user','user.userDetail','subscription','coupon')->where('status',1)
            ->when($from_date && $to_date,function($q) use($from_date,$to_date)
            {
                   $fromdate = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
                   $enddate = Carbon::createFromFormat('Y-m-d', $to_date)->endOfDay();
              $q->where('created_at', '>=', $fromdate)->where('created_at', '<=', $enddate);
            }); 
            if($user_name!=''){
                $payments->whereHas('user',function($q) use($user_name)
                { 
                    $q->where('name','like', '%' . $user_name. '%');
                });
            }
            if($mobile!=''){
                $payments->whereHas('user.userDetail',function($q) use($mobile)
                { 
                    $q->where('contact_no','like', '%' . $mobile. '%');
                });
            } 

        $payments = $payments->orderBy('created_at', 'desc')->get();        
        
        return Excel::download(new PaymentReportExport($payments), 'PaymentExport.xlsx');

    }
     
}
