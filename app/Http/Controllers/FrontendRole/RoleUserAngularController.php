<?php

namespace App\Http\Controllers\FrontendRole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\UserTemplate;
use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Models\Comment;
use App\Models\Note;
use App\Models\Superadmin\Template;
use File;
use App\Models\Notification;
use App\Models\FlowchartMapping;
use App\Models\TemplateShare;
use Intervention\Image\ImageManagerStatic as Image;

class RoleUserAngularController extends Controller
{
    public function index($id = null)
    {
        return view('angular');
    }
        public function flowchartName($id,$type){
    	$flowchart_list=null;
    	if($type=="user"){
    		$project=UserTemplate::where('id',$id)->first();
    		  if(!empty($project->project_id)){
         	$flowchart_list=UserTemplate::where('project_id',$project->project_id)->where('status',1)->get();
         }
    	}
    	if($type=="default"){
    		$project=Template::where('id',$id)->first();
    	}
    	if(!empty($project->template_name)){
         	$flowchart_name=$project->template_name;
         }
         else{
         	$flowchart_name=null;
         }
        return response()->json(['flowchart_name' => $flowchart_name,'flowchart_list'=>$flowchart_list]);
    }
    public function userDetailsForAngularTool(){
    	return Auth::guard('roleuser')->user();
    }
    public function saveFlowchartTest(){
    	$flowcharts=UserTemplate::where('user_id',Auth::guard('roleuser')->user()->id)->get();
    	$projects=FlowchartProject::where('created_by',Auth::guard('roleuser')->user()->id)->get();
    	return view('angular_flowchart_test',compact('flowcharts','projects'));
    }
    public function saveFlowchart(Request $request,$id = null){

			 try {


			    $rules = [
			       'flowchart' => ['required'],
			       'flowchart_name'=>['required'],
			       'template_id' => ['required'],
			        ];
			    $customMessages = [
			    'flowchart' => 'The Flowchart File is Required',
			    'flowchart_name' => 'The Flowchart Name is Required',
			    'template_id' => 'The Template ID is Required',
			    ];

			       $this->validate($request, $rules, $customMessages);
			       

if(!empty($request->template_id)){
$removefiles=UserTemplate::where('id',$request->template_id)->first();
$imagefile_path=public_path().'/images/defaultFCimages/'.$removefiles->file_name.'.png';
$jsonfile_path='./resources/frontend/angular/src/assets/flowcharts/'.$removefiles->file_name.'.json';
if (file_exists($imagefile_path)) {
    unlink($imagefile_path);
}
if (file_exists($jsonfile_path)) {
    unlink($jsonfile_path);
}
}
			       $data = $request->flowchart;
			       $name = 'USER_' . Auth::guard('roleuser')->user()->id . '_FLW_' . rand(11111, 99999) . date('_d_m_Y');
			       $namewithextension=$name.'.json';
			       $filepathnew_document='./resources/frontend/angular/src/assets/flowcharts/';
			       $filenew =  $filepathnew_document.$namewithextension;
			       File::put($filenew,$data);

/*default image save*/
if($request->image){      
       $width = 179;
      $height = 121;
if (!file_exists(public_path().'/images/defaultFCimages')) {
    mkdir(public_path().'/images/defaultFCimages', 0777, true);
}
        $data=$request->image;
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        $imagename=$name.'.png';
        $filepathnew=public_path().'/images/defaultFCimages/';
        $file =  $filepathnew.$imagename;
 file_put_contents($file, $data);
$image = Image::make($file)->resize($width, $height);
    $result = $image->save($file);
}
/*default image save*/
					$flowchart_save=!empty($request->template_id) ? UserTemplate::find($request->template_id) : new UserTemplate() ;
					$flowchart_save->file_name=$name;
					$flowchart_save->template_name=$request->flowchart_name;
					if($request->status){
            $flowchart_save->status=$request->status;
          }
					$flowchart_save->updated_by=Auth::guard('roleuser')->user()->id;
					$flowchart_save->save();
                 
                    $track=UserTemplateTrack::where('user_template_id',$request->template_id)->first();
                    $flowchart_track=!empty($track) ? UserTemplateTrack::find($track->id) : new UserTemplateTrack() ;
					$flowchart_track->user_template_id=$request->template_id;
					$flowchart_track->user_id=Auth::guard('roleuser')->user()->id;
					if($request->status){
            $flowchart_track->status=$request->status;
          }
					$flowchart_track->save();

				return response()->json(['status' => 'Flowchart Saved Successfully']);
			}
			 catch(\Exception $e){
			    return response()->json(['status' => 'Flowchart Not Saved']);
			}
    	  
    }

    public function projectWithFlowcharts(){
    	    $user_id=Auth::guard('roleuser')->id;
        	$usertemplates = FlowchartProject::where('created_by',$user_id)->get();
        	return $usertemplates;    
    }
    public function flowchartsWithCommentsSave(Request $request){
         try {
		        $rules = [
			       'comments' => ['required'],
			        ];
			    $customMessages = [
			    'comments' => 'The comment is Required',
			    ];
	             $comment_save= new Comment();
				$comment_save->user_id=Auth::guard('roleuser')->user()->id;
				$comment_save->user_name=Auth::guard('roleuser')->user()->name;
				$comment_save->comments=$request->comments;
				if($request->flowchart_id){
				$comment_save->user_template_id=$request->flowchart_id;
				}
				$comment_save->save();
              return response()->json(['status' => 'Comment Saved Successfully','comment_id'=>$comment_save->id]);
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Comment Not Saved']);
			}
    }
    public function flowchartsWithCommentsList($id){
         try {
		        $comments=Comment::with('user')->where('user_template_id',$id)->get();
		        $Authuser=Auth::guard('roleuser')->user()->id;
		        return response()->json(['comments' => $comments,'Authuser'=>$Authuser]);
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Comments having issue']);
			}
    }
    public function flowchartsWithNotesSave(Request $request,$id = null){
    	try {
		        $rules = [
			       'note' => ['required'],
			        ];
			    $customMessages = [
			    'note' => 'The Note is Required',
			    ];
			    if(!empty($id)){
			    	$note= Note::where('shape_id',$id)->first();
			    }
	            $note_save= !empty($note->id) ? Note::find($note->id) : new Note(); 
				$note_save->user_id=Auth::guard('roleuser')->user()->id;
				$note_save->note=$request->note;
				$note_save->shape_id=$request->shape_id;
				if($request->flowchart_id){
				$note_save->user_template_id=$request->flowchart_id;
				}
				$note_save->save();
              return response()->json(['status' => 'Note Saved Successfully']);
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Note Not Saved']);
			}
    }
    public function flowchartsWithNotesList($id){
         try {
		        $notes=Note::where('user_template_id',$id)->get();
		        $Authuser=Auth::guard('roleuser')->user()->id;
		        return response()->json(['notes' => $notes,'Authuser'=>$Authuser]); 
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Notes having issue']);
			}
    }
     public function notesWithShapeId($id,$templateid){
         try {
		        $notes=Note::where('user_template_id',$templateid)->where('shape_id',$id)->get();
		        $Authuser=Auth::guard('roleuser')->user()->id;
		        return response()->json(['notes' => $notes,'Authuser'=>$Authuser]); 
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Notes having issue']);
			}
    }
    /*superadmin*/
     public function saveDefaultFlowchart(Request $request,$id = null){
			 try {
			    $rules = [
			       'flowchart' => ['required'],
			       'flowchart_name'=>['required'],
			       'template_id' => ['required'],
			        ];
			    $customMessages = [
			    'flowchart' => 'The Flowchart File is Required',
			    'flowchart_name' => 'The Flowchart Name is Required',
			    'template_id' => 'The Template ID is Required',
			    ];

                   $this->validate($request, $rules, $customMessages);

if(!empty($request->template_id)){
$removefiles=Template::where('id',$request->template_id)->first();
$imagefile_path=public_path().'/images/defaultFCimages/'.$removefiles->file_name.'.png';
$jsonfile_path='./resources/frontend/angular/src/assets/flowcharts/'.$removefiles->file_name.'.json';
if (file_exists($imagefile_path)) {
    unlink($imagefile_path);
}
if (file_exists($jsonfile_path)) {
    unlink($jsonfile_path);
}
}

			       $data = $request->flowchart;
			       $name = 'USER_' . Auth::guard('roleuser')->user()->id . '_FLW_' . rand(11111, 99999) . date('_d_m_Y');
			       $namewithextension=$name.'.json';
			       $filepathnew_document='./resources/frontend/angular/src/assets/flowcharts/';
			       $filenew =  $filepathnew_document.$namewithextension;
			       File::put($filenew,$data);
/*default image save*/
if($request->image){      
       $width = 179;
      $height = 121;
if (!file_exists(public_path().'/images/defaultFCimages')) {
    mkdir(public_path().'/images/defaultFCimages', 0777, true);
}
        $data=$request->image;
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        $imagename=$name.'.png';
        $filepathnew=public_path().'/images/defaultFCimages/';
        $file =  $filepathnew.$imagename;
 file_put_contents($file, $data);
$image = Image::make($file)->resize($width, $height);
    $result = $image->save($file);
}
/*default image save*/
	$flowchart_save=Template::find($request->template_id);
					$flowchart_save->file_name=$name;
					$flowchart_save->template_name=$request->flowchart_name;
					if($request->status){
            $flowchart_save->status=$request->status;
          }
					$flowchart_save->save();
                 

				return response()->json(['status' => 'Default Flowchart Saved Successfully']);
			}
			 catch(\Exception $e){
			    return response()->json(['status' => 'Default Flowchart Not Saved']);
			}
    	  
    }
    public function projectWithFlowchartsEdit($id,$type){
    	if($type=="user"){
    		$project=UserTemplate::where('id',$id)->first();
    	}
    	if($type=="default"){
    		$project=Template::where('id',$id)->first();
    	}
       
        if(!empty($project->file_name)){
        	   $path = './resources/frontend/angular/src/assets/flowcharts/'.$project->file_name.'.json';
          $json = json_decode(file_get_contents($path), true);
        	if(!empty($project->template_name)) {
        		return response()->json(['flowchart_name' => $project->template_name,'flowchart_json'=>$json]);
        	}
        	 else{
        	 	return response()->json(['flowchart_name' => null,'flowchart_json'=>$json]);
        	 }
        } 
        else{
        	return null;
        }
       	
    }

  public function commentNotificationUpdate($user_id,$temp_id,$comment_id)
    { 
      $msg='';  
       $user_detail=User::where('id',$user_id)->first();
       $temp_detail=UserTemplate::find($temp_id);
        $ids=[];
        $teamusers=[];
        $viewer_ids=[]; 
        $project = FlowchartProject::find($temp_detail->project_id); 
        $ids = array($project->admin_id,$project->editor_id);
        if($project->viewer_id != '' && $temp_detail->is_approved=='1' && $temp_detail->status == '1'){
             $viewer_ids = explode(',', $project->viewer_id);
            } 
            if(($temp_detail->editor_status == '1' || $temp_detail->editor_status == '2' || $temp_detail->editor_status == '3'|| $temp_detail->editor_status == '4' ) && $temp_detail->status == '1'){
                if($project->approver_id != ''){ 
                    $ids[]=$project->approver_id;
                }
            }
        // if(($user_detail->parent_id==0) && ($user_detail->user_role_id == 1 || $user_detail->user_role_id== 4)){
            
            if($project->team_user_id == 0){
                $ids[]=$project->created_by;
                $teamParentUser = User::find($project->created_by);
                $ids[]=$teamParentUser->parent_id;
            }else{
                $ids[]=$project->team_user_id;
                $ids[]=$project->created_by;
            }
            // }
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
           foreach ($teamusers as $key => $users) {  
        
                $msg=ucfirst($user_detail->name) .' added a comment to '.ucfirst($temp_detail->template_name).' flow chart.';
            

            $addnotificate = new Notification; 
            $addnotificate->from_id = $user_id;
            $addnotificate->to_id = $users->id;     // diff to ids
            $addnotificate->template_id = $temp_id; 
            $addnotificate->comment_id = $comment_id; 
            $addnotificate->message = $msg;
            $addnotificate->type= '1'; 
            $addnotificate->save();                     
           }
        }
    }
        public function commentNotificationUpdateStatus($noti_id)
    { 
       $addnotificate =Notification::find($noti_id); 
       $addnotificate->status = 1;
       $addnotificate->save();
    }
 public function flowchartMapping($temp_id,$fc_id,$order = null){
      $usertemplate_details=UserTemplate::where('id',$temp_id)->first();
        $fccount=FlowchartMapping::where('user_template_id',$temp_id)->get();
        if(count($fccount)>0){
         $ordernumber=count($fccount)+1;
        }
        else{
         $ordernumber=1;   
        }
        
       $fc=new FlowchartMapping();
       $fc->project_id=$usertemplate_details->project_id;
       $fc->mapped_flowchart_id=$fc_id;
       $fc->user_template_id=$temp_id;
       $fc->order_number=$ordernumber;
       $fc->save();
 
        return response()->json(['status' => 'Flowchart Mapped Successfully']);
    }
    public function flowchartWithoutMappingList($id){
      $usertemp=UserTemplate::where('id',$id)->first();
      $tempids =FlowchartMapping::where('project_id',$usertemp->project_id)->where('user_template_id',$id)->pluck('mapped_flowchart_id')->toArray();
      $flowchartlist=UserTemplate::whereNotIn('id',$tempids)->where('id', '!=', $id)->where('project_id',$usertemp->project_id)->whereNull('deleted_at')->get();
      return response()->json(['flowchartlist_without' => $flowchartlist]);
    }
    public function flowchartWithMappingList($id){
      $usertemp=UserTemplate::where('id',$id)->first();
      $tempids =FlowchartMapping::where('project_id',$usertemp->project_id)->where('user_template_id',$id)->pluck('mapped_flowchart_id')->toArray();
      $flowchartlist=UserTemplate::whereIn('id',$tempids)->where('id', '!=', $id)->where('project_id',$usertemp->project_id)->whereNull('deleted_at')->get();
      return response()->json(['flowchartlist_with' => $flowchartlist]);
    }
        public function sharedUpdateNotification($id){
        $notifications = Notification::where('to_id',Auth::guard('roleuser')->id)->where('updated_by',0)->where('status',0)->get();
        if(count($notifications) > 0){
            Notification::where('to_id',Auth::guard('roleuser')->id)->where('updated_by',0)->where('status',0)->update(['status'=>'1']);
        }
        $templates = TemplateShare::where('user_template_id',$id)->get();
        if(count($templates) > 0){
            foreach ($templates as $key => $value) {
                $getKey='';
                $dbemails= unserialize($value['user_email']);
                $dbstatus= unserialize($value['view_status']);
                $getKey = array_search (Auth::guard('roleuser')->email, $dbemails);
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
