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
use App\Models\TemplateShare;
use App\Models\FlowchartProject;
use App\Models\Notification;
class ReceiveRoleFlowchartController extends Controller
{
    public function receivedRoleFCLiset(){
        $Module['module']   =  'receive-flowchart';
        $user=Auth::guard('roleuser')->user()->email;
        $sharedTemplate = TemplateShare::get();
        $templates=[];
        $usertemplate=[];
        $templateIDs=[];
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                if(in_array($user, $dbemails)){
                    $templates[]=$value['id'];
                    $templateIDs[] =$value['user_template_id'];
                }
            }
            if(count($templates) > 0){
                $chkTemplates = UserTemplate::whereIn('id',$templateIDs)->pluck('id')->toArray();
                $usertemplate = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$chkTemplates)->orderBy('id', 'desc')->get();
            } 
        }        
               
        return view('frontend-role.manage-project.receive-fc-list',compact('usertemplate'),['Module'=>$Module]);
    } 
    public function getProjects(){
        $Module['module']   =  'manageproject';
        $project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : -1; 
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : ''; 
    
        $user_id=Auth::guard('roleuser')->user()->id;
        $parent_id=Auth::guard('roleuser')->user()->parent_id;    
        $proIds = FlowchartProject::where('admin_id',$user_id)->pluck('id')->toArray(); 
        $flowchartProject = FlowchartProject::
            when($project_name,function($q) use($project_name)
            {
            
                $q->where('project_name','like', '%' . $project_name. '%');
            })
            ->when(($status != -1),function($q) use ($status)
            {
            
                $q->where('status',$status);
            })            
            ->when($to_date && $end_date,function($q) use($to_date,$end_date)
            {
                   $todate = Carbon::createFromFormat('Y-m-d', $to_date)->startOfDay();
                   $enddate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();

              $q->where('created_at', '>=', $todate)->where('created_at', '<=', $enddate);
             
            });
        
        
        $flowchartProject=$flowchartProject->whereIn('id',$proIds)->sortable()->orderBy('created_at', 'desc')->paginate(10);
        // dd($flowchartProject);

        return view('frontend-role.admin-project.index',compact('flowchartProject'),['Module'=>$Module]);
    }
    public function editProject($id)
    {

        $flowchart_project = FlowchartProject::find($id);
        $Module['module'] = 'manageproject'; 
        $user_id=Auth::guard('roleuser')->user()->id;
        $parent=Auth::guard('roleuser')->user()->parent_id;
        $editorList =[];
        $approverList =[];
        $viewerList =[];
        $editorList = User::where('parent_id',$parent)->where('user_role_id',2)->where('status',1)->get();
        $approverList = User::where('parent_id',$parent)->where('user_role_id',3)->where('status',1)->get();
        $viewerList = User::where('parent_id',$parent)->where('user_role_id',4)->where('status',1)->get();
        return view('frontend-role.admin-project.edit',compact('editorList','approverList','viewerList','flowchart_project'),['Module'=>$Module]);
    }
    public function update(Request $request)
    {
       // dd($request->all());
        $Module['module'] = 'flowchart-project'; 
        $flowchartProject = FlowchartProject::find($request->id);
        $flowchartProject->project_name = $request->project_name;
        $flowchartProject->description = $request->description;
        $flowchartProject->editor_id =  $request->editor_id;
        $flowchartProject->approver_id =  $request->approver_id;
        if(isset($request->viewer_id)){
            $flowchartProject->viewer_id =  implode(',',$request->viewer_id);
        }
        $flowchartProject->status =  $request->status;
        $flowchartProject->save();
        $this->sendProjectNotification($flowchartProject->id,$request->status);
        return redirect('role-user/projects')->with('status','Project is updated successfully');  
    }
    public function sendProjectNotification($projectId,$status){
        $user_id=Auth::guard('roleuser')->user()->id;
        $ids =[];
        $viewer_ids=[];
        $parent_users=[];
        $project = FlowchartProject::find($projectId);
        $parent_users[]=Auth::guard('roleuser')->user()->parent_id;
        
        if($status == 1){
            $chkUser = User::find(Auth::guard('roleuser')->user()->parent_id);
            if($chkUser->user_role_id == 1 && $chkUser->parent_id != 0){//to enterpriser
                $enterpriser = User::find($chkUser->parent_id);
                if($enterpriser->user_role_id == 4){
                   $parent_users[]=$chkUser->parent_id; 
                }
            }
            //dd($parent_users);
            if(count($parent_users) > 0){
                foreach ($parent_users as $key => $pid) {
                    $msg=ucfirst(Auth::guard('roleuser')->user()->name) .' updated a project '.ucfirst($project->project_name); 
                    $addnotificate = new Notification; 
                    $addnotificate->from_id = $user_id;
                    $addnotificate->to_id = $pid;     // diff to ids
                    $addnotificate->template_id = $projectId; 
                    $addnotificate->type = 4; 
                    $addnotificate->message = $msg;
                    $addnotificate->save();
                } 
            }
            $ids=array($project->editor_id,$project->approver_id);
            if($project->viewer_id != ''){
                $viewer_ids = explode(',', $project->viewer_id);
            }
            $ids = array_merge($viewer_ids,$ids);
            $ids = array_filter($ids);

            if (count($ids) > 0) {
                //chk user got notify before
                $userChk = Notification::whereIn('to_id',$ids)->where('updated_by',0)->where('template_id',$projectId)->where('type',4)->get();
                if(count($userChk) > 0){
                    $userfid =[];
                    foreach ($userChk as $key => $value) {
                        $userfid[] = $value['to_id'];
                    }
                    $ids = array_diff($ids, $userfid);
                }
                // dd($ids);
                if (count($ids) > 0) {
                    foreach ($ids as $key => $uid) {
                        $msg=ucfirst(Auth::guard('roleuser')->user()->name) .' assigned a project '.ucfirst($project->project_name).' to you.'; 
                        $addnotificate = new Notification; 
                        $addnotificate->from_id = $user_id;
                        $addnotificate->to_id = $uid;     // diff to ids
                        $addnotificate->template_id = $projectId; 
                        $addnotificate->type = 4; 
                        $addnotificate->message = $msg;
                        $addnotificate->save();
                    } 
                } 
            }
        }
    }
}
