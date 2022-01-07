<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Auth;
use Mail;  
use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserLoginReportExport;  

class ReportUserController extends Controller
{
     use Sortable;

	public function getLoginUser()
	{
		$teamlist['module'] = 'login report';  
		$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
		$location = (isset($_GET['location']) && $_GET['location'] != '') ? $_GET['location'] : '';
		$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
    $role = (isset($_GET['role']) && $_GET['role'] != '') ? $_GET['role'] : '';
		$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
		$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
		$manage_status = (isset($_GET['manage_status']) && $_GET['manage_status'] != '') ? $_GET['manage_status'] : '';
        $login_status = (isset($_GET['login_status']) && $_GET['login_status'] != '') ? $_GET['login_status'] : '';
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';   
  		$userIds = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->pluck('id')->toArray();

        $export = (isset($_GET['export']) && $_GET['export'] != '') ? $_GET['export'] : '0';  

		$users = User::with('userDetail','userRenewalDatail')->when(request()->has('email') && request()->email,function($query){
            $query->where('email','like', '%' . request()->email. '%'); 
        })->when(request()->has('user_name') && request()->user_name,function($query){
           $query->where('name','like', '%' . request()->user_name. '%');
        })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 2 ),function($query){
            $query->where('status', request()->status);
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
       
        if($location != ''){                    
            $users->whereHas('userDetail' , function($query) use ($location) {
              $query->where('address','like', '%' .$location. '%')->orWhere('city','like','%'.$location.'%')->orWhere('province','like','%'.$location.'%')->orWhere('postal_code','like','%'.$location.'%');
            });
        }
        if(Auth::user()->user_role_id ==4){
	        if($manage_status != ''){
		        if($manage_status == 1){                    
		             $users->where('parent_id',Auth::user()->id)->where('user_role_id',1); 
		        } elseif($manage_status == 2){                    
		             $users->whereIn('parent_id',$userIds); 
		        } 
	    	}else{
	    		$users->where('parent_id',Auth::user()->id)->where('user_role_id',1); 
	    	}
    	}
    	if(Auth::user()->user_role_id ==1){
    		$users->where('parent_id',Auth::user()->id); 
    	} 
      if($role != ''){                   
          $users->where('parent_id','!=','0')->where('user_role_id',$role); 
      }
      if($export== 1){
          $users = $users->get();   
          return Excel::download(new UserLoginReportExport($users), 'User Login Export.xlsx');
      }           
       
       $users = $users->sortable()->orderBy('id', 'desc')->paginate(10); 
        $roleList    = DB::table('team_user_roles')->get(); 
		  
		return view('frontend.user-report.login-user-report',compact('users','roleList'),['Module'=>$teamlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status)->with('login_status', $login_status)->with('manage_status', $manage_status);	
	}



    public function userloginExport($user_name,$email,$mobile,$to_date,$end_date,$login_status,$status,$manage_status) 
    { 
          $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
          $location = (isset($_GET['location']) && $_GET['location'] != '') ? $_GET['location'] : '';
          $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
          $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
          $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
          $manage_status = (isset($_GET['manage_status']) && $_GET['manage_status'] != '') ? $_GET['manage_status'] : '';
              $login_status = (isset($_GET['login_status']) && $_GET['login_status'] != '') ? $_GET['login_status'] : '';
              $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
              $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';   
            $userIds = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->pluck('id')->toArray(); 
          $users = User::with('userDetail','userRenewalDatail')->when(request()->has('email') && request()->email,function($query){
                  $query->where('email','like', '%' . request()->email. '%'); 
              })->when(request()->has('user_name') && request()->user_name,function($query){
                 $query->where('name','like', '%' . request()->user_name. '%');
              })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 0 ),function($query){
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
             
              if($location != ''){                    
                  $users->whereHas('userDetail' , function($query) use ($location) {
                    $query->where('address','like', '%' .$location. '%')->orWhere('city','like','%'.$location.'%')->orWhere('province','like','%'.$location.'%')->orWhere('postal_code','like','%'.$location.'%');
                  });
              }
              if(Auth::user()->user_role_id ==4){
                if($manage_status != ''){
                  if($manage_status == 1){                    
                       $users->where('parent_id',Auth::user()->id)->where('user_role_id',1); 
                  } elseif($manage_status == 2){                    
                       $users->whereIn('parent_id',$userIds); 
                  } 
              }else{
                $users->where('parent_id',Auth::user()->id)->where('user_role_id',1); 
              }
            }
            if(Auth::user()->user_role_id ==1){
              $users->where('parent_id',Auth::user()->id); 
            } 
               
        $users = $users->orderBy('created_at', 'desc')->get();        
        
        return Excel::download(new UserLoginReportExport($users), 'User Login Export.xlsx');

    }

}
