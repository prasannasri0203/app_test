<?php

namespace App\Http\Controllers;
use Auth;
use Session;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RoleUserEditRequest;
use App\Models\Superadmin\Renewal_details;

class RoleUsersController extends Controller
{
    public function index($id=null){
        $sid ='';
        if($id){
            Auth::logout();
            if($id == 1){
                $sid ='Your admin renewal has been expired,  please contact your admin!';
            }else{
                $sid ='Your admin role is not valid, please contact admin!';
            }
            return view('frontend-role-login',['sid'=>$sid]);
        }else{
            if(Auth()->guard('web')->check()){
                return redirect('/user-dashboard');
            }else if(Auth()->guard('roleuser')->check()){
                return redirect('role-user/dashboard');
            }else if(Auth()->guard('superadmin')->check()){
              return redirect('super-dashboard');
            }
            return view('frontend-role-login',['sid'=>$sid]);
        }
    }

    public function LoginUser(Request $request){

        $request->validate([ 
            'email' => 'required|email|max:255', 
            'password' => 'required|max:255',
        ]); 
        $useremail  =  User::where('email',$request->email)->whereNotNull('parent_id')->first();
        $parent = User::where('id',optional($useremail)->parent_id)->where(function($q)
        {
           $q->where('user_role_id',1);
        })->first();
        $userparentemail  =  User::where('email',$request->email)->where('parent_id',0)->first();
        if($userparentemail){
            return redirect('role-user-login')->with('error','Please check user url!');
        } 
        if(!$useremail){
            return redirect('role-user-login')->withInput()->withErrors(['email' => trans('Invalid Email-ID')]);  
        }
        if(!$parent){
            return redirect('role-user-login')->with('error','Please check user url!');
        }
        if(Auth::guard('roleuser')->attempt(['email'=> $request->email,'password' => $request->password])){
            if($parent->plan_id != 0 && $parent->parent_id == 0){//parent - own team user
                if($parent->user_role_id != 1){
                    Session::flush();
                    Auth::logout();
                    return redirect('role-user-login')->with('error','Your admin role is not valid, please contact admin!');
                }
                $status =   $this->Validatetrailuser($parent->plan_id,$parent->id);
            }else if($parent->plan_id == 0 && $parent->parent_id != 0){//parent - team user of enterpriser
                $getEnterpriser = User::where('id',$parent->parent_id)->where('user_role_id',4)->first();
                if($getEnterpriser){
                    $status =   $this->Validatetrailuser($getEnterpriser->plan_id,$getEnterpriser->id);
                }else{
                    $status = 'Not a valid user';
                }
            }
            if($status=='Expired'){
              Session::flush();
              Auth::logout();  
              return redirect('role-user-login')->with('error',"Your admin renewal has been expired,  please contact your admin"); 
            }else if($status=='Not a valid user'){
               Session::flush();
               Auth::logout(); 
               return redirect('role-user-login')->with('error','Your admin is not a valid user!');   
            }else{
                $chkUserActive = User::where('email',$request->email)->where('status',1)->first();
                if($chkUserActive){
                  $currentTime = date('Y-m-d H:i:s');
                  User::where('email',$request->email)->update(['login_at'=>$currentTime]);
                  return redirect('role-user/dashboard');
                }else{
                    Session::flush();
                    Auth::logout();
                   return redirect('role-user-login')->with('error','You cannot access, please contact your admin!');   
                }
            } 

        }else{
            return redirect('role-user-login')->withInput()->withErrors(['password' => trans('Invalid Password')]);   
        }
    }
    public function Validatetrailuser($planid,$user_id){
        $plandetails    = Renewal_details::where('user_id',$user_id)->where('plan_id',$planid)->where('is_activate',1)->where('status',1)->latest()->first();
        $currentTime = date('Y-m-d');
        if($plandetails){
            if($currentTime >= $plandetails['renewal_date']){
                return "Expired";
            }else{
                return "Not Expired";
            }
        }else{
            return "Not a valid user";
        }
    } 
    public function Userlogout($id=null){
        Session::flush();
        Auth::logout(); 
        if($id){
            return redirect('/role-user-login/'.$id);
            // if($id == 1){
            //    return redirect('role-user-login')->with('error','Your admin renewal has been expired,  please contact your admin');
            // }else{
            //     return redirect('role-user-login')->with('error','Your admin role is not valid, please contact admin!');
            // }
        }else{
            return redirect('role-user-login');
        } 
    }

    public function editProfile(){
        $Module['module'] = 'account-setting';
        $userdetails    =   DB::table('users')->join('user_details','user_details.user_id','users.id')->select('users.id','users.name','users.email','user_details.organization_name','user_details.contact_no','user_details.address','user_details.city','user_details.province','user_details.postal_code','users.image')->where('users.id',auth()->guard('roleuser')->user()->id)->get();
        return view('frontend-role.editprofile',['userdetails'=>$userdetails,'Module'=>$Module]);
    }

    public function updateProfile(RoleUserEditRequest $request){

            $userUpdate = User::find($request->user_id);
            $userUpdate->name       = $request->full_name;
             if ($image = $request->file('image')) {
            $destinationPath = public_path().'/user_logo';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $userUpdate->image   = "$profileImage";
            }else{
                unset($userUpdate->image);
            }  
            $userUpdate->save();
            $userDetailupdate   =   UserDetail::where('user_id',$request->user_id)->first();
            $userDetailupdate->contact_no           =   $request->contact_no;
            $userDetailupdate->address              =   $request->address;
            $userDetailupdate->city                 =   $request->city;
            $userDetailupdate->province             =   $request->province;
            $userDetailupdate->postal_code          =   $request->pincode;
            $userDetailupdate->save();
        
        // if($request->password!='' && $request->current_password!='' && $request->password!=null && $request->current_password!=null ){
        //     $user   =   User::where('id',$request->user_id)->get();
        //     if(Hash::check($request->current_password,$user[0]->password)){
        //         $userUpdate->password   = Hash::make($request->password);
        //         $userUpdate->save();
        //            $url    =   url('/role-user/account-setting');
        //         $status    =  "Profile Updated Successfully";
        //         return redirect($url)->with('status','Profile Updated Successfully');
        //     }else{
        //         $url    =   url('/role-user/account-setting');
        //         return redirect($url)->withErrors(['current_password' => trans('Current Password Does Not Match')]);
        //     }
        // }elseif($request->password!='' && $request->current_password=='' ){
        //       $url    =   url('/role-user/account-setting');
        //       return redirect($url)->withErrors(['current_password' => trans('The current password field is required.')]);
        // }

        $url    =   url('/role-user/account-setting');
        return redirect($url)->with('status','Profile Updated Successfully');
    }

}
