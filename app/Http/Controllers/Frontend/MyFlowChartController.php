<?php

namespace App\Http\Controllers\Frontend;

use DB;
use Mail;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserDetail;
use App\Models\Notification;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use App\Mail\Userregistermail;
use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageUserRequest;
use App\Models\Comment;
class MyFlowChartController extends Controller
{
    public function index()
    {
    	$Module['module'] = 'flowchart'; 
        $template_name = (isset($_GET['template_name']) && $_GET['template_name'] != '') ? $_GET['template_name'] : ''; 
        $team_user_id = (isset($_GET['team_user_id']) && $_GET['team_user_id'] != '') ? $_GET['team_user_id'] : ''; 
        $project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : -1; 
        $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
        $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';     
        $user_id=Auth::user()->id;
        $parent_id=Auth::user()->parent_id;
        $getTeamMembers=[];
        $getUsers=[];
        $teamUser=[];
        $proIds=[];
        $proIds1 =[];
        $proIds2 =[];
        $project_fc_list =[];
        $teamUsers = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->get();        
        if(Auth::user()->user_role_id == '4'){//enterprise user
            $teamUser = User::where('parent_id',$user_id)->where('user_role_id',1)->pluck('id')->toArray();
            $proIds1 = FlowchartProject::whereIn('created_by',$teamUser)->pluck('id')->toArray();
            //get team user project           
        }else if(Auth::user()->user_role_id == '1'){
            //team user
            if($parent_id != 0){ //team user of enterpriser                
                $proIds1 = FlowchartProject::where('created_by',$parent_id)->where('team_user_id',$user_id)->pluck('id')->toArray();
                //get project assigned by enterpriser(parent)
            }
        } 
        $proIds2 = FlowchartProject::where('created_by',$user_id)->pluck('id')->toArray();
        $proIds = array_merge($proIds1,$proIds2);
        $project_fc_list = FlowchartProject::WhereIn('id',$proIds)->get();

        if(Auth::user()->user_role_id == 2 || Auth::user()->user_role_id== 3)
        {//trial,individual
            $usertemplate = UserTemplate::where('user_id',$user_id)->when($template_name,function($q) use($template_name)
            {
                $q->where('template_name','like', '%' . $template_name. '%');
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
             if($project_name!=''){
                $usertemplate->whereHas('flowchartProject',function($q) use($project_name)
                { 
                    $q->where('project_name','like', '%' . $project_name. '%');
                });
            }

            $usertemplate=$usertemplate->sortable()->orderBy('id', 'desc')->paginate(10); 
            return view('frontend.myflowchart.index',compact('usertemplate','project_fc_list'),['Module'=>$Module]); 
        }
        elseif(Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4){//team user,enterpriser 
            if(Auth::user()->user_role_id == '4'){//enterprise user
                $getTeamMembers = User::whereIn('parent_id',$teamUser)->where('status',1)->pluck('id')->toArray();//get Team Members
            }
            $usertemplate = UserTemplate::with('flowchartProject','user')->when($template_name,function($q) use($template_name)
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
                });
                
            })
           ->when($to_date && $end_date,function($q) use($to_date,$end_date)
            {
                   $todate = Carbon::createFromFormat('Y-m-d', $to_date)->startOfDay();
                   $enddate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
              $q->where('created_at', '>=', $todate)->where('created_at', '<=', $enddate);
            });
           if($project_name!=''){
                $usertemplate->whereHas('flowchartProject',function($q) use($project_name)
                { 
                    $q->where('project_name','like', '%' . $project_name. '%');
                });
            }
            if($team_user_id!=''){
                $usertemplate->whereHas('flowchartProject',function($q) use($team_user_id)
                {  
                    $q->where('team_user_id',$team_user_id)->orWhere('created_by',$team_user_id);
                }); 
           }
           $usertemplate=$usertemplate->whereIn('project_id',$proIds)->sortable()->orderBy('id', 'desc')->paginate(10); 
        }
        
        $getUsers = User::where('parent_id',$user_id)->where('status',1)->pluck('id')->toArray();//get Users
        $userIds = array_merge($getTeamMembers,$getUsers);
        // dd($userIds);
        $userList = User::whereIn('id',$userIds)->get();
        return view('frontend.myflowchart.team-index',compact('usertemplate','userList','teamUsers','project_fc_list'),['Module'=>$Module]);               
    }

    public function show($id)
    {     
        $usertemplate = UserTemplate::find($id);
        return $usertemplate->template_name; 
    }

    public function rename(Request $request)
    {
       
     $id = $request->template_id;
     $user = UserTemplate::find($id);
     $user->template_name = $request->template_name;
     $user->save();
     return redirect('user-myflowchart')->with('status','Flow Chart Renamed Successfully'); 
    }
    public function addNewFlowcahrt(Request $request){ 
            $temp = new UserTemplate; 
            $temp->user_id = $request->userid;
            $temp->template_name = $request->add_temp_name;  
            $temp->project_id = $request->project_id;  
            $temp->save(); 
       return redirect('/flowchart?user='.$temp->id);
    }

    public function duplicate(Request $request)
    { $temp_name=UserTemplate::where('template_name','=',$request->template_name)->get(); 
        if(count($temp_name)>0){
            return   $status = 'error';   
        }else
        { 
             $id=$request->original_id;              
             if($id){ 
                $template_name = $request->template_name;
                $usertemplate = UserTemplate::find($id);
                $newUserTemplate = new UserTemplate();
                $newUserTemplate->user_id = Auth::user()->id;
                $newUserTemplate->file_name = $usertemplate->file_name;
                $newUserTemplate->template_name = $template_name;
                $newUserTemplate->description = $usertemplate->description;
                $newUserTemplate->status =$usertemplate->status;
                $newUserTemplate->template_id = $usertemplate->template_id;
                $newUserTemplate->project_id = $usertemplate->project_id;
                $newUserTemplate->save();

                $userTemplateTrack = new  UserTemplateTrack();
                $userTemplateTrack->user_id =  Auth::user()->id;
                $userTemplateTrack->user_template_id =  $newUserTemplate->id;
                $userTemplateTrack->status =1;
                $userTemplateTrack->save(); 
                return $status= 'success';                    
               // return redirect('user-dashboard')->with('status','Flow Chart Duplicate Successfully');
            } 
        }     
    }

    public function destroy($id)
    {
        $user = UserTemplate::find($id);
        $user->delete(); 
        $getNotify = Notification::where('template_id',$id)->whereIn('type',['1','2','3','5','6'])->get();
        if(count($getNotify) > 0) {
            foreach ($getNotify as $key => $notify) { 
                DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
            }
        }  
        return redirect('user-myflowchart')->with('status','Flow Chart Deleted Successfully');      
    }

    public function approved($id)
    {    
     $user = UserTemplate::find($id);
     $user->is_approved = 1;
     $user->editor_status='3';
     $user->save();
     $this->commentNotificationUpdate(Auth::user()->id,$id,0,'6');
     return redirect()->back()->with('status','Flow Chart Approved Successfully'); 
    }

    public function addNotes(Request $request)
    {
        //template_id  note 
    	$Module['module'] = 'flowchart'; 
    	$addnotes = new Note; 
        $addnotes->user_id = Auth::user()->id;
        $addnotes->user_template_id = $request->template_id;
        $addnotes->note = $request->note;
        $addnotes->status = '1'; 
        $addnotes->save();
        return redirect('user-myflowchart')->with('status','Notes Added Successfully'); 
    }

    public function noteList(Request $request)
    { 
        $notes=Note::with('user')->where('user_id',Auth::user()->id)->where('user_template_id',$request->template_id)->get(); 
        return response()->json(['notes' => $notes]);
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $id = $request->get('id');
        $user = User::find($id);
        if($user->parent_id)
        {            //enterpricer' team user
            $flowchartProject =FlowchartProject::where('created_by',$id)->orWhere('created_by',$user->parent_id)->get();
        }
        else
        {            //team user and enterpricer
             $subuser =User::where('parent_id',$id)->get();
        }

        //team user and enterpricer
        $team =User::where('parent_id',$id)->get();
        $email_array = [];

        if($team->isNotEmpty())
        {
            foreach($team as $list)
            {    
                if($list->email)
                { 
                    if(!in_array($list->email,$email_array))
                    {
                        $email_array[] = $list->email;
                    }                   
                }
                if(Auth::user()->user_role_id == 4)
                {
                    $subuser =User::where('parent_id',$list->id)->get();
                    foreach($subuser as $sublist)
                    {
                        if($sublist->email)
                        { 
                            if(!in_array($sublist->email,$email_array))
                            {
                                $email_array[] = $sublist->email;
                            }                        
                        }
                    }
                }    // if($list->editor_id &&  optional($list->editor)->email)
                // {
                //     if(!in_array($list->editor->email,$email_array))
                //     {
                //         $email_array[] = $list->editor->email;
                //     } 
                // }
                // if($list->approver_id && $optional($list->approver)->email)
                // {

                //     if(!in_array($list->editor->approver,$email_array))
                //     {
                //         $email_array[] = $list->approver->email;
                //     }
                // }
                // if($list->viewer_id && $optional($list->viewer)->email)
                // {

                //     if(!in_array($list->editor->viewer,$email_array))
                //     {
                //         $email_array[] = $list->viewer->email;
                //     }

                // }
                
    
            }

        }
       
  return response()->json($email_array);
       
     
    }

    public function addComments(Request $request)
    {  
         if($request->comments!='')
        {
            $addnotes = new Comment; 
            $addnotes->user_id = $request->userid;
            $addnotes->user_template_id = $request->temprqstchnge;
            $addnotes->comments = $request->comments;
            $addnotes->status = '1'; 
            $addnotes->save();
            // notification 
            $this->commentNotificationUpdate($request->userid,$request->temprqstchnge,$addnotes->id,'1');        
        } 
        return redirect('user-myflowchart')->with('status','Comments Added Successfully');  
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
    public function addChanges(Request $request)
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
        return redirect('user-myflowchart')->with('status','Flow Chart Request Changed Successfully'); 
    }
    public function reqchangeslist(Request $request)
    {
        $comments= Comment::with('user','user.userTeamRole','user.userRole','degradedUser','degradedUser.userTeamRole','degradedUser.userRole')->where('user_template_id',$request->template_id)->where('request_change',1)->latest()->get();  
        return response()->json(['comments' => $comments]);
    }
    public function rejectFC(Request $request)
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
        return redirect('user-myflowchart')->with('status','Flow Chart Rejected Successfully'); 
    }
    

    public function commentNotificationUpdate($user_id,$temp_id,$comment_id,$cmt)
    { 
        $msg='';  
        $user_detail=User::find($user_id);
        $temp_detail=UserTemplate::find($temp_id);
        $ids=[];
        $teamusers=[];
        $viewer_ids=[]; 
        $project = FlowchartProject::find($temp_detail->project_id); 
        $ids = array($project->admin_id,$project->editor_id);
        if(Auth::user()->user_role_id == 1 || Auth::user()->user_role_id== 4){
            if($project->viewer_id != '' && $temp_detail->is_approved=='1' && $temp_detail->status == '1'){
              $viewer_ids = explode(',', $project->viewer_id);
            } 
            if(($temp_detail->editor_status == '1' || $temp_detail->editor_status == '2' || $temp_detail->editor_status == '3'|| $temp_detail->editor_status == '4' ) && $temp_detail->status == '1'){
                if($project->approver_id != ''){ 
                    $ids[]=$project->approver_id;
                }
            }
            if($project->team_user_id != '0'){
                $ids[] =  $project->team_user_id;
            }
            if(Auth::user()->user_role_id == 1){//to enterpriser
                $teamParentUser = User::find($user_id);
                if($teamParentUser->user_role_id == 1){
                    if($teamParentUser->parent_id != 0){
                        $enterpriser = User::find($teamParentUser->parent_id);
                        if($enterpriser->user_role_id == 4){
                           $ids[]=$enterpriser->id; 
                        }
                    }
                }
            }
            $ids = array_merge($viewer_ids,$ids);
            $ids = array_filter($ids);
            $ids = array_filter($ids, function($k) {
              return $k != Auth::user()->id;
            });
            if(count($ids) > 0){
                $teamusers = User::whereIn('id',$ids)->where('status',1)->get();
            }
        }                    // to id save  
        if (count($teamusers) > 0) {
            if($cmt==1){
                $msg=ucfirst(Auth::user()->name) .' added a comment to '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==2){
                $msg=ucfirst(Auth::user()->name) .' requested changes to '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==3){
                $msg=ucfirst(Auth::user()->name) .' rejected this '.ucfirst($temp_detail->template_name).' flow chart.';
            }elseif($cmt==6){
                $msg=ucfirst(Auth::user()->name) .' approved this '.ucfirst($temp_detail->template_name).' flow chart.';
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
}
