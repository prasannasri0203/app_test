<?php

namespace App\Http\Controllers\Frontend;

use DB;
use Mail;
use App\Models\User;
use App\Models\UserRole;
use App\Models\TeamUserRole;
use App\Models\FlowchartProject;
use App\Models\Notification;
use App\Mail\RoleUserMail;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageUserRequest;
use App\Models\UserTemplate;
class FrontUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Module['module']   =   'Manage User';
        $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
        $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
        $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $role = (isset($_GET['role']) && $_GET['role'] != '') ? $_GET['role'] : '';
        $nid = (isset($_GET['nid']) && $_GET['nid'] != '') ? $_GET['nid'] : '';
        if($nid != ''){
            Notification::where('id',$nid)->where('updated_by',0)->where('status',0)->update(['status'=>'1']);
        }
        $users = User::with('userDetail','userRenewalDatail')->where('parent_id',Auth::user()->id)->when(request()->has('email') && request()->email,function($query){
            $query->where('email','like', '%' . request()->email. '%'); 
        })->when(request()->has('user_name') && request()->user_name,function($query){
           $query->where('name','like', '%' . request()->user_name. '%');
        })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 0 ),function($query){
            $query->where('status','like', '%' . request()->status. '%');
        })->when($role,function($query) use ($role)
        {
                $query->where('user_role_id',$role);
        });
        if($mobile != ''){                    
            $users->whereHas('userDetail' , function($query) use ($mobile) {
              $query->where('contact_no','like', '%' .$mobile. '%');
            });
        }
        $users = $users->sortable()->orderBy('id', 'desc')->paginate(10); 
        $roleList    = DB::table('team_user_roles')->get();      
        return view('frontend.manage-user.index',compact('users','roleList'),['Module'=>$Module]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {      
        $Module['module']   =   'Manage User'; 
        $roleList    = DB::table('team_user_roles')->get();
        return view('frontend.manage-user.create',compact('roleList'),['Module'=>$Module]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManageUserRequest $request)
    {

        if($request->password == $request->password_confirmation)
        {
         
            $user = new User();
            $user->name = $request->full_name;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->user_role_id = $request->user_role_id;
            $user->parent_id = Auth::user()->id;
            $user->is_approved =1;
            $user->is_own =1;
            $user->save();

                $userDetail = new UserDetail();
                $userDetail->user_id = $user->id;
                $userDetail->contact_no = $request->contact_no;
                $userDetail->address = $request->address;
                $userDetail->province = $request->province;
                $userDetail->city = $request->city;   
                $userDetail->postal_code = $request->pincode;        
                $userDetail->save();

            $roledet = TeamUserRole::find( $request->user_role_id);    
            $main_user=User::find(Auth::user()->parent_id);
            if(Auth::user()->parent_id != 0){ 
                $msg = ucfirst(Auth::user()->name).' added '.ucfirst($request->full_name).' as a '.$roledet['role'].' user under his/her team';
                $msgtype=10;

                $addnotify = new Notification; 
                $addnotify->from_id =Auth::user()->id;
                $addnotify->to_id = $main_user->id;
                $addnotify->template_id = $user->id; 
                $addnotify->type = $msgtype; 
                $addnotify->message = $msg;
                $addnotify->save();
            }

            if($request->password !='')
            {			
                $role  = DB::table('team_user_roles')->where('id',$request->user_role_id)->first();
                $emailcontent['update']     =   '0';
                $emailcontent['parent_name']=  Auth::user()->name;
                $emailcontent['username']   =   $user->name;
                $emailcontent['email']      =   $user->email;
                $emailcontent['password']   =   $request->password;
                $emailcontent['role']   =   $role->role;
                $emailcontent['site_link']   =   'https://apps.kaizenhub.ca/role-user-login';
                \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));
            }	
            return redirect('user-list')->with('status','User Added Successfully');  
        }
        else
        {
            return redirect('user-list')->with('status','Password and Confirm Password does not match');    
        }

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
        $Module['module']   =   'Manage User';
        $user = User::find($id);
        $roleList    = DB::table('team_user_roles')->get();
        return view('frontend.manage-user.edit',compact('roleList','user'),['Module'=>$Module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManageUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->full_name;
        $user->email = $request->email;
         if($request->password !='')
         {
            $user->password =  Hash::make($request->password);
         }
       
        $user->user_role_id = $request->user_role_id;
        $user->is_approved =1;
        $user->is_own =1;
        $user->status = $request->status;  
        $user->save();

        $userDetail = UserDetail::where('user_id',$id)->first();
        $userDetail->user_id = $user->id;
        $userDetail->contact_no = $request->contact_no;
        $userDetail->address = $request->address;
        $userDetail->province = $request->province;
        $userDetail->city = $request->city;     
        $userDetail->postal_code = $request->pincode;    
        $userDetail->save();

        if($request->password !='')
        {			
            $role  = DB::table('team_user_roles')->where('id',$request->user_role_id)->first();
            $emailcontent['update']     =   '1';
            $emailcontent['username']   =   $user->name;
            $emailcontent['email']      =   $user->email;
            $emailcontent['password']   =   $request->password;
            $emailcontent['role']   =   $role->role;
            $emailcontent['site_link']   =   'https://apps.kaizenhub.ca/role-user-login';
            \Mail::to($emailcontent['email'])->send(new RoleUserMail($emailcontent));
        }	
        return redirect('user-list')->with('status','User Updated Successfully');  
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
               return redirect('user-list')->with('failure','This user is assigned in projects,you cannot delete');
        }else{ 
            $subuser = User::where('id',$id)->forceDelete();
            $subuserDetail = UserDetail::where('user_id',$id)->delete();
            $getTemplates = UserTemplate::where('user_id',$id)->get();
            if (count($getTemplates) > 0) {
                foreach ($getTemplates as $key => $temp) { 
                    $deleteRows =  UserTemplate::where('id',$temp->id)->delete();
                    $notifications = Notification::where('template_id',$temp->id)->where('updated_by',0)->whereIn('type',['1','2','3','5','6'])->delete();
                }
            }
            $getNotify = Notification::where('template_id',$id)->where('updated_by',0)->where('type',10)->get();              
            if (count($getNotify) > 0) {
                 foreach ($getNotify as $key => $notify) { 
                      DB::delete('DELETE FROM notifications WHERE id = ?', [$notify->id]);   
                 }
            } 
            return redirect('user-list')->with('status','User is Deleted Successfully');
        } 
      }
     
    }
}