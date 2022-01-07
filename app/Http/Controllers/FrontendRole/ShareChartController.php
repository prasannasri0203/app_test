<?php

namespace App\Http\Controllers\FrontendRole;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Superadmin\ThemeSetting;
use Auth;
use DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserTemplate;
use App\Models\Notification;
use App\Models\UserTemplateTrack;
use App\Models\TemplateShare;
use App\Mail\FlowChartShareMail;
class ShareChartController extends Controller
{
    
    public function shareChart(Request $request){
        // dd($request->useremail);
        if(!empty($request->useremail)){
            if(is_array($request->useremail)){
                $users = $request->useremail;
            }else{
                $users = explode(',', $request->useremail);
            }
            // dd($users); die;
            $chart = UserTemplate::find($request->chart_id);
            $emailcontent['chartname']  = $chart->template_name;
            $emailcontent['username']   = Auth()->guard('roleuser')->user()->name;
            $emailcontent['msg']        = $request->msg;
            $emailcontent['chartid']    = $request->chart_id;
            $activeUser =[];
            $activeStatus =[];
            foreach ($users as $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)){ 
                    $chkUserMail = $this->existUserShare($value,$request->chart_id);
                    if($chkUserMail != 'exists'){
                        $activeUser[] = $value;
                        $activeStatus[]=0;
                    }
                }
            } 
            $usermail   = $activeUser;
            $userstatus = $activeStatus;
            //dd($usermail);
            //dd($userstatus);
            $chkUserExist = TemplateShare::where('user_id',Auth()->guard('roleuser')->user()->id)->where('user_template_id',$chart->id)->first();
            if($chkUserExist){
                $dbemails= unserialize($chkUserExist['user_email']);
                $dbstatus= unserialize($chkUserExist['view_status']);
                $usermail = array_merge($dbemails,$usermail);
                $userstatus = array_merge($dbstatus,$userstatus);
                $shareTemplate =TemplateShare::find($chkUserExist->id);
                $shareTemplate->user_email =serialize($usermail);
                $shareTemplate->view_status =serialize($userstatus);
                $shareTemplate->msg =$request->msg;
                $shareTemplate->save();
            }else{
                $shareTemplate = new TemplateShare();
                $shareTemplate->user_id =Auth()->guard('roleuser')->user()->id;
                $shareTemplate->user_template_id =$chart->id;
                $shareTemplate->user_email =serialize($usermail);
                $shareTemplate->view_status =serialize($userstatus);
                $shareTemplate->msg =$request->msg;
                $shareTemplate->save();
            }
            foreach ($usermail as $key => $value) {
                \Mail::to($value)->send(new FlowChartShareMail($emailcontent));
            }
            return back()->with('status','Flow Chart Shared Successfully');           
        }else{
            return back()->with('failure','Sender Email-ID Is Missing');
        }
    }
    public function existUserShare($email,$chart_id){
        $chkUserExist = TemplateShare::where('user_id',Auth()->guard('roleuser')->user()->id)->where('user_template_id',$chart_id)->first();
        if($chkUserExist){
            $dbemails= unserialize($chkUserExist['user_email']);
            if(in_array($email, $dbemails)){
                return 'exists';
            }else{
                return 'none';
            }            
        }else{
            return 'none';
        }
    }
    public function reshareChart(Request $request){
        $chkUserExist = TemplateShare::where('user_id',Auth()->guard('roleuser')->user()->id)->where('user_template_id',$request->chart_id)->first();
        if($chkUserExist){
            $chart = UserTemplate::find($request->chart_id);
            $emailcontent['chartname']  = $chart->template_name;
            $emailcontent['username']   = Auth()->guard('roleuser')->user()->name;
            $emailcontent['msg']        = $chkUserExist->msg;
            $emailcontent['chartid']    = $request->chart_id;
            \Mail::to($request->email)->send(new FlowChartShareMail($emailcontent));
            return 1;
        }else{
            return 0;
        }
    }
    public function chkshareExist(Request $request){
        $chkUserExist = TemplateShare::where('user_id',Auth()->guard('roleuser')->user()->id)->where('user_template_id',$request->chart_id)->first();

        if($chkUserExist){
            $dbemails= unserialize($chkUserExist['user_email']);
            if(in_array($request->email, $dbemails)){
                return 'exists';
            }else{
                return 'none';
            }            
        }else{
            return 'none';
        }
    }
    public function chkshareChartExist(Request $request){
        $chkUserExist = TemplateShare::where('user_id',Auth()->guard('roleuser')->user()->id)->where('user_template_id',$request->chart_id)->first();
        if($chkUserExist){
            $dbemails= unserialize($chkUserExist['user_email']);
            $dbstatus= unserialize($chkUserExist['view_status']);
            $emails=[];
            $useremails=[];
            foreach ($dbemails as $key => $value) {
                $chk = User::where('email',$value)->first();
                if($chk){
                    $emails[] = ucfirst($chk->name);
                }else{
                   $emails[] = $value;
                }
                $useremails[] = $value;
            }
            
            $cnt = count($dbemails);
            return response()->json(['cnt' => $cnt,'emails'=>$emails,'useremails'=>$useremails,'dbstatus'=>$dbstatus]);
        }else{
            return 'none';
        }
    }
    public function getNotification(){
        $userm=Auth()->guard('roleuser')->user()->email;
        $sharedTemplate = TemplateShare::get();
        $templates=[];
        $usertemplate=[];
        $notifications=[];
        $templateIDs=[];
        $notifications = Notification::where('to_id',Auth()->guard('roleuser')->user()->id)->where('updated_by',0)->where('status',0)->latest()->get();
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);                
                if(in_array(Auth()->guard('roleuser')->user()->email, $dbemails)){
                    $getKey = array_search (Auth()->guard('roleuser')->user()->email, $dbemails);
                    foreach ($dbstatus as $key => $statusvalue) {
                        if($getKey == $key && $statusvalue == 0){
                            $templates[]=$value['id'];
                            $templateIDs[] =$value['user_template_id'];
                        }
                    }
                }
                //dd($templates);
            }

            if(count($templates) > 0){
                $chkTemplates = UserTemplate::whereIn('id',$templateIDs)->pluck('id')->toArray();
                $usertemplate = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$chkTemplates)->latest()->get(); 
                return response()->json(['cnt' =>(count($usertemplate)+count($notifications)),'usertemplate'=>$usertemplate,'templates'=>implode(',',$templates),'notifications'=>$notifications]);
            }else{
                return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications,'usertemplate'=>$usertemplate]);
            }
        }else{
            return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications,'usertemplate'=>$usertemplate]);
        }
    }
          
    public function updateNotification(Request $request){
        $notifications = Notification::where('to_id',Auth()->guard('roleuser')->user()->id)->where('updated_by',0)->where('status',0)->get();
        if(count($notifications) > 0){ 
            Notification::where('to_id',Auth()->guard('roleuser')->user()->id)->where('updated_by',0)->where('status',0)->update(['status'=>'1']);
        }
        $templates = TemplateShare::whereIn('id',explode(',', $request->template_ids))->get();
        if(count($templates) > 0){
            foreach ($templates as $key => $value) {
                $getKey='';
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);
                $getKey = array_search (Auth()->guard('roleuser')->user()->email, $dbemails);
                $status=[];
                //dd($getKey);
                foreach ($dbstatus as $key => $statusvalue) {
                    if($getKey == $key){
                        $status[]=1;
                    }else{
                        $status[]=$statusvalue;
                    }
                }
                // dd($status);
                $update = TemplateShare::find($value['id']);
                $update->view_status =serialize($status);
                $update->save();                
          }
        }
        if(count($notifications) > 0 || count($templates) > 0){
            return 1;
        }else{
           return 0;            
        }
    }
}
