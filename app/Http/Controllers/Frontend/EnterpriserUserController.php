<?php

namespace App\Http\Controllers\Frontend;

use DB;
use Mail;
use App\Models\User;
use App\Models\UserRole;
use App\Mail\RoleUserMail;
use App\Models\UserDetail;
use App\Models\Notification;
use App\Models\TeamUserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageEnterpriseTeamUserRequest;
use App\Http\Requests\ManageEnterpriseSubUserRequest;
use App\Models\FlowchartProject;
use App\Models\UserTemplate;
class EnterpriserUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Module['module']   =   'Manage Team User';
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $location = (isset($_GET['location']) && $_GET['location'] != '') ? $_GET['location'] : '';
        $userDetails = User::find(Auth::user()->id);
        $teamUsers = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->get();
        $users = User::with('userDetail')->where('parent_id',Auth::user()->id)->when(request()->has('email') && request()->email,function($query){
            $query->where('email','like', '%' . request()->email. '%'); 
        })->when(request()->has('user_name') && request()->user_name,function($query){
           $query->where('name','like', '%' . request()->user_name. '%');
        })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 0 ),function($query){
            $query->where('status','like', '%' . request()->status. '%');
        });
        if($mobile != ''){                    
            $users->whereHas('userDetail' , function($query) use ($mobile) {
              $query->where('contact_no','like', '%' .$mobile. '%');
            });
        }
        if($location != ''){                    
            $users->whereHas('userDetail' , function($query) use ($location) {
              $query->where('address','like', '%' .$location. '%')->orWhere('city','like','%'.$location.'%')->orWhere('province','like','%'.$location.'%')->orWhere('postal_code','like','%'.$location.'%');
            });
        }
        $users = $users->where('user_role_id',1)->sortable()->orderBy('id', 'desc')->paginate(10); 

       
        return view('frontend.manage-user.team-user.index',compact('users','userDetails','teamUsers'),['Module'=>$Module]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {      
        $Module['module']   =   'Manage Team User';
        return view('frontend.manage-user.team-user.create',['Module'=>$Module]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManageEnterpriseTeamUserRequest $request)
    {

        $user = new User();
        $user->name = $request->full_name;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->user_role_id = $request->user_role_id;
        $user->parent_id = Auth::user()->id;
        $user->status =1;
        $user->save();

        $userDetail = new UserDetail();
        $userDetail->user_id = $user->id;
        $userDetail->contact_no = $request->contact_no;
        $userDetail->address = $request->address;
        $userDetail->province = $request->province;
        $userDetail->city = $request->city;   
        $userDetail->postal_code = $request->pincode;        
        $userDetail->save();
        

        if($request->password !='')
        {	
            $emailcontent['update']     =   '0';
            $emailcontent['parent_name']=  Auth::user()->name;		
            $emailcontent['username']   =   $user->name;
            $emailcontent['email']      =   $user->email;
            $emailcontent['password']   =   $request->password;
            $emailcontent['role']       =   'Team';
            $emailcontent['site_link']  =   'https://apps.kaizenhub.ca/role-user-login';
            \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));
        }	
        return redirect('team-user-list')->with('status','Team User Added Successfully');  
        

    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Module['module']   =   'Manage Team User';
        $user = User::find($id);
        return view('frontend.manage-user.team-user.edit',compact('user'),['Module'=>$Module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManageEnterpriseTeamUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->full_name;
        $user->email = $request->email;
        if($request->password !='')
        {
            $user->password =  Hash::make($request->password);
        }
        $user->status = $request->status;  
        $user->save();

        $userDetail = UserDetail::where('user_id',$id)->first();
        $userDetail->contact_no = $request->contact_no;
        $userDetail->address = $request->address;
        $userDetail->province = $request->province;
        $userDetail->city = $request->city;     
        $userDetail->postal_code = $request->pincode;    
        $userDetail->save();

        if($request->password !=''){			
            $role  = DB::table('team_user_roles')->where('id',$request->user_role_id)->first();
            $emailcontent['update']     =   '1';
            $emailcontent['username']   =   $user->name;
            $emailcontent['email']      =   $user->email;
            $emailcontent['password']   =   $request->password;
            $emailcontent['site_link']   =   'https://apps.kaizenhub.ca/role-user-login';
            \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));
        }	
        return redirect('team-user-list')->with('status','Team User Updated Successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user->delete())
        {
            $proIds = FlowchartProject::where('created_by',$id)->pluck('id')->toArray();
            if(count($proIds) > 0){
                $projectDelete = FlowchartProject::whereIn('id',$proIds)->where('created_by',$id)->delete();
                $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();
                if (count($getTemplates) > 0) {
                    foreach ($getTemplates as $key => $temp) { 
                        $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
                        $notifications = Notification::where('template_id',$temp->id)->where('updated_by',0)->whereIn('type',['1','2','3','5','6'])->delete();
                    }
                }
            
                $notifications = Notification::whereIn('template_id',$proIds)->where('updated_by',0)->where('type',4)->delete();
            }  
            $subuserIDs = User::where('parent_id',$id)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
            if(count($subuserIDs) > 0){
                $subuser = User::whereIn('id',$subuserIDs)->forceDelete();
                $subuserDetail = UserDetail::whereIn('user_id',$subuserIDs)->delete();
            }
            return redirect('team-user-list')->with('status','User Is Deleted Successfully');
        }else{
            return redirect('team-user-list')->with('status','Error');
        }
     
    }

    public function getSubUserlist()
    {
        $Module['module']   =   'Manage User';
        $userIds=[];
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $team_user_id = (isset($_GET['team_user_id']) && $_GET['team_user_id'] != '') ? $_GET['team_user_id'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $role = (isset($_GET['role']) && $_GET['role'] != '') ? $_GET['role'] : '';
        $nid = (isset($_GET['nid']) && $_GET['nid'] != '') ? $_GET['nid'] : '';
        if($nid != ''){
            Notification::where('id',$nid)->where('updated_by',0)->where('status',0)->update(['status'=>'1']);
        }
        $userIds = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->pluck('id')->toArray();
        // dd($userIds);
        $users = User::with('userDetail')->when(request()->has('email') && request()->email,function($query){
            $query->where('email','like', '%' . request()->email. '%'); 
        })->when(request()->has('user_name') && request()->user_name,function($query){
           $query->where('name','like', '%' . request()->user_name. '%');
        })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 0 ),function($query){
            $query->where('status','like', '%' . request()->status. '%');
        })->when($role,function($query) use ($role)
        {
                $query->where('user_role_id',$role);
        })->when($team_user_id,function($query) use ($team_user_id)
        {
                $query->where('parent_id',$team_user_id);
        });
        if($mobile != ''){                    
            $users->whereHas('userDetail' , function($query) use ($mobile) {
              $query->where('contact_no','like', '%' .$mobile. '%');
            });
        }        
        $users = $users->whereIn('parent_id',$userIds)->sortable()->orderBy('created_at', 'desc')->paginate(10); 
        $roleList    = DB::table('team_user_roles')->get(); 
        $teamUsers = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->get();
        return view('frontend.manage-user.team-user.sub-user.index',compact('users','roleList','teamUsers'),['Module'=>$Module]);
    }
    public function createSubUser(Request $request, $id = null){
        $userAdd['module'] = 'Manage User';
        $user = $id ? $user = User::find($id) : [];
        $roleList    = DB::table('team_user_roles')->get();
        $teamUsers = User::where('user_role_id',1)->where('parent_id',Auth::user()->id)->get();
        if($id)
        {
          $userDetail = UserDetail::where('user_id', $id)->get();
        }
        else 
        {
          $userDetail=[];
        }
        //dd($userDetail);
        return view('frontend.manage-user.team-user.sub-user.create',['user'=>$user,'roleList'=>$roleList,'Module'=>$userAdd,'userDetail'=>$userDetail,'teamUsers'=>$teamUsers]);
    }

    public function storeSubUser(ManageEnterpriseSubUserRequest $request,$id = null){
        if($id){
            $userInsert = User::find($id);
            $db_user_role = $userInsert->user_role_id;
            $db_user_parentid = $userInsert->parent_id;
        }
        else{            
            $userInsert = new User();
        }
        $userInsert->name = $request->full_name;
        $userInsert->email = $request->email;
        if($request->password !=''){
            $userInsert->password = Hash::make($request->password);
        }
        $userInsert->user_role_id = $request->user_role_id;
        $userInsert->parent_id = $request->team_user_id;
        if($id){
            $userInsert->status = $request->status;
        }else{
            $userInsert->status = '1';
        }
        $userInsert->save();
        if($id){
            $user_id= $id;
        }else{
            $user_id= $userInsert->id;
        }
        $user = User::find($user_id);
        if($id){
            if($db_user_role != $request->user_role_id || $request->password !=''){
                $parent = User::find($request->team_user_id);
                $role  = DB::table('team_user_roles')->where('id',$request->user_role_id)->first();
                if($db_user_role != $request->user_role_id){
                    $emailcontent['update']     =   '0';
                }else{
                    $emailcontent['update']     =   '1';
                }
                $emailcontent['parent_name']=   $parent->name;      
                $emailcontent['username']   =   $user->name;
                $emailcontent['email']      =   $user->email;
                if($request->password !=''){
                    $emailcontent['password']   =   $request->password;
                }else{
                    $emailcontent['password']   =   '';
                }
                $emailcontent['role']       =   $role->role;
                $emailcontent['site_link']  =   'https://apps.kaizenhub.ca/role-user-login';
                \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));
            }
        }else{ 
            $parent = User::find($request->team_user_id);
            $role  = DB::table('team_user_roles')->where('id',$request->user_role_id)->first();
            $emailcontent['update']     =   '0';
            $emailcontent['parent_name']=   $parent->name;      
            $emailcontent['username']   =   $user->name;
            $emailcontent['email']      =   $user->email;
            $emailcontent['password']   =   $request->password;
            $emailcontent['role']       =   $role->role;
            $emailcontent['site_link']  =   'https://apps.kaizenhub.ca/role-user-login';
            \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));  
        }      
        if(!$id){
            $roledet = TeamUserRole::find($request->user_role_id);    
            $team_user_name=User::find($request->team_user_id); 
            $main_user=User::find(Auth::user()->parent_id); 
            $msg = ucfirst(Auth::user()->name).' added '.ucfirst($request->full_name).' as a '.$roledet['role'].' user under your team';
            $msgtype=11;

            $addnotify = new Notification; 
            $addnotify->from_id = Auth::user()->id;
            $addnotify->to_id = $request->team_user_id; 
            $addnotify->template_id = $user_id; 
            $addnotify->type = $msgtype; 
            $addnotify->message = $msg;
            $addnotify->save();
        }
        if($id){
            $getDetail = UserDetail::where('user_id',$id)->first();
            $userDetail = UserDetail::find($getDetail->id);
        }
        else{
            $userDetail = new UserDetail();
            $userDetail->user_id = $user_id;            
        }
        $userDetail->contact_no = $request->contact_no;
        $userDetail->address = $request->address;
        $userDetail->province = $request->province;
        $userDetail->city = $request->city;     
        $userDetail->postal_code = $request->pincode;        
        $userDetail->save();
        if($id){                
            return redirect('sub-user-list')->with('status','User Updated Successfully');
        }
        else{                
            return redirect('sub-user-list')->with('status','User Added Successfully');            
        }
        
    }
    public function deleteSubUser($id){

    if($id){
        $role=User::where('id',$id)->get();
        $roleid=$role[0]->user_role_id; 
        if($roleid==1){
            $chk_proj=FlowchartProject::where('admin_id',$id)->get(); 
        }elseif ($roleid==2) {
            $chk_proj=FlowchartProject::where('editor_id',$id)->get(); 
        }elseif ($roleid==3) {
            $chk_proj=FlowchartProject::where('approver_id',$id)->get(); 
        }elseif ($roleid==4) { 
            $chk_proj=FlowchartProject::whereIn('viewer_id',[$id])->get(); 
        } 
        if(count($chk_proj) > 0){
             return redirect('sub-user-list')->with('failure','This user is assigned in projects,you cannot delete');
        }else{
             if($id){
            $subuser = User::where('id',$id)->forceDelete();
            $subuserDetail = UserDetail::where('user_id',$id)->delete();
            $getTemplates = UserTemplate::where('user_id',$id)->get();
            if (count($getTemplates) > 0) {
                foreach ($getTemplates as $key => $temp) { 
                    $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
                    $notifications = Notification::where('template_id',$temp->id)->where('updated_by',0)->whereIn('type',['1','2','3','5','6'])->delete();
                }
            }
            $getNotify = Notification::where('template_id',$id)->where('updated_by',0)->where('type',11)->get();              
            if (count($getNotify) > 0) {
                 foreach ($getNotify as $key => $notify) { 
                      DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
                 }
            }
            return redirect('sub-user-list')->with('status','User Deleted Successfully');
        }
        }       
        }
    }
}