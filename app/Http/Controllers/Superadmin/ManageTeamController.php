<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\FlowchartProject;
use App\Models\UserRole;
use App\Models\StripRenwalRecords;
use App\Models\DeletedUser;
use App\Models\Notification;
use App\Models\Note; 
use App\Models\UserTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ManageTeamUserRequest;
use App\Models\Superadmin\Subscription;
use App\Models\Superadmin\Renewal_details;
use Mail;
use App\Mail\Userregistermail; 
use App\Mail\UserEditMail;
use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Http\Traits\UserUpgrade;
class ManageTeamController extends Controller
{
	
    use Sortable;
    use UserUpgrade;
	public function getTeamUser()
	{
		$teamlist['module'] = 'team-user'; 
		$user_details=UserRole::where('role','Team')->first(); 
		$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
		$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
		$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
		$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';

		// $users = User::with('userDetail')->where('user_role_id',$user_details->id);
		 $users = User::with(['userRenewalDatail'=> function ($query) {
            $query->where('is_activate','1')->where('status','1');
            },'userDetail'])->where('user_role_id',$user_details->id)->when(request()->has('email') && request()->email,function($query){
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

		$users = $users->sortable()->orderBy('created_at', 'desc')->paginate(20); 
        
        $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('users.user_role_id',$user_details->id)->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'ASC')->paginate(20);
        // dd($users);
		return view('super-admin.team.team-list',compact('users','plans'),['Module'=>$teamlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status);	
	}

	public function createTeamUser(Request $request, $id = null){
		$teamUserAdd['module'] = 'team-user';
		$user = $id ? $user = User::find($id) : [];
		$user_details=UserRole::where('role','Team')->first();  
		$subscriptions = Subscription::where('display_in_site',1)->where('status',1)->get();  
		if($id)
		{
			$userDetail = UserDetail::where('user_id', $id)->get();
		}
		else 
		{
			$userDetail=[];
		}
        //dd($userDetail);
		return view('super-admin.team.team-create',['user'=>$user,'userDetail'=>$userDetail,'Module'=>$teamUserAdd,'subscriptions'=>$subscriptions]);
	}
	public function storeTeamUser(ManageTeamUserRequest $request,$id = null){
        $userExist =0;  
        $chkuser =1;      
        $chkMobile = UserDetail::where('contact_no',$request->contact_no)->first();
        if($id){
            $getuser = User::with('userDetail')->find($id);
            if($getuser['userDetail']['contact_no'] == $request->contact_no){
                $chkuser =0;
            }
        }
        if($chkMobile && $chkuser == 1){ 
            $chk = User::where('id',$chkMobile->user_id)->first(); 
            if($chk){
                if($chk['parent_id'] != 0){
                    return redirect('create/team-user/'.$id)->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
                }
                $chkRenewal = Renewal_details::where('user_id',$chk->id)->where('is_activate','!=','0')->where('status','!=','0')->get();
                if(count($chkRenewal) > 0){
                    return redirect('create/team-user/'.$id)->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
                }
            }
        }
        $subscriptions = Subscription::where('id', $request->subscription_plan)->first(); 

    	if($id){
			$userInsert = User::find($id);
			$db_user_role = $userInsert->user_role_id;
            $db_user_planid = $userInsert->plan_id;
		}
		else{
            $chkEmail = User::where('email',$request->email)->first();
            if($chkEmail){
                $userExist =1;
                $userInsert = User::find($chkEmail->id);
                $db_user_role = $chkEmail->user_role_id;
                $db_user_planid = $chkEmail->plan_id;
            }else{
			 $userInsert = new User();
            }
		}
		$userInsert->name = $request->full_name;
		$userInsert->email = $request->email;
		$userInsert->plan_id = $subscriptions->id;
		if($request->password !=''){
			$userInsert->password = Hash::make($request->password);
		}
		$userInsert->user_role_id = $subscriptions->user_role_id;
		if($id){
			$userInsert->status = $request->status;
		}else{
			$userInsert->status = '1';
		}
		$userInsert->save();
		if($id){
            $user_id= $id;
        }else{
            $user_id= $userInsert->id;
        }
        $user = User::find($user_id); 
        if($id =='' && $id==null){
            $emailcontent['username']   =   $userInsert->name;
            $emailcontent['email']      =   $userInsert->email;
            $emailcontent['password']   =   $request->password;
            $userrole = $user['userRole']['role'];
            $emailcontent['subscription'] =   $subscriptions;
            $emailcontent['userrole']   =   $userrole;
            \Mail::to($emailcontent['email'])->send(new Userregistermail($emailcontent));
        } 
        if($id!=''){ 
            if( $db_user_planid != $request->subscription_plan ||  $request->password != null ){ 
                $emailcontent['username']   =   $userInsert->name;
                $emailcontent['email']      =   $userInsert->email;
                $emailcontent['password']   =   $request->password;
                $emailcontent['subscriptionplan']   = $subscriptions->plan_name;   
                \Mail::to($emailcontent['email'])->send(new UserEditMail($emailcontent));
            }   
        } 
		
		// renewal details updation	 
		$userRenewal = Renewal_details::where('user_id',$user_id)->latest()->first();
		if(!$userRenewal){
            $currentDate = date('Y-m-d');
        }else{
            if($request->change_plan == '1'){
                $currentDate = date('Y-m-d');
                $fcUpdate=$this->subUserUpgrade($user_id,$subscriptions->user_role_id,$db_user_role);
            }else{
                $currentDate = date('Y-m-d',strtotime($user->created_at));
            }
        }	
		if($subscriptions->payment_type=='yearly'){
            $yearly         = date("Y-m-d",strtotime ( '+1 year' , strtotime ( $currentDate ) )) ;
            $renewaldate    = date('Y-m-d', strtotime($yearly . ' +1 day'));
        } 
        else if($subscriptions->payment_type=='monthly'){
            $monthly = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $currentDate ) )) ;
            $renewaldate    = date('Y-m-d', strtotime($monthly . ' +1 day'));
        }
        else{
            $renewaldate =  date('Y-m-d', strtotime($currentDate. ' + '.$subscriptions->activation_period.' days'));
        }
        if(!$userRenewal){
            $renewalDetail = new Renewal_details();
            $renewalDetail->user_id         =   $user_id;
            if($subscriptions->user_role_id != '2'){
                $renewalDetail->amount      =   $subscriptions->amount;
            }else{
                $renewalDetail->amount      = 0;
            }
            $renewalDetail->plan_id         =   $request->subscription_plan; 
            $renewalDetail->renewal_date    =   $renewaldate;
            $renewalDetail->is_activate    =   '1'; 
            $renewalDetail->status         =   '1';        
            $renewalDetail->save();
        }else{//to check user role
            //if($db_user_role != $subscriptions->user_role_id){
            if($request->change_plan == '1'){
                $deactivateUserRenewal = Renewal_details::where('user_id',$user_id)->get();
                foreach ($deactivateUserRenewal as $value) {
                    $update = Renewal_details::find($value->id);
                    $update->is_activate    =   '0'; 
                    $update->save();
                }
                $renewalDetail = new Renewal_details();
                $renewalDetail->user_id         =   $user_id;
                if($subscriptions->user_role_id != '2'){
                    $renewalDetail->amount      =   $subscriptions->amount;
                }else{
                    $renewalDetail->amount      = 0;
                }
                $renewalDetail->plan_id         =   $request->subscription_plan; 
                $renewalDetail->renewal_date    =   $renewaldate;
                $renewalDetail->is_activate    =   '1'; 
                $renewalDetail->status         =   '1';        
                $renewalDetail->save();
            }else{             
                if($db_user_planid != $request->subscription_plan){
                    $update = Renewal_details::find($userRenewal->id);
                    if($subscriptions->user_role_id != '2'){
                        $update->amount          =   $subscriptions->amount;
                    }else{
                        $update->amount      = 0;
                    }
                    $update->plan_id         =   $request->subscription_plan; 
                    $update->renewal_date    =   $renewaldate; 
                    $update->is_activate    =   '1'; 
                    $update->status         =   '1';           
                    $update->save();
                }        
            }
        }
		if($id){
			$getDetail = UserDetail::where('user_id',$id)->first();
			$userDetail = UserDetail::find($getDetail->id);
		}
		else{
            if($userExist == 0){
    			$userDetail = new UserDetail();
    			$userDetail->user_id = $user_id;
            }else{
                $userExistDetail = UserDetail::where('user_id',$user_id)->first();
                $userDetail = UserDetail::find($userExistDetail->id);
            }
		}
		$userDetail->contact_no = $request->contact_no;
		$userDetail->organization_name = $request->organization_name;
		$userDetail->address = $request->address;
		$userDetail->province = $request->province;
		$userDetail->city = $request->city;     
		$userDetail->postal_code = $request->pincode;        
		$userDetail->save();  

       
        if($subscriptions->user_role_id=="1"){
           if($id){                
                return redirect('team-users')->with('status','Team User Updated Successfully');
            }
            else{                
                return redirect('team-users')->with('status','Team User Added Successfully');            
            }
        }elseif($subscriptions->user_role_id=="2"){ 
           if($id){                
                return redirect('trial-users')->with('status','Trial User Updated Successfully');
            }
            else{                
                return redirect('trial-users')->with('status','Trial User Added Successfully');            
            }
         }elseif($subscriptions->user_role_id=="3"){ 
           if($id){                
                return redirect('individualuser')->with('status','Individualuser User Updated Successfully');
            }
            else{                
                return redirect('individualuser')->with('status','Individualuser User Added Successfully');            
            }
         }elseif($subscriptions->user_role_id=="4"){ 
           if($id){                
                return redirect('enterpriseuser')->with('status','Enterprise User Updated Successfully');
            }
            else{                
                return redirect('enterpriseuser')->with('status','Enterprise User Added Successfully');            
            }
        }
 
	}
	
	 public function deleteTeamUser($id){
             $getTeamChilds=[];
            $temp_id=[];
            $getChilds=[];
            $proIds=[];
            $proIds2=[];
            $getTeams=[];
            $getNotify=[];
            $getstriprenewal=[];
            $getTemplates=[];
        if($id){
            $proIds = FlowchartProject::where('created_by',$id)->pluck('id')->toArray(); 
            $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();
            if (count($getTemplates) > 0) {
                 foreach ($getTemplates as $key => $temp) { 
                    $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
                 }
            }  
                   
           $getNotify = Notification::where('from_id',$id)->whereIn('type',['7','8','9'])->get();    
           if (count($getNotify) > 0) {
                  foreach ($getNotify as $key => $notify) { 
                       DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
                  }
            } 
             $getstriprenewal = StripRenwalRecords::where('user_id',$id)->get();
              
            if (count($getstriprenewal) > 0) {
                 foreach ($getstriprenewal as $key => $striprecord) { 
                     DB::delete('DELETE FROM strip_renwal_records WHERE id = ?', [$striprecord->id]); 
                 }
            } 
            $teamuser=User::find($id); 
            $renewal_list = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();  
            $deleteduser=new DeletedUser();
            $deleteduser->user_id=$id;
            $deleteduser->name=$teamuser->name;
            $deleteduser->email=$teamuser->email;        
            $deleteduser->contact_no=$teamuser->userDetail->contact_no; 
            $deleteduser->organization_name=$teamuser->userDetail->organization_name;     
            $deleteduser->user_role_id=$teamuser->user_role_id;     
            $deleteduser->parent_id=$teamuser->parent_id;     
            $deleteduser->is_approved=$teamuser->is_approved;  
            $deleteduser->location_id  =$teamuser->location_id;  
            $deleteduser->team_count=$teamuser->team_count;
            $deleteduser->plan_id=$teamuser->plan_id;             
            $deleteduser->address=$teamuser->userDetail->address; 
            $deleteduser->city=$teamuser->userDetail->city; 
            $deleteduser->province=$teamuser->userDetail->province; 
            $deleteduser->postal_code=$teamuser->userDetail->postal_code;  
            $deleteduser->save();

         $teamusers = User::where('parent_id',$id)->get();
        if (count($teamusers) > 0) {
             foreach ($teamusers as $key => $users) {    
                $deleted_user=new DeletedUser();
                $deleted_user->user_id=$users->id;
                $deleted_user->name=$users->name;
                $deleted_user->email=$users->email;        
                $deleted_user->contact_no=$users->userDetail->contact_no;   
                $deleted_user->user_role_id=$users->user_role_id;     
                $deleted_user->parent_id=$users->parent_id;     
                $deleted_user->is_approved=$users->is_approved;  
                $deleted_user->location_id  =$users->location_id;  
                $deleted_user->organization_name=''; 
                $deleted_user->team_count=$users->team_count;
                $deleted_user->plan_id=$users->plan_id;             
                $deleted_user->address=$users->userDetail->address; 
                $deleted_user->city=$users->userDetail->city; 
                $deleted_user->province=$users->userDetail->province; 
                $deleted_user->postal_code=$users->userDetail->postal_code;  
                $deleted_user->save();

                if($users->id){ 
                    DB::delete('DELETE FROM users WHERE id = ?', [$users->id]); 
                    DB::delete('DELETE FROM user_details WHERE user_id = ?', [$users->id]); 
                    $usertempnote =Note::where('user_id',$users->id)->get();
                    if (count($usertempnote) > 0) {
                       foreach ($usertempnote as $key => $tempnote) {
                            DB::delete('DELETE FROM notes WHERE id = ?', [$tempnote->id]);                   
                         } 
                    }    
                }
             } 
        }
    }
        if($id){ 
            DB::delete('DELETE FROM users WHERE id = ?', [$id]); 
            DB::delete('DELETE FROM user_details WHERE user_id = ?', [$id]); 
            $usertempnote =Note::where('user_id',$id)->get();
            if (count($usertempnote) > 0) {
                foreach ($usertempnote as $key => $tempnote) {
                    DB::delete('DELETE FROM notes WHERE id = ?', [$tempnote->id]);                   
                }   
            }  
            $status="Team User Deleted Successfully";
        }
        return redirect('team-users')->with('status',$status);  

    }

}
