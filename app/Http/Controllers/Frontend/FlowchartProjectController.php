<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FlowchartProjectRequest;
use App\Models\Notification;
use DB;
class FlowchartProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Module['module'] = 'flowchart-project'; 
        $project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : -1; 
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : ''; 
    
        $user_id=Auth::user()->id;
        $parent_id=Auth::user()->parent_id;    
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
        // dd($proIds1);
        $proIds2 = FlowchartProject::where('created_by',$user_id)->pluck('id')->toArray();
        $proIds = array_merge($proIds1,$proIds2);
        // dd($proIds);
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
        
        
        $flowchartProject=$flowchartProject->whereIn('id',$proIds)->sortable()->orderBy('id', 'desc')->paginate(10);
        // dd($flowchartProject);

        return view('frontend.flowchart-project.index',compact('flowchartProject'),['Module'=>$Module]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Module['module'] = 'flowchart-project'; 
        $user_id=Auth::user()->id;
        $adminList =[];
        $editorList =[];
        $approverList =[];
        $viewerList =[];
        $teamList=[];
        if(Auth::user()->user_role_id == '1'){
            $adminList = User::where('parent_id',$user_id)->where('user_role_id',1)->where('status',1)->get();
            $editorList = User::where('parent_id',$user_id)->where('user_role_id',2)->where('status',1)->get();
            $approverList = User::where('parent_id',$user_id)->where('user_role_id',3)->where('status',1)->get();
            $viewerList = User::where('parent_id',$user_id)->where('user_role_id',4)->where('status',1)->get();
        }else{
            $teamList = User::where('parent_id',$user_id)->where('user_role_id',1)->where('status',1)->get();
        }
        return view('frontend.flowchart-project.create',compact('adminList','editorList','approverList','viewerList','teamList'),['Module'=>$Module]);
    }
    public function getTeamuser(Request $request){
        $adminList =[];
        $editorList =[];
        $approverList =[];
        $viewerList =[];
        $adminList = User::where('parent_id',$request->user_id)->where('user_role_id',1)->where('status',1)->get();
        $editorList = User::where('parent_id',$request->user_id)->where('user_role_id',2)->where('status',1)->get();
        $approverList = User::where('parent_id',$request->user_id)->where('user_role_id',3)->where('status',1)->get();
        $viewerList = User::where('parent_id',$request->user_id)->where('user_role_id',4)->where('status',1)->get();
        return response()->json(['admins'=>$adminList,'editors'=>$editorList,'approvers'=>$approverList,'viewers'=>$viewerList]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlowchartProjectRequest $request)
    {
        
        $user_id=Auth::user()->id;
        $flowchartProject = new FlowchartProject();
        $flowchartProject->project_name = $request->project_name;
        $flowchartProject->description = $request->description;
        $flowchartProject->admin_id =  $request->admin_id;
        $flowchartProject->editor_id =  $request->editor_id;
        $flowchartProject->approver_id =  $request->approver_id;
        if(isset($request->viewer_id)){
            $flowchartProject->viewer_id =  implode(',',$request->viewer_id);
        }
        $flowchartProject->created_by =  $user_id;
        if(Auth::user()->user_role_id == '4'){
            if(isset($request->team_user_id)){
                $flowchartProject->team_user_id =  $request->team_user_id;
            }
        }
        $flowchartProject->save();
        $this->sendProjectNotification($flowchartProject->id,1);
        // $userTemplate = new UserTemplate();
        // $userTemplate->user_id =  $user_id;
        // $userTemplate->template_name = $request->project_name.'-'.$flowchartProject->id;
        // $userTemplate->status =1;
        // // $userTemplate->editor_status =1;
        // // $userTemplate->is_approved =1;
        // $userTemplate->project_id =  $flowchartProject->id;
        // $userTemplate->save();

        // $userTemplateTrack = new  UserTemplateTrack();
        // $userTemplateTrack->user_id =  $user_id;
        // $userTemplateTrack->user_template_id =  $userTemplate->id;
        // $userTemplateTrack->status =1;
        // $userTemplateTrack->save();


        return redirect('flowchart-project')->with('status','Flow chart project is added successfully');  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $flowchart_project = FlowchartProject::find($id);
        $Module['module'] = 'flowchart-project'; 
        $user_id=Auth::user()->id;
        $adminList =[];
        $editorList =[];
        $approverList =[];
        $viewerList =[];
        $teamList=[];
        $parent = 0;
        if(Auth::user()->user_role_id == '1'){
            $parent=$user_id;
        }else if(Auth::user()->user_role_id == '4'){

            $teamList = User::where('parent_id',$user_id)->where('user_role_id',1)->where('status',1)->get();
            if($flowchart_project['created_by'] == Auth::user()->id){
                $parent=$flowchart_project['team_user_id'];
            }else{
                $parent=$flowchart_project['created_by'];
            }

        }
        if($parent != 0){
            $adminList = User::where('parent_id',$parent)->where('user_role_id',1)->where('status',1)->get();
            $editorList = User::where('parent_id',$parent)->where('user_role_id',2)->where('status',1)->get();
            $approverList = User::where('parent_id',$parent)->where('user_role_id',3)->where('status',1)->get();
            $viewerList = User::where('parent_id',$parent)->where('user_role_id',4)->where('status',1)->get();
        }
        return view('frontend.flowchart-project.edit',compact('adminList','editorList','approverList','viewerList','flowchart_project','teamList'),['Module'=>$Module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FlowchartProjectRequest $request, $id)
    {
        $Module['module'] = 'flowchart-project'; 
        $flowchartProject = FlowchartProject::find($id);

        $db_admin_id=$flowchartProject->admin_id;
        $db_editor_id=$flowchartProject->editor_id;
        $db_approver_id=$flowchartProject->approver_id;
        $db_team_user_id=$flowchartProject->team_user_id;        
        if($db_admin_id!=$request->admin_id && $request->admin_id!=''){
            $chktemplate=UserTemplate::where('project_id',$id)->where('user_id',$db_admin_id)->get();
            if(count($chktemplate) > 0){
                $update_template=UserTemplate::where('project_id',$id)->where('user_id',$db_admin_id)->update([
                    'user_id'=>$request->admin_id
                ]); 
            }          
        }
        if($db_editor_id!=$request->editor_id && $request->editor_id!=''){
            $chktemplate=UserTemplate::where('project_id',$id)->where('user_id',$db_editor_id)->get();
            if(count($chktemplate) > 0){
                $update_template=UserTemplate::where('project_id',$id)->where('user_id',$db_editor_id)->update([
                    'user_id'=>$request->editor_id
                ]); 
            }                    
        }
        // if($db_approver_id!=$request->approver_id && $request->approver_id!=''){
        //     $update_template=UserTemplate::where('project_id',$id)->where('user_id',$db_approver_id)->update([
        //             'user_id'=>$request->approver_id
        //         ]); 
        // }  
        


        $flowchartProject->project_name = $request->project_name;
        $flowchartProject->description = $request->description;
        $flowchartProject->admin_id =  $request->admin_id;
        $flowchartProject->editor_id =  $request->editor_id;
        $flowchartProject->approver_id =  $request->approver_id;
        if(isset($request->viewer_id)){
            $flowchartProject->viewer_id =  implode(',',$request->viewer_id);
        }
        $flowchartProject->status =  $request->status;
        if(Auth::user()->user_role_id == '4'){
            if(isset($request->team_user_id)){
                $flowchartProject->team_user_id =  $request->team_user_id;
            }
        }
        $flowchartProject->save();
        $this->sendProjectNotification($flowchartProject->id,$request->status);
        
        return redirect('flowchart-project')->with('status','Flow chart project is updated successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flowchartProject = FlowchartProject::find($id);
        if($flowchartProject)
        {

            if(optional($flowchartProject->userTemplate)->count())
            {
                foreach($flowchartProject->userTemplate as $usertemplate)
                {
                    
                  
                    if(optional($usertemplate)->userTemplateTrack)
                    {
                        $usertemplate->userTemplateTrack->status =0;
                        $usertemplate->userTemplateTrack->save();
                    }
                    $usertemplate->delete();
                }
            }
            $getNotify = Notification::where('template_id',$id)->where('type',4)->get();              
            if (count($getNotify) > 0) {
                 foreach ($getNotify as $key => $notify) { 
                      DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
                 }
            } 
            $flowchartProject->delete();
            
            return redirect('flowchart-project')->with('status','Flow chart project is deleted successfully');
        }else{
            return redirect('flowchart-project')->with('status','Error');
        }
    }

    public function sendProjectNotification($projectId,$status){
        $user_id=Auth::user()->id;
        $ids =[];
        $viewer_ids=[]; 
        $parentUser='';
        $project = FlowchartProject::find($projectId);
        if($status == 1){
            $ids=array($project->admin_id,$project->editor_id,$project->approver_id);
            if($project->viewer_id != ''){
                $viewer_ids = explode(',', $project->viewer_id);
            }
            if($project->team_user_id != '0'){
                $ids[] =  $project->team_user_id;
            }
            if(Auth::user()->user_role_id == 1){//to enterpriser
                $teamParentUser = User::find($user_id);
                if($teamParentUser->user_role_id == 1 && $teamParentUser->parent_id != 0){
                    $enterpriser = User::find($teamParentUser->parent_id);                    
                    if($enterpriser->user_role_id == 4){
                       $parentUser=$enterpriser->id; 
                    }
                }
            }   
            $ids = array_merge($viewer_ids,$ids);
            $ids = array_filter($ids);
            if(count($ids) > 0) {
                //chk user got notify before
                $userChk = Notification::whereIn('to_id',$ids)->where('updated_by',0)->where('template_id',$projectId)->where('type',4)->get();
                if(count($userChk) > 0){
                    $userfid =[];
                    foreach ($userChk as $key => $value) {
                        $userfid[] = $value['to_id'];
                    }
                    $ids = array_diff($ids, $userfid);
                }
                //dd($ids);
                if (count($ids) > 0) {
                    foreach ($ids as $key => $uid) {
                        $msg=ucfirst(Auth::user()->name) .' assigned a project "'.ucfirst($project->project_name).'" to you.'; 
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
            // dd($parentUser);
            if($parentUser != ''){
                if($project->created_by != $user_id){
                    $msg=ucfirst(Auth::user()->name) .' updated a project '.ucfirst($project->project_name);
                }else{
                    $msg=ucfirst(Auth::user()->name) .' created a project '.ucfirst($project->project_name);
                }
                $addnotificate = new Notification; 
                $addnotificate->from_id = $user_id;
                $addnotificate->to_id = $parentUser;
                $addnotificate->template_id = $projectId; 
                $addnotificate->type = 4; 
                $addnotificate->message = $msg;
                $addnotificate->save();
            }
        }
    }
    public function viewProject($id)
    {
        $Module['module'] = 'flowchart-project'; 
        $chkNotif = Notification::where('template_id',$id)->where('updated_by',0)->where('status',0)->where('to_id',Auth::user()->id)->first();
        if($chkNotif){
            $chkNotif = Notification::find($chkNotif->id);
            $chkNotif->status=1;
            $chkNotif->save();
        }
        $project = FlowchartProject::find($id);
        $templates = UserTemplate::with('userTemplateTrack','flowchartProject','flowchartMapping')->where('project_id',$id)->get();
        //dd($templates);
        return view('frontend.myflowchart.project-map-fc',compact('templates','project'),['Module'=>$Module]);  
    }
}
