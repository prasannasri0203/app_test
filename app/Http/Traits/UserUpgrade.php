<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Superadmin\Renewal_details;
use App\Models\UserTemplate;
use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Models\DegradedSubUser;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\UserRole;
use DB;
use Auth;
trait UserUpgrade
{
    public function subUserUpgrade($user_id,$role_id,$db_role_id){
        // dd($db_role_id);
        $getChilds=[];
        $getTeams=[];
        $getTemplates=[];
        $getTeamChilds=[];
        $proIds = FlowchartProject::where('created_by',$user_id)->pluck('id')->toArray();
        if($db_role_id == 2 ||  $db_role_id == 3){//trial or individual user's upgrading to enterpriser or team,moving active fc to account
            $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();
            if(count($getTemplates) > 0){
                foreach ($getTemplates as $template) {
                    // if($template->status == 1 )
                    // {
                        UserTemplate::where('user_id',$user_id)->where('id',$template->id)->update(['is_approved'=>1]);
                    // }
                }
            }
        }
        else if($db_role_id == 4 || $db_role_id == 1){ //enterpriser or team user's upgrading to enterpriser/team or individual
            if($db_role_id == 4){//enterpriser
                if($role_id != $db_role_id){   
                    $getTeams = User::where('parent_id',$user_id)->where('user_role_id',1)->pluck('id')->toArray();//getting team users id
                    $proIds2 = FlowchartProject::whereIn('created_by',$getTeams)->pluck('id')->toArray();
                    $fcIds = array_merge($proIds2,$proIds);
                    $getTemplates = UserTemplate::whereIn('project_id',$fcIds)->get();//update user id in template
                    if(count($getTeams) > 0){                   
                        $getTeamChilds = User::whereIn('parent_id',$getTeams)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
                        $getChilds = array_merge($getTeamChilds,$getTeams);
                    }
                }
            }else if($db_role_id == 1){//team
                if($role_id != $db_role_id){                
                    $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();//update user id in template
                    $getChilds = User::where('parent_id',$user_id)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();   
                }
            }   

            if(count($getTemplates) > 0){
                foreach ($getTemplates as $template) {                   
                    // if($template->is_approved == 1 && $template->status==1){
                        UserTemplate::where('id',$template->id)->update(['user_id'=>$user_id]); //moving active fc to account
                    // }
                }
            }
            //inactive the subusers            
            $this->inactiveSubUsers($getChilds);
        }
        
    }
    public function inactiveSubUsers($user_ids){
        if(count($user_ids) > 0){
            foreach ($user_ids as $child) {
                $userDetail = User::find($child);
                $user = new DegradedSubUser();
                $user->name = $userDetail->name;
                $user->email = $userDetail->email;
                $user->user_id = $userDetail->id;
                $user->parent_id = $userDetail->parent_id;
                $user->user_role_id = $userDetail->user_role_id;
                $user->save();
                $userDetail->forceDelete();
                $contact = UserDetail::where('user_id',$child)->delete();
            }
        }
    }
    public function userRegistration($user_id,$name,$role,$planname,$type,$amt)
    {
        $admins = Admin::where('status',1)->get();
        $roledet = UserRole::find($role);
        $msgtype=7;
        if($type == 1){//registration
            if($role == 2){//trial
                $msgtype=9;
            }
            if($role != 4){
                if($amt != 0){
                    $msg = ucfirst($name) .' has registered as a '.$roledet['role'].' user and paid CAD '.$amt.' for '.$planname.' plan';
                }else{
                    $msg = ucfirst($name) .' has registered as a '.$roledet['role'].' user and choosed '.$planname.' plan';
                }
                
            }else{//enterpriser
                $msg = ucfirst($name) .' has requested as a enterpriser';
                $msgtype=8;
            }
        }else if($type == 2){//upgrade
            if($role == 4){//enterpriser
                $msg = ucfirst($name) .' has upgraded as a enterpriser';
                $msgtype=8;
            }else{
                $msg = ucfirst($name) .' has upgraded as a '.$roledet['role'].' user and paid CAD '.$amt.' for '.$planname.' plan';
            }
        }else if($type == 3){//renewal
            if($role == 4){//enterpriser
                $msg = ucfirst($name) .' has renewed his/her enterpriser plan';
            }else{
                $msg = ucfirst($name) .' has renewed his/her '.$planname.' plan and paid CAD '.$amt;
            }
        }else if($type == 4){//same role,plan changed
            $msg = ucfirst($name) .' has updated his/her plan as '.$planname.' plan and paid CAD '.$amt;
        }
        foreach ($admins as $key => $admin) {
            $chkNotify = Notification::where('from_id',$user_id)->where('to_id',$admin->id)->where('message',$msg)->where('type',$msgtype)->first();
            if(!$chkNotify){
                $addnotificate = new Notification; 
                $addnotificate->from_id = $user_id;
                $addnotificate->to_id = $admin->id; 
                $addnotificate->type = $msgtype; 
                $addnotificate->message = $msg;
                $addnotificate->updated_by = 1;
                $addnotificate->save();  
            }
        }
    }
}