<?php

namespace App\Http\Controllers\Superadmin;

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
use Mail;  
use Carbon\Carbon;
use App\Models\Notification;
class EnterpriseUserRequestController extends Controller
{
    
    public function enterpriseUserRqtList(){
        $planlist = Subscription::where('user_role_id',4)->where('display_in_site',1)->where('status',1)->latest()->get();
        $userlist['module'] = 'Enterpriseuserrequest';
        $no_of_entries = request()->no_of_entries ? request()->no_of_entries : 1;

        $user_details=UserRole::where('role','Enterpriser')->first(); 
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $is_activate='1';
        $nid = (isset($_GET['nid']) && $_GET['nid'] != '') ? $_GET['nid'] : '';
        if($nid != ''){
            Notification::where('id',$nid)->where('status',0)->update(['status'=>'1']);
        }
       
        $users =User::with('userDetail','userRenewalDatail')->when(request()->has('email') && request()->email,function($query){
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
        $users = $users->where('user_role_id',$user_details->id)->where('is_approved','!=',1)->sortable()->orderBy('created_at', 'desc')->paginate(20);

        $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('users.user_role_id',$user_details->id)->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'ASC')->paginate(20);
                 
           //     $plans=Renewal_details::join('super_admin_subscription_plan as sp','renewal_details.plan_id','sp.id')->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.status',1)->select('sp.plan_name','renewal_details.*')->orderBy('renewal_details.user_id', 'desc')->paginate(20);



        return view('super-admin.enterprise-req-user.enterprise-req-list',compact('users','plans','planlist'),['Module'=>$userlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status);

        // return view('super-admin.enterpriser.index',['Module'=>$enterpriser]);
    }

    public function activateEnterpriseUser(Request $request,$id)
    { 
        $users=User::find($id);
        $renewalDetail = Renewal_details::where('user_id',$id)->where('plan_id',0)->where('is_activate',1)->where('status',1)->first();
        if ($users!='' && $renewalDetail) {
            $users->plan_id    =  $request['plan_id'];    
            $users->is_approved    =  '1';   
            $users->team_count    =  $request['team_count'];       
            $users->save(); 
            $subscriptions = Subscription::where('id', $request['plan_id'])->first();
            if($subscriptions->payment_type=='yearly'){
                $renewaldate =  date('Y-m-d', strtotime("365 days +1 day"));
            }
            else if($subscriptions->payment_type=='monthly') $renewaldate =  date('Y-m-d', strtotime("30 days +1 day"));
            
            
            $update = Renewal_details::find($renewalDetail->id);
            $update->plan_id         =   $request['plan_id']; 
            $update->renewal_date    =   $renewaldate;  
            $update->amount          =   $subscriptions->amount;
            $update->save();  
            if($request['team_count']!='' && $request['plan_id']!=''){  
                $emailcontent['username']   =   $users->name;
                $emailcontent['email']      =   $users->email;
                $emailcontent['team_count']      =   $request['team_count'];
                $emailcontent['password']   =   "";
                $emailcontent['reason']   =   "";
                $emailcontent['renewal_date']      =   $renewaldate;
                $emailcontent['subscriptionplan']   = $subscriptions->plan_name;
                $emailcontent['status']   =   "1";          
                \Mail::to($emailcontent['email'])->send(new EnterpriseUserMail($emailcontent));
            }  
            $status="Enterprise User Approved Successfully";
            
        }else{
            $status="Enterprise User not Available";
        }
        return redirect('enterprise-request')->with('status',$status);  
    }
    
    public function deactivateEnterpriseUser($id)
    { 
        $users=User::find($id);
        if ($users!='') {
            $users->is_approved    =  '0'; 

            $users->save(); 
        $status="Enterprise User Deactivate Successfully";
        }else{
              $status="Enterprise User not Available";
        }
        return redirect('enterprise-request')->with('status',$status);     
    }

    public function rejectEnterpriseUser(Request $request,$id)
    {  
        $users=User::find($id);  
        $subscriptions = Subscription::where('id', $users->plan_id)->first(); 
        $renewal_list = Renewal_details::where('user_id',$id)->first();  
        $rejectedUser=new RejectedEnterpriseUser();
        $rejectedUser->user_id=$id;
        $rejectedUser->name=$users->name;
        $rejectedUser->email=$users->email;
        $rejectedUser->plan_id=$renewal_list->plan_id;     
        $rejectedUser->team_count=$users->team_count;
        $rejectedUser->contact_no=$users->userDetail->contact_no; 
        $rejectedUser->organization_name=$users->userDetail->organization_name; 
        $rejectedUser->address=$users->userDetail->address; 
        $rejectedUser->city=$users->userDetail->city; 
        $rejectedUser->province=$users->userDetail->province; 
        $rejectedUser->postal_code=$users->userDetail->postal_code; 
        $rejectedUser->reason=$request['reason'];
        $rejectedUser->save(); 
        $db_user_planid = $rejectedUser->plan_id; 
         if($request['reason']!=''){  
                $emailcontent['username']   =   $users->name;
                $emailcontent['email']      =   $users->email;
                $emailcontent['reason']      =   $request['reason'];
                $emailcontent['team_count']   =   "";
                $emailcontent['password']   =   ""; 
                $emailcontent['status']   =   "2";        
                \Mail::to($emailcontent['email'])->send(new EnterpriseUserMail($emailcontent));
            }  

        if ($users!='') {
            DB::delete('DELETE FROM users WHERE id = ?', [$id]); 
            DB::delete('DELETE FROM user_details WHERE user_id = ?', [$id]); 
            $status="Enterprise User Rejected Successfully";
        }else{
            $status="Enterprise User not Available";
        }
        return redirect('enterprise-request')->with('status',$status);      
    }

    public function rejectEnterpriseUserRqtList(){
        $userlist['module'] = 'Enterpriseuserrequest';
        $no_of_entries = request()->no_of_entries ? request()->no_of_entries : 1;

        $user_details=UserRole::where('role','Enterpriser')->first(); 
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : ''; 
        $is_activate='1';
         
        // $users= RejectedEnterpriseUser::join('user_details','rejected_enterprise_users.user_id','user_details.user_id')                   
        //             ->join('renewal_details','rejected_enterprise_users.user_id','renewal_details.user_id')  
        //             ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
        //              ->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.status',1)                  
        //             ->select('rejected_enterprise_users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;
        //             if($user_name!=''){
        //                 $users->where('rejected_enterprise_users.name','like', '%' . request()->user_name. '%');
        //             }
        //             if($email!=''){
        //                 $users->where('rejected_enterprise_users.email','like', '%' . request()->email. '%');
        //             }
        //             if($mobile!=''){
        //                 $users->where('user_details.contact_no','like', '%' . request()->mobile. '%');
        //             }

        //         $users=$users->sortable()->orderBy('renewal_details.created_at', 'ASC')->paginate(20);

        $users =RejectedEnterpriseUser::with('userDetail','userRenewalDatail')->when(request()->has('email') && request()->email,function($query){
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
                // $users->whereHas('userRenewalDatail' , function($query){
                //   $query->where('is_activate','1')->where('status','1');
                // });
        $users = $users->sortable()->orderBy('created_at', 'desc')->paginate(20);

        $plans = RejectedEnterpriseUser::join('user_details','rejected_enterprise_users.user_id','user_details.user_id')                   
                    ->join('renewal_details','rejected_enterprise_users.user_id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.status',1)                  
                    ->select('rejected_enterprise_users.*','renewal_details.renewal_date','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'ASC')->paginate(20); 


        return view('super-admin.enterprise-req-user.reject-enterpriseuser-list',compact('users','plans'),['Module'=>$userlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile);

        // return view('super-admin.enterpriser.index',['Module'=>$enterpriser]);
    }

}