<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Admin; 
use App\Models\User; 
use App\Models\Notification; 
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;  
use App\Http\Requests\AdminEditProfileRequest;
class SuperAdminController extends Controller
{

    public function dashboard()
    {

        $users = User::with('userRenewalDatail');
            $users->whereHas('userRenewalDatail' , function($query){
              $query->where('is_activate','1')->where('status','1');
            });
        $users = $users->where('is_approved','!=',2)->get();
        $userIds=[];
        if(count($users) > 0){
            foreach ($users as $value) {
                $userIds[]=$value['id'];
            }
        }
        //dd($userIds);
        $teamUsers = User::whereIn('id',$userIds)->where('user_role_id',1)->get();
        $individualUsers = User::whereIn('id',$userIds)->where('user_role_id',3)->get();
        $trialUsers = User::whereIn('id',$userIds)->where('user_role_id',2)->get();
        $enterPriserUsers = User::whereIn('id',$userIds)->where('user_role_id',4)->where('is_approved',1)->get();
        $enterPriserUserRequest = User::whereIn('id',$userIds)->where('user_role_id',4)->where('is_approved',0)->get();
        
        $dashboard['module'] = 'Dashboard';
        return view('super-admin.superadmin-dashboard',['Module'=>$dashboard,'teamusers'=>$teamUsers,'individualusers'=>$individualUsers,'totalusers'=>$users,'trialusers'=>$trialUsers,'enterprisers'=>$enterPriserUsers,'enterpriser_request'=>$enterPriserUserRequest]); 
    } 
    
    public function logout(){ 
        Session::flush();
        Auth::guard('superadmin')->logout(); 
        return redirect('admin-login');  
    }

    public function adminProfile(Request $request,$id='')
    {
      $dashboard['module'] = 'Dashboard'; 
     $admin=Admin::find(Auth::guard('superadmin')->user()->id); 
      return view('super-admin.admin-profile',compact('admin'))->with(['Module'=>$dashboard]);  
    }
    public function updateAdminProfile(AdminEditProfileRequest $request)
    { 

        $dashboard['module'] = 'Dashboard'; 
        $adminUpdate = Admin::find($request->admin_id);
        $adminUpdate->name  = $request->full_name;
        $adminUpdate->mobile  = $request->contact_no;
        if($request->password != '' && $request->password_confirmation != ''){  
            $adminUpdate->password   = Hash::make($request->password);
        }  
        $adminUpdate->save(); 
        return redirect('admin-profile')->with('status','Profile Updated Successfully');
    }

    public function getNotification(){
        $admin_id=Auth::guard('superadmin')->user()->id;
        $admin_name=Auth::guard('superadmin')->user()->name;   
        $userdetail=[];
        $notifications=[];
        $notifications = Notification::where('to_id',$admin_id)->where('updated_by',1)->where('status',0)->latest()->get();

        if(count($notifications) > 0){ 
            foreach ($notifications as $key => $notify) {
                 $userdetail[]=User::find($notify->from_id); 
            }

             return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications,'userdetail'=>$userdetail]);          
        }else{
            return response()->json(['cnt'=>count($notifications),'notifications'=>$notifications]);
        }
    }
      public function updateNotification(){
        $admin_id=Auth::guard('superadmin')->user()->id;
        $notifications = Notification::where('to_id',$admin_id)->where('updated_by',1)->where('status',0)->get();
        if(count($notifications) > 0){
            Notification::where('to_id',$admin_id)->where('updated_by',1)->where('status',0)->update(['status'=>'1']);
        }        
        if(count($notifications) > 0 ){
            return 1;
        }else{
           return 0;            
        }
    }


}
