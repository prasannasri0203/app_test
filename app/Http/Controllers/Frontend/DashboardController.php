<?php

namespace App\Http\Controllers\Frontend;

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
use App\Models\Comment;
use App\Models\UserTemplate;
use App\Models\UserTemplateTrack;
use App\Models\Notification;
use App\Models\TemplateShare;
use App\Mail\FlowChartShareMail;
use App\Models\FlowchartProject;
class DashboardController extends Controller{

    public function index(){
        $Module['module']   =   'Dashboard';
        $user_id=Auth::user()->id;
        $parent_id=Auth::user()->parent_id;
        $tendaysbefore =   Carbon::now()->subDays(10)->format('Y-m-d');
        $proIds=[];
        $proIds1 =[];
        if(Auth::user()->user_role_id == '4'){//enterprise user
            $teamUser = User::where('parent_id',$user_id)->where('user_role_id',1)->pluck('id')->toArray();
            $proIds1 = FlowchartProject::whereIn('created_by',$teamUser)->pluck('id')->toArray(); //get team user project           
        }else if(Auth::user()->user_role_id == '1'){//team user
            if($parent_id != 0){//team user of enterpriser                
                $proIds1 = FlowchartProject::where('created_by',$parent_id)->where('team_user_id',$user_id)->pluck('id')->toArray();//get project assigned by enterpriser(parent)
            }
        }
        $proIds2 = FlowchartProject::where('created_by',$user_id)->pluck('id')->toArray();
        $proIds = array_merge($proIds1,$proIds2);
        // if(Auth::user()->user_role_id == 2 || Auth::user()->user_role_id== 3)
        // {
            $usertemplate = UserTemplate::where('user_id',$user_id)
            ->where(function($q) use($tendaysbefore)
                {   
                   $q->whereDate('created_at', '>=', $tendaysbefore);
                })
           ->whereHas('userTemplateTrack',function($q) use($user_id)
           {
                $q->where('user_id',$user_id)->where('status',1);
           })->orderBy('id', 'desc')->paginate(5);

        /*} 
        elseif( (Auth::user()->user_role_id == 1 &&  Auth::user()->parent_id == 0) || Auth::user()->user_role_id == 4) 
        {//team(own user),enterpriser


              $usertemplate = UserTemplate::with('flowchartProject')->whereHas('flowchartProject',function($q) use($user_id)
            {
                  $q->where('created_by',$user_id)->where('status',1);
            })->where('created_at', '>=', $tendaysbefore)
            ->whereHas('userTemplateTrack',function($q)
            {
                 $q->where(function($q)
                 {   
                      $q->where('status',1);
                 });
            })->where('status',1)           
              ->orderBy('created_at', 'desc')->paginate(5);

        }
        elseif(Auth::user()->user_role_id == 1 &&  Auth::user()->parent_id) 
        {//team user of enterpriser


            $parent_id = Auth::user()->parent_id;
            $usertemplate = UserTemplate::whereHas('flowchartProject',function($q) use($user_id,$parent_id)
            {     
                 $q->where(function($q) use($user_id,$parent_id)
                     {
                            $q->where('created_by',$user_id)->orWhere('created_by',$parent_id);
                      })->where('status',1);

            })->where('created_at', '>=', $tendaysbefore)
            ->whereHas('userTemplateTrack',function($q)
            {
                 $q->where(function($q)
                 {   
                      $q->where('status',1);
                 });
            })->where('status',1)           
              ->orderBy('created_at', 'desc')->paginate(5);

        }*/
        $receivedfc =[];
        $templates=[];
        $sharedTemplate=[];
        $templateIDs=[];
        $sharedTemplate = TemplateShare::get();
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);                
                if(in_array(Auth::user()->email, $dbemails)){
                  $getKey = array_search (Auth::user()->email, $dbemails);
                  foreach ($dbstatus as $key => $statusvalue) {
                      if($getKey == $key){
                          $templates[]=$value['id'];
                          $templateIDs[] =$value['user_template_id'];
                      }
                  }
                }
            }
            if(count($templates) > 0){
                $chkTemplates = UserTemplate::whereIn('id',$templateIDs)->pluck('id')->toArray();
                $receivedfc = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$chkTemplates)->whereDate('created_at', '>=', $tendaysbefore)->orderBy('id', 'desc')->paginate(5);
            }
        }
        $projectlist=[];
        $projectlist = FlowchartProject::whereIn('id',$proIds)
            ->where(function($q) use($tendaysbefore)
                {   
                   $q->whereDate('created_at', '>=', $tendaysbefore);
                })->orderBy('id', 'desc')->paginate(5);
        return view('frontend.dashboard',compact('usertemplate','receivedfc','projectlist'),['Module'=>$Module]);
    } 

    public function templateRename(Request $request){     

        $usertemplate = UserTemplate::find($request->template_id);
        $usertemplate->template_name=$request->template_name;
        $usertemplate->save();
         return redirect('user-dashboard')->with('status','Flow Chart Renamed Successfully');
    }
  
    public function templateDelete($id){     
        $user = UserTemplate::find($id);
        if($user->delete())
        {
            $user->userTemplateTrack->status =0;
            $user->userTemplateTrack->save();
            return redirect('user-dashboard')->with('status','Flow Chart Deleted Successfully');
        }else{
            return redirect('user-dashboard')->with('status','Error');
        }
    }
  
    public function templateDuplicate(Request $request){   
        $temp_name=UserTemplate::where('template_name','=',$request->template_name)->get(); 
         
        if(count($temp_name)>0){
            return   $status = 'error';   
        }else
        { 
             $id=$request->original_id;              
             if($id){ 

                $template_name = $request->template_name;
                $usertemplate = UserTemplate::find($id);
                $newUserTemplate = new UserTemplate();
                $newUserTemplate->user_id = $usertemplate->user_id;
                $newUserTemplate->file_name = $usertemplate->file_name;
                $newUserTemplate->template_name = $template_name;
                $newUserTemplate->description = $usertemplate->description;
                $newUserTemplate->status =$usertemplate->status;
                $newUserTemplate->template_id = $usertemplate->template_id;
                $newUserTemplate->project_id = $usertemplate->project_id;
                $newUserTemplate->save(); 

                $userTemplateTrack = new  UserTemplateTrack();
                $userTemplateTrack->user_id =  $usertemplate->user_id;
                $userTemplateTrack->user_template_id =  $newUserTemplate->id;
                $userTemplateTrack->status =1;
                $userTemplateTrack->save();
                return $status= 'success';         
            } 
        }

    } 

    public function checkName(Request $request)
    {  
        if(UserTemplate::where('template_name','=',$request->input('template_name'))->exists()){
               return "Flow Chart Name Already Existing Name...";
            }else{                                                                    
               return "Flow Chart Name Not Exist";
            }  
    }
    public function shareChart(Request $request){
       // dd($request->useremail);
        if(!empty($request->useremail)){
            if(is_array($request->useremail)){
                $users = $request->useremail;
            }else{
                $users = explode(',', $request->useremail);
            }            
            // dd($users);
            $chart = UserTemplate::find($request->chart_id);
            $emailcontent['chartname']  = $chart->template_name;
            $emailcontent['username']   = Auth::user()->name;
            $emailcontent['msg']        = $request->msg;
            $emailcontent['chartid']    = $request->chart_id;
            $activeUser =[];
            $activeStatus =[];
            $inactiveUser=[];
            $inactiveStatus=[];
            foreach ($users as $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)){ 
                    $chkUser = User::with('userRenewalDatail')->where('email',$value);
                        $chkUser->whereHas('userRenewalDatail' , function($query){
                          $query->where('is_activate','1')->where('status','1');
                        });
                    $chkUser = $chkUser->where('status',1)->first();
                    $chkSubuser = User::where('email',$value)->where('parent_id','!=',0)->where('status',1)->first();
                    $chkUserMail = $this->existUserShare($value,$request->chart_id);
                    if($chkUserMail != 'exists'){
                        if($chkUser || $chkSubuser){
                            // if($chkUser){
                                $activeUser[] = $value;
                                $activeStatus[]=0;
                            // }
                            // if($chkSubuser){
                            //     $activeUser[] =$value;
                            //     $activeStatus[]=0;
                            // }
                        }else{
                            $inactiveUser[] = $value;
                            $inactiveStatus[]=0;
                        }
                    }
                }
            } 
            $usermail = array_merge($activeUser,$inactiveUser);
            $userstatus = array_merge($activeStatus,$inactiveStatus);
            // dd($usermail);
            // dd($userstatus);
            $chkUserExist = TemplateShare::where('user_id',Auth::user()->id)->where('user_template_id',$chart->id)->first();
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
                $shareTemplate->user_id =Auth::user()->id;
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
            // return redirect('user-dashboard')->with('failure','Sender Email-ID Is Missing');
            return back()->with('failure','Sender Email-ID Is Missing');
        }
    }
    public function existUserShare($email,$chart_id){
        $chkUserExist = TemplateShare::where('user_id',Auth::user()->id)->where('user_template_id',$chart_id)->first();
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
    public function chkshareExist(Request $request){
        $chkUserExist = TemplateShare::where('user_id',Auth::user()->id)->where('user_template_id',$request->chart_id)->first();
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
        $chkUserExist = TemplateShare::where('user_id',Auth::user()->id)->where('user_template_id',$request->chart_id)->first();
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
        $userm=Auth::user()->email;
        $sharedTemplate = TemplateShare::get();
        $templates=[];
        $usertemplate=[];
        $notifications=[];
        $userdetail=[];
        $templateIDs=[];
        $notifications = Notification::where('to_id',Auth::user()->id)->where('updated_by',0)->where('status',0)->latest()->get();

        if(count($notifications) > 0){ 
            foreach ($notifications as $key => $notify) {
                if($notify->type == 10 || $notify->type == 11){
                    $userdetail[]=User::find($notify->template_id); 
                }else{
                    $userdetail[]='';
                }
            }
        }
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);                
                if(in_array(Auth::user()->email, $dbemails)){
                    $getKey = array_search (Auth::user()->email, $dbemails);
                    foreach ($dbstatus as $key => $statusvalue) {
                        if($getKey == $key && $statusvalue == 0){
                            $templates[]=$value['id'];
                            $templateIDs[] =$value['user_template_id'];
                        }
                    }
                }
            }
            if(count($templates) > 0){
                $chkTemplates = UserTemplate::whereIn('id',$templateIDs)->pluck('id')->toArray();
                $usertemplate = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$chkTemplates)->latest()->get(); 
                return response()->json(['cnt' =>(count($usertemplate)+count($notifications)),'usertemplate'=>$usertemplate,'templates'=>implode(',',$templates),'notifications'=>$notifications,'userdetail'=>$userdetail]);
            }else{
                return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications,'usertemplate'=>$usertemplate,'userdetail'=>$userdetail]);
            }
        }else{
            return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications,'usertemplate'=>$usertemplate,'userdetail'=>$userdetail]);
        }
    }
    public function updateNotification(Request $request){
        $notifications = Notification::where('to_id',Auth::user()->id)->where('updated_by',0)->where('status',0)->get();
        if(count($notifications) > 0){
            Notification::where('to_id',Auth::user()->id)->where('updated_by',0)->where('status',0)->update(['status'=>'1']);
        }
        $templates = TemplateShare::whereIn('id',explode(',', $request->template_ids))->get();
        if(count($templates) > 0){
            foreach ($templates as $key => $value) {
                $getKey='';
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);
                $getKey = array_search (Auth::user()->email, $dbemails);
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
    public function reshareChart(Request $request){
        $chkUserExist = TemplateShare::where('user_id',Auth::user()->id)->where('user_template_id',$request->chart_id)->first();
        if($chkUserExist){
            $chart = UserTemplate::find($request->chart_id);
            $emailcontent['chartname']  = $chart->template_name;
            $emailcontent['username']   = Auth::user()->name;
            $emailcontent['msg']        = $chkUserExist->msg;
            $emailcontent['chartid']    = $request->chart_id;
            \Mail::to($request->email)->send(new FlowChartShareMail($emailcontent));
            return 1;
        }else{
            return 0;
        }
    }
    public function getSubUsers(Request $request){
        $ids=[];
        $users=[];
        $viewer_ids=[];
        $chart = UserTemplate::find($request->chart_id);
        $project = FlowchartProject::find($chart->project_id);
        $user_id=Auth::user()->id;
        $ids = array($project->admin_id,$project->editor_id,$project->approver_id);
        if(Auth::user()->user_role_id == 1 || Auth::user()->user_role_id== 4){
            if($project->viewer_id != ''){
             $viewer_ids = explode(',', $project->viewer_id);
            }
            if($project->team_user_id == 0){
                $ids[]=$project->created_by;
                $teamParentUser = User::find($project->created_by);
                if($teamParentUser->user_role_id == 1 && $teamParentUser->parent_id != 0){
                    $enterpriser = User::find($teamParentUser->parent_id);
                    if($enterpriser->user_role_id == 4){
                       $ids[]=$enterpriser->id; 
                    }
                }
            }else{
                $ids[]=$project->team_user_id;
                $ids[]=$project->created_by;
            }
            $ids = array_merge($viewer_ids,$ids);
            $ids = array_filter($ids);
            $ids = array_filter($ids, function($k) {
              return $k != Auth::user()->id;
            });
            if(count($ids) > 0){
                $users = User::whereIn('id',$ids)->where('status',1)->get();
            }
        }
        return $users;
    }
}


?>