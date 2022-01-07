<?php

namespace App\Http\Controllers\FrontendRole;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Superadmin\ThemeSetting;
use App\Models\Superadmin\Template;
use App\Models\Superadmin\TemplateCategory;
use Auth;
use DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\UserTemplate;
use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Models\TemplateShare;
use App\Mail\FlowChartShareMail;

class DefaultTemplateRoleController extends Controller
{
     
      public function index(Request $request){  
        $Module['module']='DefaultTemplate';  
        $tendaysbefore = Carbon::now()->subDays(10)->format('Y-m-d');
        $user_id=Auth()->guard('roleuser')->user()->id;
        $parent_id=Auth()->guard('roleuser')->user()->parent_id;
        $proIds1=[];
        $proIds2=[];

   		$template_cat_id = (isset($_GET['template_cat_id']) && $_GET['template_cat_id'] != '') ? $_GET['template_cat_id'] : ''; 
 
            $default_template = Template::with('templateCategory')->where('status',1) 
					            ->whereHas('templateCategory',function($q) use($user_id)
					            {
					                $q->where('status',1);
					            })->where('status',1);
			if($template_cat_id!='')
			{
				$default_template->where('template_category_id',$template_cat_id);
			}
			$default_template=$default_template->orderBy('created_at', 'desc')->get();

		$template_category=TemplateCategory::where('status',1)->get();

		if(Auth::guard('roleuser')->user()->user_role_id ==1 && Auth()->guard('roleuser')->user()->parent_id != 0){
				$project_fc_list=FlowchartProject::where('admin_id',$user_id)->orderBy('created_at', 'desc')->get();
		}  elseif (Auth::guard('roleuser')->user()->user_role_id ==2 && Auth()->guard('roleuser')->user()->parent_id != 0) {
				$project_fc_list=FlowchartProject::where('editor_id',$user_id)->orderBy('created_at', 'desc')->get();
		}    
       
        return view('frontend-role.default-temple-role.default_temp',compact('default_template','template_category','project_fc_list'),['Module'=>$Module]);
    }   
    public function useTemplateRoleUpdate(Request $request)
    { 
        $default_temp_chk = UserTemplate::where('project_id', $request->project_id)->where('template_name',$request->fc_name)->first();
 $template=Template::where('id',$request->tempid)->first();
        if(!$default_temp_chk){ 
            $default_temp = new UserTemplate; 
            $default_temp->user_id = $request->userid;
            $default_temp->project_id = $request->project_id;
            $default_temp->template_id = $request->tempid;
            if(!empty($template->file_name)){
                 $default_temp->file_name = $template->file_name; 
            }
            $default_temp->template_name = $request->fc_name;  
            $default_temp->save();

            $userTemplateTrack = new  UserTemplateTrack();
            $userTemplateTrack->user_id =  $request->userid;
            $userTemplateTrack->user_template_id =  $default_temp->id;
            $userTemplateTrack->status =1;
            $userTemplateTrack->save();

        	return redirect('/role-user/default-temp-list')->with('status','Flow chart is created successfully');  
        }
        else{ 
            return redirect('/role-user/default-temp-list')->with('failure','Flow chart Name is already existing');
        }
    }



}
