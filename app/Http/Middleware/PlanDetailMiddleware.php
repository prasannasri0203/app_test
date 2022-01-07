<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Superadmin\Renewal_details;
use Session;
class PlanDetailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            Session::flush();
            Auth::guard('web')->logout();
            return redirect('user-login');
        }
        $useremail      =   User::where('email',Auth()->guard('web')->user()->email)->where('parent_id',0)->get();
        if(count($useremail)==0){
          $team =  User::where('email',Auth()->guard('web')->user()->email)->whereNotNull('parent_id')->where('user_role_id',1)->first();
          if($team){            
            $useremail      =   User::where('id',$team['parent_id'])->get();
          }else{
            Session::flush();
            Auth::guard('web')->logout();
            return redirect('user-login')->withInput()->withErrors(['email' => trans('Invalid Email-ID')]);
          }
        }
        if($useremail[0]->user_role_id==2){//trial
            $status =   $this->Validatetrailuser($useremail[0]['plan_id'],$useremail[0]->id);
        }else if($useremail[0]->user_role_id==4){//enterpriser
            if($useremail[0]->is_approved != 1){
                return redirect('user-login')->with('status','Your request has not been approved yet!');  
            }else{
               $status =   $this->Validateotherusers($useremail[0]['plan_id'],$useremail[0]->id);
            }
        }else{//team,individual
            $status =   $this->Validateotherusers($useremail[0]['plan_id'],$useremail[0]->id);
        }
        if(Auth::user()->user_role_id == 1 && Auth::user()->parent_id != 0){//team user of enterpriser
            if($status=='Expired'){
                Session::flush();
                Auth::guard('web')->logout();
                return redirect('user-login')->with('status','Your admin renewal has been expired, please contact admin!'); 
            }else if($status=='Not a valid user'){
                Session::flush();
                Auth::guard('web')->logout();
                return redirect('user-login')->with('status','Your admin is not a valid user!');   
            }
        }
        if($status=='Expired'){
            return redirect('plan-setting')->with('status','Your plan has been expired. Please upgrade your plan');
            // Session::flush();
            // Auth::guard('web')->logout(); 
            // return redirect('user-login')->with('status','Your plan has been expired. Please contact admin!');
        }else if($status=='Not a valid user'){
            Session::flush();
            Auth::guard('web')->logout();
            return redirect('user-login')->with('status','You are not a valid user!');   
        }else{
            $chkUserActive = User::where('email',Auth()->guard('web')->user()->email)->where('status',1)->first();
            if($chkUserActive){
                return $next($request);
            }else{
                Session::flush();
                Auth::guard('web')->logout();
                return redirect('user-login')->with('status','You cannot access, please contact admin!');   
            }
        }        
    }
    public function Validatetrailuser($planid,$user_id){
        $plandetails    = Renewal_details::where('user_id',$user_id)->where('plan_id',$planid)->where('is_activate',1)->where('status',1)->first();
        $currentTime = date('Y-m-d');
        if($plandetails){
            if($currentTime >= date('Y-m-d',strtotime($plandetails['renewal_date']))){
                return "Expired";
            }else{
                return "Not Expired";
            }
        }else{
            return "Not a valid user";
        }
    }

    public function Validateotherusers($planid,$user_id){
        $plandetails      =   Renewal_details::where('user_id',$user_id)->where('plan_id',$planid)->where('is_activate',1)->where('status',1)->get();
        // dd($plandetails,$planid,$user_id);
        date_default_timezone_set('Asia/Kolkata');
        $currentTime      =   date('Y-m-d');
        if(count($plandetails) > 0){
            if($currentTime >= date('Y-m-d',strtotime($plandetails[0]->renewal_date))){
                return "Expired";
            }else{
                return "Not Expired";
            }
        }else{
            return "Not a valid user";
        }
    }
}
