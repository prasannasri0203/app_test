<?php

namespace App\Http\Controllers\FrontendRole;

use DB;
use Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserDetail;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use App\Mail\Userregistermail;
use App\Models\UserTemplateTrack;
use App\Models\Note;
use App\Models\Notification;
use App\Models\Comment;
use App\Models\FlowchartProject;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageUserRequest;
use App\Models\FlowchartQrCode;
use QrCode;
class EditorProjectController extends Controller
{
    

    public function index()
    {

        $Module['module'] = 'flowcharteditor'; 
        $template_name = (isset($_GET['template_name']) && $_GET['template_name'] != '') ? $_GET['template_name'] : ''; 
        $project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : -1; 
        if(Auth::guard('roleuser')->user()->user_role_id == 4){
            $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : 6;
        }else if(Auth::guard('roleuser')->user()->user_role_id == 3){
            $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] :7;
        }
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';  
        $user_id=Auth()->guard('roleuser')->user()->id;
        $user_role_id=Auth::guard('roleuser')->user()->user_role_id;
        if($user_role_id == 1){
            $where = 'admin_id';
        }elseif($user_role_id == 2){
            $where = 'editor_id';
        }elseif($user_role_id == 3){
            $where = 'approver_id';
        }elseif($user_role_id == 4){
            $where = 'viewer_id';
        }
        $filterstatus=[];
        $project_fc_list=[];
        if(Auth::guard('roleuser')->user()->user_role_id == 3){
            $filterstatus =array(['name'=>'Request for Approval','val'=>'2'],['name'=>'Request for Change','val'=>'3'],['name'=>'Approved','val'=>'4'],['name'=>'Rejected','val'=>'5']);
        }else if(Auth::guard('roleuser')->user()->user_role_id == 1 || Auth::guard('roleuser')->user()->user_role_id == 2){
            $filterstatus =array(['name'=>'Draft','val'=>'0'],['name'=>'Active','val'=>'1'],['name'=>'Request for Approval','val'=>'2'],['name'=>'Request for Change','val'=>'3'],['name'=>'Approved','val'=>'4'],['name'=>'Rejected','val'=>'5']);
        }
        $user_project_temp=UserTemplate::with('flowchartProject')->whereHas('flowchartProject',function ($query) use($user_id,$where)
        {
           $query->where($where,'like', '%' . $user_id. '%');
        })->when($template_name,function($q) use($template_name)
            {            
                $q->where('template_name','like', '%' . $template_name. '%');
            })
          
            ->when(($status != -1),function($q) use ($status)
            {                               
                $q->when($status ==0 || $status == 1,function($q) use ($status)
                {
                    $q->where('status',$status);
                })->when($status ==2 || $status == 3,function($q) use ($status)
                {
                    $newstatus = ($status ==2)?1:2;
                    if($newstatus == 1){
                        $q->where('editor_status','1')->orWhere('editor_review',1)->where('status',1)->where('is_approved',0);
                    }else{
                        $q->where('editor_status',2)->where('editor_review','!=',1)->where('status',1);
                    }
                })->when($status ==4 || $status == 5,function($q) use ($status)
                {
                    $newstatus = ($status ==4)?1:2;
                    $q->where('is_approved',$newstatus)->where('status',1);
                })->when($status ==6,function($q) use ($status)
                {
                    $q->where('is_approved',1)->where('status',1);
                })->when($status ==7,function($q) use ($status)
                {
                    $q->whereIn('editor_status',['1','2','3','4'])->where('status',1);
                });
            })
          ->when($to_date && $end_date,function($q) use($to_date,$end_date)
            {
                   $todate = Carbon::createFromFormat('Y-m-d', $to_date)->startOfDay();
                   $enddate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
              $q->whereBetween('created_at',[$todate,$enddate]);
            }); 
            if($project_name!=''){
                $user_project_temp->whereHas('flowchartProject',function($q) use($project_name)
                { 
                    $q->where('project_name','like', '%' . $project_name. '%');
                });
            }  
            
        $user_project_temp=$user_project_temp->sortable()->orderBy('created_at', 'desc')->paginate(10);
            if(Auth::guard('roleuser')->user()->user_role_id ==1 && Auth()->guard('roleuser')->user()->parent_id != 0){
                $project_fc_list=FlowchartProject::where('admin_id',$user_id)->orderBy('created_at', 'desc')->get();
        }  elseif (Auth::guard('roleuser')->user()->user_role_id ==2 && Auth()->guard('roleuser')->user()->parent_id != 0) {
                $project_fc_list=FlowchartProject::where('editor_id',$user_id)->orderBy('id', 'desc')->get();
        }    
       
        return view('frontend-role.manage-project.editor-project-list',compact('user_project_temp','filterstatus','project_fc_list'),['Module'=>$Module]);
    }

     public function addNewRoleFlowcahrt(Request $request){ 
            $temp = new UserTemplate; 
            $temp->user_id = $request->userid;
            $temp->template_name = $request->add_temp_name;  
            $temp->project_id = $request->project_id;  
            $temp->save(); 
        // return redirect('/role-user/flowchart')->with('status','Flow Chart add project type Successfully'); 
             return redirect('/RU-flowchart?user='.$temp->id);
        //return redirect('/role-user/editor-project-list')->with('status','Flow Chart add project type Successfully'); 
    }

    public function addNotes(Request $request)
    {
        //template_id  note 
        $Module['module'] = 'flowchart'; 
        $addnotes = new Note; 
        $addnotes->user_id = $request->userid;
        $addnotes->user_template_id = $request->template_id;
        $addnotes->note = $request->note;
        $addnotes->status = '1'; 
        $addnotes->save();
        return redirect('/role-user/editor-project-list')->with('status','Notes Added Successfully'); 
    }

    public function notelist(Request $request)
    { 
        $notes=Note::with('user')->where('user_template_id',$request->template_id)->where('user_id',$request->user_id)->get(); 
        return response()->json(['notes' => $notes]);
    }

    public function commentList(Request $request)
    {
        $comments=Comment::with('user','user.userTeamRole','user.userRole','degradedUser','degradedUser.userTeamRole','degradedUser.userRole')->where('user_template_id',$request->template_id)->where('request_change',1)->orderBy('created_at', 'desc')->get(); 
        return response()->json(['comments' => $comments]);
    }
    public function commentListrqst(Request $request)
    {        
        $comments= Comment::where('user_template_id',$request->template_id)
            ->with(['userTemplate'=> function ($query) {
                    $query->where('editor_status','2');
                    },'user','user.userTeamRole','user.userRole','degradedUser','degradedUser.userTeamRole','degradedUser.userRole'])
            ->latest()->get();  
        return response()->json(['comments' => $comments]);
    }
     public function requestCmtAdd(Request $request)
    {  
         if($request->comments!='')
        {
            $addnotes = new Comment; 
            $addnotes->user_id = $request->userid;
            $addnotes->user_template_id = $request->temprqstchnge;
            $addnotes->comments = $request->comments;
            $addnotes->status = '1'; 
            $addnotes->save();            
            $this->commentNotificationUpdate($request->userid,$request->temprqstchnge,$addnotes->id,'1');
        } 
        return redirect('/role-user/editor-project-list')->with('status','Comments Added Successfully'); 
    }

    public function editorStatusChange(Request $request)
    { 
        $userstatus=UserTemplate::find($request->moveapprovaltempid); 
        if($userstatus->editor_status == 0){
            $userstatus->editor_status='1';
        }elseif($userstatus->editor_status == 2){
            $userstatus->editor_review='1';
        }
        $userstatus->is_approved='0'; 
        $userstatus->save();
        $this->commentNotificationUpdate(Auth()->guard('roleuser')->user()->id,$request->moveapprovaltempid,0,'5');
        return redirect('/role-user/editor-project-list')->with('status','Flow Chart Move to Approval Successfully'); 
    }
    public function requestStatusChange(Request $request)
    { 
        $userstatus=UserTemplate::find($request->tempchangeid); 
        $userstatus->editor_status='2'; 
        $userstatus->is_approved='0'; 
        if($userstatus->editor_review == 1){
            $userstatus->editor_review='0';
        }
        $userstatus->save();
         if($request->comments!='')
        {
            $addnotes = new Comment; 
            $addnotes->user_id = $request->userid;
            $addnotes->user_template_id = $request->tempchangeid;
            $addnotes->comments = $request->comments;
            $addnotes->status = '1'; 
            $addnotes->request_change='1';
            $addnotes->save();

            $this->commentNotificationUpdate($request->userid,$request->tempchangeid,$addnotes->id,'2');  
        } 
        return redirect('/role-user/editor-project-list')->with('status','Flow Chart Request Changed Successfully'); 
    }
    public function rejectStatusChange(Request $request)
    {  
        $userstatus=UserTemplate::find($request->temprejectid); 
        $userstatus->editor_status='4'; 
        $userstatus->is_approved='2'; 
        $userstatus->save();

        if($request->comments!='')
        {
            $addnotes = new Comment; 
            $addnotes->user_id = $request->userid;
            $addnotes->user_template_id = $request->temprejectid;
            $addnotes->comments = $request->comments;
            $addnotes->status = '1'; 
            $addnotes->save();

            $this->commentNotificationUpdate($request->userid,$request->temprejectid,$addnotes->id,'3');  
        } 
        return redirect('/role-user/editor-project-list')->with('status','Flow Chart Rejected Successfully'); 
    }
    public function approverStatusChange(Request $request)
    {   
        $userstatus=UserTemplate::find($request->approvaltempid); 
        $userstatus->editor_status='3';
        $userstatus->is_approved='1'; 
        $userstatus->save();
        // $qrcode=new FlowchartQrCode;
        // $qrimg=QrCode::size(400)->generate($userstatus->template_name); 
        // $qrcode->user_id=$request->userid;
        // $qrcode->user_template_id=$request->approvaltempid;
        // $qrcode->template_image_name=$qrimg;
        // $qrcode->save();
        $this->commentNotificationUpdate(Auth()->guard('roleuser')->user()->id,$request->approvaltempid,0,'6');  
        return redirect('/role-user/editor-project-list')->with('status','Flow Chart Approval Successfully'); 
    }
     public function templateRename(Request $request){   
        $usertemplate = UserTemplate::find($request->templateid);
        $usertemplate->template_name=$request->templatename;
        $usertemplate->save();
        return redirect('/role-user/editor-project-list')->with('status','Flow Chart Renamed Successfully');
    }
  
    public function templateDelete(Request $request){      
        $user = UserTemplate::find($request->tempid);        
        $user->delete();
        $getNotify = Notification::where('template_id',$request->tempid)->whereIn('type',['1','2','3','5','6'])->get();
        if(count($getNotify) > 0) {
            foreach ($getNotify as $key => $notify) { 
                DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
            }
        }        
       return redirect('/role-user/editor-project-list')->with('status','Flow Chart Deleted Successfully');
    }
  
    public function templateDuplicate(Request $request){   
        $temp_name=UserTemplate::where('template_name','=',$request->template_name)->get(); 
        if(count($temp_name)>0){
            return   $status = 'error';  
              // return "FlowChart Name Already Existing Name..."; 
        }else
        { 
             $id=$request->original_id;              
             if($id){ 

                $template_name = $request->template_name;
                $usertemplate = UserTemplate::find($id);
                $newUserTemplate = new UserTemplate();
                $newUserTemplate->user_id = Auth()->guard('roleuser')->user()->id;
                $newUserTemplate->file_name = $usertemplate->file_name;
                $newUserTemplate->template_name = $template_name;
                $newUserTemplate->description = $usertemplate->description;
                $newUserTemplate->status =$usertemplate->status;
                $newUserTemplate->template_id = $usertemplate->template_id;
                $newUserTemplate->project_id = $usertemplate->project_id;
                $newUserTemplate->save();
                
                $userTemplateTrack = new  UserTemplateTrack();
                $userTemplateTrack->user_id =  Auth()->guard('roleuser')->user()->id;
                $userTemplateTrack->user_template_id =  $newUserTemplate->id;
                $userTemplateTrack->status =1;
                $userTemplateTrack->save();  
                return $status= 'success';                    
               // return redirect('user-dashboard')->with('status','Flow Chart Duplicate Successfully');
            } 
        }

    } 
    public function getSubUsers(Request $request){
        $ids=[];
        $users=[];
        $viewer_ids=[];
        $chart = UserTemplate::find($request->chart_id);
        $project = FlowchartProject::find($chart->project_id);
        $user_id=Auth()->guard('roleuser')->user()->id;
        $ids = array($project->admin_id,$project->editor_id,$project->approver_id);
        if($project->viewer_id != ''){
         $viewer_ids = explode(',', $project->viewer_id);
        }
        if($project->team_user_id == 0){
            $ids[]=$project->created_by;
            $teamParentUser = User::find($project->created_by);
            $ids[]=$teamParentUser->parent_id;
        }else{
            $ids[]=$project->team_user_id;
            $ids[]=$project->created_by;
        }
        $ids = array_merge($viewer_ids,$ids);
        $ids = array_filter($ids);
        $ids = array_filter($ids, function($k) {
          return $k != Auth()->guard('roleuser')->user()->id;
        });
        if(count($ids) > 0){
            $users = User::whereIn('id',$ids)->where('status',1)->get();
        }
        return $users;
    }

    public function commentNotificationUpdate($user_id,$temp_id,$comment_id,$cmt)
    { 
        $msg='';  
        $user_id=Auth()->guard('roleuser')->user()->id;
        $temp_detail=UserTemplate::find($temp_id);
        $ids=[];
        $users=[];
        $viewer_ids=[]; 
        $teamusers =[];
        $project = FlowchartProject::find($temp_detail->project_id);
        $ids = array($project->admin_id,$project->editor_id);
        $ids[]=Auth::guard('roleuser')->user()->parent_id;
        
        $chkUser = User::find(Auth::guard('roleuser')->user()->parent_id);
        if($chkUser->user_role_id == 1 && $chkUser->parent_id != 0){//to enterpriser
            $enterpriser = User::find($chkUser->parent_id);
            if($enterpriser->user_role_id == 4){
               $ids[]=$chkUser->parent_id; 
            }
        }
        
        if($project->viewer_id != '' && $temp_detail->is_approved=='1' && $temp_detail->status == '1'){
            $viewer_ids = explode(',', $project->viewer_id);
        } 
        if(($temp_detail->editor_status == '1' || $temp_detail->editor_status == '2' || $temp_detail->editor_status == '3'|| $temp_detail->editor_status == '4' ) && $temp_detail->status == '1'){
            if($project->approver_id != ''){ 
                $ids[]=$project->approver_id;
            }
        }
        
        $ids = array_merge($viewer_ids,$ids);
        $ids = array_filter($ids);
        $ids = array_filter($ids, function($k) {
          return $k != Auth()->guard('roleuser')->user()->id;
        });
        if(count($ids) > 0){
            $teamusers = User::whereIn('id',$ids)->where('status',1)->get();
        } 
                         // to id save  
       if (count($teamusers) > 0) {
            if($cmt==1){
                $msg=ucfirst(Auth()->guard('roleuser')->user()->name) .' added a comment to '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==2){
                $msg=ucfirst(Auth()->guard('roleuser')->user()->name) .' requested changes to '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==3){
                $msg=ucfirst(Auth()->guard('roleuser')->user()->name) .' rejected this '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==5){
                $msg=ucfirst(Auth()->guard('roleuser')->user()->name) .' requested for approval to this '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==6){
                $msg=ucfirst(Auth()->guard('roleuser')->user()->name) .' approved this '.ucfirst($temp_detail->template_name).' flow chart.';
            }
            foreach ($teamusers as $key => $users) {
                $addnotificate = new Notification; 
                $addnotificate->from_id = $user_id;
                $addnotificate->to_id = $users->id;     // diff to ids
                $addnotificate->template_id = $temp_id; 
                $addnotificate->comment_id = $comment_id;
                $addnotificate->type = $cmt; 
                $addnotificate->message = $msg;
                $addnotificate->save();                     
            }
        }
    }

    public function viewProject($id)
    {
       $Module['module'] = 'flowcharteditor'; 
       $user_id = Auth()->guard('roleuser')->user()->id;
       $user_role_id = Auth()->guard('roleuser')->user()->user_role_id;
       $chkNotif = Notification::where('template_id',$id)->where('updated_by',0)->where('status',0)->where('to_id',$user_id)->first();
        if($chkNotif){
            $chkNotif = Notification::find($chkNotif->id);
            $chkNotif->status=1;
            $chkNotif->save();
        }
        $project = FlowchartProject::find($id);
        $templates = UserTemplate::with('userTemplateTrack','flowchartProject','flowchartMapping')->where('project_id',$id)->get();
        //dd($templates);
        return view('frontend-role.manage-project.project-map-fc',compact('templates','project','user_id','user_role_id'),['Module'=>$Module]);
    }
    public function qrCodeGenerate()
    {  
            $id =   request('id'); //dynamic id
            $qrcode =  "QrCode::size(250)->generate(".$id.")";
            $link = '';
           $html ="{!! ". $qrcode."!!}"; 
            return $html; 

    }
}
