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
use App\Models\Comment;
use App\Models\Notification;
use App\Models\FlowchartProject;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageUserRequest;
use App\Models\Superadmin\ThemeSetting;
use App\Models\TemplateShare;
class RoleDashboardController extends Controller
{
    public function index(){
//return  Auth::guard('roleuser')->user();
	$user_id=Auth()->guard('roleuser')->user()->id;
    $user_role_id=Auth::guard('roleuser')->user()->user_role_id;
    $Module['module']   =   'Dashboard';        
    $tendaysbefore =   Carbon::now()->subDays(10)->format('Y-m-d');
    $usertemplate=[];
    if($user_role_id == 1 || $user_role_id == 2){//admin/editor - recently created fc
        $usertemplate = UserTemplate::with('flowchartProject','userTemplateTrack')->where('user_id',$user_id)
            ->where(function($q) use($tendaysbefore)
                {   
                   $q->whereDate('created_at', '>=', $tendaysbefore);
                })
           ->whereHas('userTemplateTrack',function($q) use($user_id)
           {
                $q->where('user_id',$user_id)->where('status',1);
           })->orderBy('id', 'desc')->paginate(5);
    }else if($user_role_id == 3 || $user_role_id == 4){//fc list by status
        if($user_role_id == 3){//approver 
            $where = 'approver_id';
            $status = 7;
        }else{
            $where = 'viewer_id';
            $status = 6;
        }

        $usertemplate=UserTemplate::with('flowchartProject','userTemplateTrack')->whereHas('flowchartProject',function ($query) use($user_id,$where)
        {
           $query->where($where,'like', '%' . $user_id. '%');
        })
        ->when(($status != -1),function($q) use ($status)
        {                               
            $q->when($status ==6,function($q) use ($status)
            {
                $q->where('is_approved',1)->where('status',1);
            })->when($status ==7,function($q) use ($status)
            {
                $q->whereIn('editor_status',['1','2','3','4'])->where('status',1);
            });
        });
        $usertemplate=$usertemplate->orderBy('id', 'desc')->paginate(5);
    }
        // dd($usertemplate);
        /*if($user_role_id == 1 || $user_role_id == 2){//admin/editor
          
            $usertemplate=UserTemplateTrack::with('userTemplate')->where('status',1)
            ->where('user_id',$user_id)->where(function($q) use($tendaysbefore)
            {   
               $q->whereDate('created_at', '>=', $tendaysbefore);
            })
            ->whereHas('userTemplate',function($q) use($user_id)
            {
               $q->where('user_id',$user_id);
            })->orderBy('created_at', 'desc')->paginate(5);
        }else{*/
            // $usertemplate=UserTemplateTrack::with('userTemplate')->where('status',1)
            // ->where('user_id',$user_id)->where(function($q) use($tendaysbefore)
            // {   
            //    $q->whereDate('created_at', '>=', $tendaysbefore);
            // })->orderBy('created_at', 'desc')->paginate(5);
        //}

           //  $usertemplate = UserTemplate::where('user_id',$user_id)
           //  ->where(function($q) use($tendaysbefore)
           //      {   
           //         $q->where('created_at', '>=', $tendaysbefore);
           //      })
           // ->whereHas('userTemplateTrack',function($q) use($user_id)
           // {
           //      $q->where('user_id',$user_id)->where('status',1);
           // })->where('status',1)->orderBy('created_at', 'desc')->paginate(5);
        $receivedfc =[];
        $templates=[];
        $sharedTemplate=[];
        $templateIDs=[];
        $sharedTemplate = TemplateShare::get();
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);                
                if(in_array(Auth()->guard('roleuser')->user()->email, $dbemails)){
                  $getKey = array_search (Auth()->guard('roleuser')->user()->email, $dbemails);
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
                $receivedfc = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$templateIDs)->whereDate('created_at', '>=', $tendaysbefore)->orderBy('id', 'desc')->paginate(5);
            }
        }
        $projectlist=[];
        $projectIds=[];
        $projectIds = Notification::where('to_id',$user_id)->where('updated_by',0)->where('type',4)->whereDate('created_at','>=',$tendaysbefore)->orderBy('id', 'desc')->pluck('template_id')->toArray();
         
        if(count($projectIds) > 0){
            $projectIds = array_slice($projectIds, 0,5);
            $projectlist=FlowchartProject::with('userTemplate')->where('status',1)->whereIn('id',$projectIds)->get();
        }
        //dd($projectIds);
        return view('frontend-role.dashboard',compact('usertemplate','user_role_id','receivedfc','projectlist'),['Module'=>$Module]);
    }
    public function setTheme(){
    	$user_id=auth()->guard('roleuser')->user()->id;
    	$user = User::find($user_id);
    	if($user->theme_setting_id != '0'){
    		$theme = ThemeSetting::withTrashed()->where('id',$user->theme_setting_id)->first();
    		return response()->json(['success' => '1', 'background_color' => $theme->background_color, 'font_color' => $theme->font_color]);
    	}else{
    		return response()->json(['success' => '0']);
    	}
    }
    public function themeList(){   	
		$Module['module'] = 'themes';
		$user_id=auth()->guard('roleuser')->user()->id;
    	$user = User::find($user_id);  
    	$theme_id=$user->theme_setting_id;
        $themes = ThemeSetting::where('status',1)->latest()->get(); 
		return view('frontend-role.themelist',compact('themes'),['Module'=>$Module,'theme_id'=>$theme_id]);
    }
    public function updateTheme(Request $request){   	
		$user_id=auth()->guard('roleuser')->user()->id;
    	$user = User::find($user_id);
		$user->theme_setting_id    =  $request->theme_id;        
    	$user->save();
    	return response()->json(['success' => '1', 'message' => 'Theme updated']);
    }

	public function setDefaultTheme()
    {
        $user_id=auth()->guard('roleuser')->user()->id;
        $user = User::find($user_id);
        $user->theme_setting_id   = "0";        
        $user->save();
        return response()->json(['success' => '1', 'message' => 'Default Theme updated']);
    }

    public function templateRename(Request $request){     

        $usertemplate = UserTemplate::find($request->templateid);
        $usertemplate->template_name=$request->templatename;
        $usertemplate->save();
         return redirect('/role-user/dashboard')->with('status','Flow Chart Renamed Successfully');
    }
  
    public function templateDelete(Request $request){     
        $user = UserTemplate::find($request->tempid);
        if($user->delete())
        {
            $user->userTemplateTrack->status =0;
            $user->userTemplateTrack->save();
            return redirect('/role-user/dashboard')->with('status','Flow Chart Deleted Successfully');
        }else{
            return redirect('/role-user/dashboard')->with('status','Error');
        }
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
               // return redirect('user-dashboard')->with('status','Flow Chart Duplicate Successfully');
            } 
        }

    } 


}
