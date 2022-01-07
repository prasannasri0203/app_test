<?php

namespace App\Models\Superadmin;

use DB;
use Mail;
use App\Models\User;
use App\Mail\UserEditMail;
use App\Models\UserDetail;
use App\Models\DeletedUser;
use App\Models\StripRenwalRecords;
use App\Models\Notification;
use App\Models\UserTemplate;
use App\Models\Note;
use App\Mail\Userregistermail;
use Illuminate\Support\Facades\Hash;
use App\Models\Superadmin\Subscription; 
use Illuminate\Database\Eloquent\Model;
use App\Models\Superadmin\Renewal_details;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Traits\UserUpgrade;
class Individualuser extends Model
{
    use SoftDeletes;
    use UserUpgrade;
    public function saveindividualuser($request){
        $userExist =0;  
        $chkuser =1;  
        $db_user_planid =0; 
        $status=[];   
        $chkMobile = UserDetail::where('contact_no',$request->contact_no)->first();
        $id =$request['user_id'];
        if($request['user_id']!=''){
            $getuser = User::with('userDetail')->find($id);
            if($getuser['userDetail']['contact_no'] == $request->contact_no){
                $chkuser =0;
            }
        }
        if($chkMobile && $chkuser == 1){ 
            $chk = User::where('id',$chkMobile->user_id)->first(); 
            if($chk){
                if($chk['parent_id'] != 0){
                    return 'contact no err';
                }
                $chkRenewal = Renewal_details::where('user_id',$chk->id)->where('is_activate','!=','0')->where('status','!=','0')->get();
                if(count($chkRenewal) > 0){
                    return 'contact no err';
                }
            }
        }

        $chkEmail = User::where('email',$request->email)->first();
        if($chkEmail){
            $userExist =1;
            $request['user_id'] =$chkEmail->id;
            $db_user_role = $chkEmail->user_role_id;
            $db_user_planid = $chkEmail->plan_id;
        }

        //date_default_timezone_set('Asia/Kolkata');
		$currentTime = date('Y-m-d H:i:s',time ());
        $planDetail = Subscription::find($request->plan_type);
        $data['name']           =   $request['full_name'];
        $data['email']          =   $request['email'];
        $data['user_role_id']   =   $planDetail->user_role_id;
        if(isset($request['plan_type'])) $data['plan_id']        =   $request['plan_type'];

        if($request['user_id']==''){
            $data['status']         =   1;
            $data['created_at']     =   $currentTime;
            $data['password']       =   Hash::make($request['password']);
            $userid =   DB::table('users')->insertGetId($data);
           if(isset($request['plan_type'])) $this->Insertplandetails($request['plan_type'],$userid,0,2,$db_user_planid);
            
        }else{
            $userDetail = User::find($request['user_id']);
            $db_user_role = $userDetail->user_role_id;
            $db_user_planid = $userDetail->plan_id;
            if(isset($request['status']))
            {
                $data['status']         =   $request['status']; 
            }         
            // $data['status']         =   $request['status'];
            $data['updated_at']     =   $currentTime;
            if($request['password']!='') $data['password']  =   Hash::make($request['password']);
            DB::table('users')->where('id',$request['user_id'])->update($data);
            if(isset($request['plan_type'])) $this->Insertplandetails($request['plan_type'],$request['user_id'],$db_user_role,$request['change_plan'],$db_user_planid);
        }

        
        $data1['contact_no']            =   $request['contact_no'];
        $data1['organization_name']     =   $request['organization_name'];
        $data1['address']               =   $request['address'];
        $data1['city']                  =   $request['city'];
        $data1['province']              =   $request['province'];
        $data1['postal_code']           =   $request['pincode'];
        

        if($request['user_id']==''){
            $data1['user_id']           =   $userid;
            $data1['created_at']        =   $currentTime;
            DB::table('user_details')->insertGetId($data1);

              if($planDetail->user_role_id=='1'){
                $status['status'] =  'Team  User Added Suceessfully';
                $status['roleid']='1';
            }elseif($planDetail->user_role_id=='2'){
                $status['status'] = 'Trail User Added Suceessfully';
                $status['roleid']='2';
            }elseif($planDetail->user_role_id=='3'){
                $status['status']  = 'Individualuser User Added Suceessfully';
                $status['roleid']='3';
            }elseif($planDetail->user_role_id=='4'){
                $status['status'] = 'Enterpriser User Added Suceessfully';
                $status['roleid']='4';
            }
        }else{
            $data1['user_id']           =   $request['user_id'];
            $data1['updated_at']        =   $currentTime;
            DB::table('user_details')->where('user_id',$request['user_id'])->update($data1);

              if($planDetail->user_role_id=='1'){
                $status['status'] =  'Team  User Updated Suceessfully';
                $status['roleid']='1';
            }elseif($planDetail->user_role_id=='2'){
                $status['status'] = 'Trail User Updated Suceessfully';
                $status['roleid']='2';
            }elseif($planDetail->user_role_id=='3'){
                $status['status']  = 'Individualuser User Updated Suceessfully';
                $status['roleid']='3';
            }elseif($planDetail->user_role_id=='4'){
                $status['status'] = 'Enterpriser User Updated Suceessfully';
                $status['roleid']='4';
            }
        }
       if($request['user_id'] =='' && $request['user_id']==null){
            $emailcontent['username']   =   $data['name'];
            $emailcontent['email']      =   $data['email'];
            $emailcontent['password']   =   $request['password'];
            $emailcontent['userrole']   =   'Individual';
            $emailcontent['subscription'] =   $planDetail;
            \Mail::to($emailcontent['email'])->send(new Userregistermail($emailcontent));
        }
        if($request['user_id']!=''){ 
            if( $db_user_planid != $request->plan_type ||  $request->password != null ){ 
                $emailcontent['username']   =   $data['name'];
                $emailcontent['email']      =    $data['email'];
                $emailcontent['password']   =   $request['password'];
                $emailcontent['subscriptionplan']   = $planDetail->plan_name; 
                \Mail::to($emailcontent['email'])->send(new UserEditMail($emailcontent));
            }    
        }  
        return $status;
    }

    public function Gettinguserlist(){

        $username   =   '';
        $email      =   '';
        $mobile     =   '';
        $status     =   '';

        if(isset($_GET['user_name']) && $_GET['user_name']!='') $username = $_GET['user_name'];
        if(isset($_GET['email'])    &&  $_GET['email']!='') $email = $_GET['email'];
        if(isset($_GET['mobile'])   &&  $_GET['mobile']!='') $mobile = $_GET['mobile'];
        if(isset($_GET['status'])   &&  $_GET['status']!='0') $status = $_GET['status'];

        $roleid =   DB::table('user_roles')->select('id')->where('role','Individual')->get();
        
        $userlist   =   DB::table('users')->join('user_details','user_details.user_id','users.id')->select('users.id','users.name','users.email','user_details.contact_no','user_details.organization_name')->where('user_role_id',$roleid[0]->id);

        if($username!='') $userlist = $userlist->where('users.name','like','%'.$username.'%');
        if($email!='') $userlist = $userlist->where('users.email','like','%'.$email.'%');
        if($mobile!='') $userlist = $userlist->where('user_details.contact_no','like','%'.$mobile.'%');
        if($status!='') $userlist = $userlist->where('users.status',$status);

        $userlist = $userlist->paginate(20);

        $filtervalues['username']   =   $username;
        $filtervalues['email']      =   $email;
        $filtervalues['mobile']     =   $mobile;
        $filtervalues['status']     =   $status;

        $users['userlist']      =   $userlist;
        $users['filtervalue']   =   $filtervalues;

        return $users;
    }

    public function Gettingeditvalues($id){
        $editvalues =  DB::table('users')->join('user_details','user_details.user_id','users.id')->where('users.id',$id)->get();
        return $editvalues;
    }

    public function deleteuser($id){
         $users=User::find($id); 
        $renewal_list = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();  
        $deleted_user=new DeletedUser();
        $deleted_user->user_id=$id;
        $deleted_user->name=$users->name;
        $deleted_user->email=$users->email;        
        $deleted_user->contact_no=$users->userDetail->contact_no; 
        $deleted_user->organization_name=$users->userDetail->organization_name;     
        $deleted_user->user_role_id=$users->user_role_id;     
        $deleted_user->parent_id=$users->parent_id;     
        $deleted_user->is_approved=$users->is_approved;  
        $deleted_user->location_id  =$users->location_id;  
        $deleted_user->team_count=$users->team_count;
        $deleted_user->plan_id=$users->plan_id;             
        $deleted_user->address=$users->userDetail->address; 
        $deleted_user->city=$users->userDetail->city; 
        $deleted_user->province=$users->userDetail->province; 
        $deleted_user->postal_code=$users->userDetail->postal_code;  
        $deleted_user->save();  
        $usertempnote=[];
        $getstriprenewal=[];
        $usertemp=[];
        $temp_id=[];
        $getNotify=[];

         
        if($id){
              
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
            DB::delete('DELETE FROM users WHERE id = ?', [$id]); 
            DB::delete('DELETE FROM user_details WHERE user_id = ?', [$id]); 
            $usertempnote =Note::where('user_id',$id)->get();
            $usertemp =UserTemplate::where('user_id',$id)->get();
            if (count($usertempnote) > 0) {
                foreach ($usertempnote as $key => $tempnote) {
                            DB::delete('DELETE FROM notes WHERE id = ?', [$tempnote->id]);                   
                         }
            }  
            if (count($usertemp) > 0) {
                foreach ($usertemp as $key => $temp) {
                    $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
                 }               
            } 
            $status = 'User Deleted Suceessfully'; 
        } 
        return $status;
    }

    public function GettingIndividualplans(){
        $plans  =   DB::table('super_admin_subscription_plan')->where('display_in_site',1)->where('status','1')->get();
        return $plans;
    }

    public function Insertplandetails($planid,$userid,$dbRole,$planDet,$oldplanid){
        $planDetail     =   DB::table('super_admin_subscription_plan')->where('id',$planid)->first();
        if($planDetail){
            $userRenewal = Renewal_details::where('user_id',$userid)->latest()->first();
            $user = User::find($userid);
            if(!$userRenewal){
                $currentDate = date('Y-m-d');
            }else{
                if($planDet == '1'){
                    $currentDate = date('Y-m-d');
                    $fcUpdate=$this->subUserUpgrade($userid,$planDetail->user_role_id,$dbRole);
                }else{
                    $currentDate = date('Y-m-d',strtotime($user->created_at));
                }
            }
            if($planDetail->payment_type=='yearly'){
                $yearly         = date("Y-m-d",strtotime ( '+1 year' , strtotime ( $currentDate ) )) ;
                $renewaldate    = date('Y-m-d', strtotime($yearly . ' +1 day'));
            } 
            else if($planDetail->payment_type=='monthly'){
                $monthly = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $currentDate ) )) ;
                $renewaldate    = date('Y-m-d', strtotime($monthly . ' +1 day')); 
            } 
            else{
                $renewaldate =  date('Y-m-d', strtotime($currentDate. ' + '.$planDetail->activation_period.' days +1 day'));
            }

            if(!$userRenewal){
                $renewalDetail = new Renewal_details();
                $renewalDetail->user_id         =   $userid;
                if($planDetail->user_role_id != '2'){
                    $renewalDetail->amount      =   $planDetail->amount;
                }else{
                    $renewalDetail->amount      = 0;
                }
                $renewalDetail->plan_id         =   $planid; 
                $renewalDetail->renewal_date    =   $renewaldate;
                $renewalDetail->is_activate    =   '1'; 
                $renewalDetail->status         =   '1';        
                $renewalDetail->save();
            }else{//to check user role
                //if($dbRole != $planDetail->user_role_id){
                if($planDet == '1'){
                    $deactivateUserRenewal = Renewal_details::where('user_id',$userid)->get();
                    foreach ($deactivateUserRenewal as $value) {
                        $update = Renewal_details::find($value->id);
                        $update->is_activate    =   '0'; 
                        $update->save();
                    }
                    $renewalDetail = new Renewal_details();
                    $renewalDetail->user_id         =   $userid;
                    if($planDetail->user_role_id != '2'){
                        $renewalDetail->amount      =   $planDetail->amount;
                    }else{
                        $renewalDetail->amount      = 0;
                    }
                    $renewalDetail->plan_id         =   $planid; 
                    $renewalDetail->renewal_date    =   $renewaldate;
                    $renewalDetail->is_activate    =   '1'; 
                    $renewalDetail->status         =   '1';        
                    $renewalDetail->save();
                }else{             
                    if($oldplanid != $planid){
                        $update = Renewal_details::find($userRenewal->id);
                        if($planDetail->user_role_id != '2'){
                            $update->amount          =   $planDetail->amount;
                        }else{
                            $update->amount      = 0;
                        }
                        $update->plan_id         =   $planid; 
                        $update->renewal_date    =   $renewaldate;   
                        $update->is_activate    =   '1'; 
                        $update->status         =   '1';         
                        $update->save();
                    }
                }
            }
        }
    }

}


?>