<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\User;
use App\Models\UserRole;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\Controller;
use App\Models\Superadmin\Individualuser;
use App\Models\Superadmin\Renewal_details;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ManageIndividualUserRequest;

class IndividualuserController extends Controller{
    
     use Sortable;

    protected $individualuser; 
    public function __construct()
    {
       $this->individualuser = new Individualuser();
    }

    public function index(){
        // $individual['module']   = 'Individualuser';
        // $userlist   =   $this->individualuser->Gettinguserlist();
        // $individual['userlist']     =  $userlist['userlist'];
        // $individual['filtervalues'] =  $userlist['filtervalue'];
         $userlist['module'] = 'Individualuser';
        $no_of_entries = request()->no_of_entries ? request()->no_of_entries : 10;

         $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 

      $user_details=UserRole::where('role','Individual')->first(); 
       
         $users = User::with(['userRenewalDatail'=> function ($query) {
            $query->where('is_activate','1')->where('status','1');
            },'userDetail'])->sortable()->when(request()->has('email') && request()->email,function($query){
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

        $users = $users->where('user_role_id',$user_details->id)->orderBy('created_at', 'desc')->paginate(20);

        $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('users.user_role_id',$user_details->id)->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'ASC')->paginate(20);
 
        return view('super-admin.individualuser.index',compact('users','plans'),['Module'=>$userlist])->with('email', $email)->with('user_name',$user_name)->with('mobile',$mobile)->with('status',$status);
        
        // return view('super-admin.individualuser.index',['Module'=>$individual]);
    }

    public function edituser($id=""){
        $individualedit['module']                      =   'Individualuser';
        $individualedit['plans']                       =   $this->individualuser->GettingIndividualplans();
        if($id!='') $individualedit['editpagevalues']  =   $this->individualuser->Gettingeditvalues($id);
        return view('super-admin.individualuser.edit',['Module'=>$individualedit]);
    }

    public function saveindividualuser(ManageIndividualUserRequest $request){
        $returnstatus = $this->individualuser->saveindividualuser($request);
        if($returnstatus == 'contact no err'){
            return redirect('create-individualuer/'.$request['user_id'])->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
        }
          if($returnstatus['roleid']=="1"){ 
            return redirect('team-users')->with('status', $returnstatus['status']); 
         }elseif($returnstatus['roleid']=="2"){  
            return redirect('trial-users')->with('status', $returnstatus['status']); 
         }elseif($returnstatus['roleid']=="3"){               
            return redirect('individualuser')->with('status', $returnstatus['status']);   
         }elseif($returnstatus['roleid']=="4"){            
            return redirect('enterpriseuser')->with('status', $returnstatus['status']);  
        } 
      
    }

    public function deleteuser($id){
        $this->individualuser->deleteuser($id);
        $url =  url('/individualuser');
        return redirect($url)->with('status','Individual User Deleted Successfully');
    }
}


?>