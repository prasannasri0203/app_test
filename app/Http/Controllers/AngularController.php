<?php

namespace App\Http\Controllers;

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


class AngularController extends Controller
{
    public function index($id = null)
    {
        return view('angular');
    }
   /* public function userDetailsForAngularTool(){
    	return auth()->user();
    }
    public function saveFlowchartTest(){
    	$flowcharts=UserTemplate::where('user_id',Auth::user()->id)->get();
    	$projects=FlowchartProject::where('created_by',Auth::user()->id)->get();
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
			       $request->validate($rules);
			       $data = $request->flowchart;
			       $name = 'USER_' . Auth::user()->id . '_FLW_' . rand(11111, 99999) . date('_d_m_Y').'.json';
			       $namewithextension=$name;
			       $filepathnew_document='../resources/frontend/angular/src/assets/flowcharts/';
			       $filenew =  $filepathnew_document.$namewithextension;
			       File::put($filenew,$data);


					$flowchart_save=!empty($request->template_id) ? UserTemplate::find($request->template_id) : new UserTemplate() ;
					$flowchart_save->file_name=$namewithextension;
					$flowchart_save->template_name=$request->flowchart_name;
					$flowchart_save->status=$request->status;
					$flowchart_save->updated_by=Auth::user()->id;
					$flowchart_save->save();
                 
                    $track=UserTemplateTrack::where('user_template_id',$request->template_id)->first();
                    $flowchart_track=!empty($track) ? UserTemplateTrack::find($track->id) : new UserTemplateTrack() ;
					$flowchart_track->user_template_id=$request->template_id;
					$flowchart_track->user_id=Auth::user()->id;
					$flowchart_save->status=$request->status;
					$flowchart_track->save();

				return response()->json(['status' => 'Flowchart Saved Successfully']);
			}
			 catch(\Exception $e){
			    return response()->json(['status' => 'Flowchart Not Saved']);
			}
    	  
    }

    public function projectWithFlowcharts(){
    	    $user_id=Auth::user()->id;
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
				$comment_save->user_id=Auth::user()->id;
				$comment_save->user_name=Auth::user()->name;
				$comment_save->comments=$request->comments;
				if($request->flowchart_id){
				$comment_save->user_template_id=$request->flowchart_id;
				}
				$comment_save->save();
              return response()->json(['status' => 'Comment Saved Successfully']);
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Comment Not Saved']);
			}
    }
    public function flowchartsWithCommentsList(){
         try {
		        $comments=Comment::with('user')->get();
		        $Authuser=Auth::user()->id;
		        return response()->json(['comments' => $comments,'Authuser'=>$Authuser]);
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Comments having issue']);
			}
    }
    public function flowchartsWithNotesSave(Request $request){
    	try {
		        $rules = [
			       'note' => ['required'],
			        ];
			    $customMessages = [
			    'note' => 'The Note is Required',
			    ];
	             $note_save= new Note();
				$note_save->user_id=Auth::user()->id;
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
    public function flowchartsWithNotesList(){
         try {
		        $notes=Note::get();
		        $Authuser=Auth::user()->id;
		        return response()->json(['notes' => $notes,'Authuser'=>$Authuser]); 
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Notes having issue']);
			}
    }
     public function notesWithShapeId($id){
         try {
		        $notes=Note::where('shape_id',$id)->get();
		        $Authuser=Auth::user()->id;
		        return response()->json(['notes' => $notes,'Authuser'=>$Authuser]); 
			}
			catch(\Exception $e){
			    return response()->json(['status' => 'Notes having issue']);
			}
    }
    
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
			       $data = $request->flowchart;
			       $name = 'USER_' . Auth::user()->id . '_FLW_' . rand(11111, 99999) . date('_d_m_Y').'.json';
			       $namewithextension=$name;
			       $filepathnew_document='../resources/frontend/angular/src/assets/flowcharts/';
			       $filenew =  $filepathnew_document.$namewithextension;
			       File::put($filenew,$data);

					$flowchart_save=!empty($request->template_id) ? Template::find($request->template_id) : new Template() ;
					$flowchart_save->file_name=$namewithextension;
					$flowchart_save->template_name=$request->flowchart_name;
					$flowchart_save->status=$request->status;
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
        $path = '../resources/frontend/angular/src/assets/flowcharts/'.$project->file_name;
        if(!empty($project->file_name)){
        	$json = json_decode(file_get_contents($path), true); 
       return $json;
        } 
        else{
        	return null;
        }
       	
    } */
}
