<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Notification;
use App\Models\UserTemplate;
use App\Models\Note;
use App\Models\UserDetail;
use App\Models\DeletedUser;
use App\Models\FlowchartProject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Kyslik\ColumnSortable\Sortable;

use App\Http\Controllers\Controller;
use App\Models\Superadmin\Enterprise;
use App\Models\Superadmin\Renewal_details;
use DB;
use Illuminate\Foundation\Http\FormRequest;  
use App\Http\Requests\ManageEnterpriseUserRequest;

class EnterpriseController extends Controller{

     use Sortable;

    protected $enterpriser;
    public function __construct()
    {
       $this->enterpriser = new Enterprise();
    }

    public function index(){
        // $enterpriser['module']          = 'Enterpriseuser';
        // $enterprise =   $this->enterpriser->enterpriserlst();
        // $enterpriser['listpagevalues']  =  $enterprise['userlist'];
        // $enterpriser['filtervalues']    =  $enterprise['filtervalue'];
         $userlist['module'] = 'Enterpriseuser';
        $no_of_entries = request()->no_of_entries ? request()->no_of_entries : 1;

      $user_details=UserRole::where('role','Enterpriser')->first(); 
      $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
              
         $users = User::with(['userRenewalDatail'=> function ($query) {
            $query->where('is_activate','1')->where('status','1');
            },'userDetail'])->when(request()->has('email') && request()->email,function($query){
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
        $users = $users->where('user_role_id',$user_details->id)->where('is_approved',1)->sortable()->orderBy('created_at', 'desc')->paginate(20);
          $plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('users.user_role_id',$user_details->id)->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.updated_at as renewal_updated_at','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

                $plans=$plans->orderBy('renewal_details.created_at', 'ASC')->paginate(20);
                 
        return view('super-admin.enterpriser.index',compact('users','plans'),['Module'=>$userlist])->with('user_name', $user_name)->with('email', $email)->with('mobile', $mobile)->with('status', $status);

        // return view('super-admin.enterpriser.index',['Module'=>$enterpriser]);
    }

    public function editenterpriseuser($id=""){
        $enterpriser['module']                  = 'Enterpriseuser';
        $enterpriser['plans']                   =  $this->enterpriser->GettingEnterpriseplans();
        if($id!='') $enterpriser['editvalues']  =  $this->enterpriser->editenterpriser($id);  
        return view('super-admin.enterpriser.edit',['Module'=>$enterpriser]);
    }

    public function saveenterprise(ManageEnterpriseUserRequest $request){
      
        $returnstatus = $this->enterpriser->saveenterprise($request);
        if($returnstatus == 'contact no err'){
            return redirect('add-enterprise-user/'.$request['user_id'])->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
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

    public function deleteenterpriser($id){
        $getTeamChilds=[];
        $getChilds=[];
        $proIds=[];
        $proIds2=[];
        $getTeams=[];
        $getNotify=[];
        $getTemplates=[];
        $temp_id=[];
        $proIds = FlowchartProject::where('created_by',$id)->pluck('id')->toArray();
        $getTeams = User::where('parent_id',$id)->where('user_role_id',1)->pluck('id')->toArray();//getting team users id
        $proIds2 = FlowchartProject::whereIn('created_by',$getTeams)->pluck('id')->toArray();
        $fcIds = array_merge($proIds2,$proIds);
        $getTemplates = UserTemplate::whereIn('project_id',$fcIds)->get();
        
        $getNotify = Notification::where('from_id',$id)->whereIn('type',['7','8','9'])->get();
        if (count($getTemplates) > 0) {
             foreach ($getTemplates as $key => $temp) {
                 $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
             }
        } 
        if (count($getNotify) > 0) {
             foreach ($getNotify as $key => $notify) { 
                  DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
             }
        } 

        $getTeamChilds = User::whereIn('parent_id',$getTeams)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
        $getChilds = array_merge($getTeamChilds,$getTeams);

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

         $teamusers = User::whereIn('id',$getChilds)->get(); 
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
                if($users->userDetail->organization_name){  
                    $deleted_user->organization_name=$users->userDetail->organization_name; 
                }else{
                    $deleted_user->organization_name=''; 
                }
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
        if($id){ 
            DB::delete('DELETE FROM users WHERE id = ?', [$id]); 
            DB::delete('DELETE FROM user_details WHERE user_id = ?', [$id]); 
            $usertempnote =Note::where('user_id',$id)->get(); 
            if (count($usertempnote) > 0) {
                 foreach ($usertempnote as $key => $tempnote) {
                    DB::delete('DELETE FROM notes WHERE id = ?', [$tempnote->id]);                   
                 }
            }  
            $status="Enterprises User Deleted Successfully";
        } 
        $url =  url('/enterpriseuser');
        return redirect($url)->with('status',$status);
    }
}


?>