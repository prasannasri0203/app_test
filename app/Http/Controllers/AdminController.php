<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Admin; 
use App\Models\User; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Mail\ForgotPasswordOtpMail;
use Illuminate\Support\Facades\Mail;
use Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;  


class AdminController extends Controller
{
    public function superLogin()
    { 
        if(Auth()->guard('web')->check()){
          return redirect('/user-dashboard');
        }else if(Auth()->guard('roleuser')->check()){
          return redirect('role-user/dashboard');
        }else if(Auth()->guard('superadmin')->check()){
          return redirect('super-dashboard');
        }
        return view('super-login');        
    }
 
     public function postlogin(Request $request)
        {   
           $data = $request->validate([ 
            'email' => 'required|max:255', 
            'password' => 'required|max:255',
            ]);     
            $admin=Admin::where('email','=',$request->email)->first();
            if(!$admin)
            {
              return redirect('admin-login')->withErrors(['email' => trans('Sorry,Email does not Exist')]); 
            } 
            if(Auth::guard('superadmin')->attempt(['email'=> $request->email,'password' => $request->password])){ 
             return redirect('super-dashboard')->with('You Have Succssfully admin logged In..');   
            }else{
              return redirect('admin-login')->withErrors(['password' => trans('Invalid Password')]);   
            }
            return redirect('admin-login')->with('You have entered invalid admin logged In..');
        }  
 

        public function forgotpassword(Request $request)
        { 
           return view('reset-password.forgot-password');        
        }
        public function otpConfirm(Request $request)
        { 
           return view('reset-password.otp-confirm');        
        }


public function forgotPasswordOtpPost(Request $request)
{  
   $request->validate([ 
    'email' => 'required',  
   ]);   
   $otp = rand(1000,9999);
   Log::info("otp = ".$otp);  
   $admin_count = Admin::where('email','=',$request->email)->update(['email_otp' => $otp]); 
   $admin = Admin::where('email','=',$request->email)->first(); 
   if($admin_count>0){   
    $data = [
        'title' => 'Welcome to the KaizenHub web Site '. $admin->name,  
        'body' => ' You have requested to reset your password in "Kaizen Hub".Use this OTP ' .$otp
    ];
     \Mail::to($request->email)->send(new ForgotPasswordOtpMail($data));
     return view('reset-password.otp-confirm',compact('admin'))->with('Check Your Email OTP Number..');    
}
else{
     return redirect('forgotpassword')->withErrors(['email' => trans('Sorry,Email does not Exist')]);
   }   

}  

   public function confirmPasswordPost(Request $request)
  {       
         
     if($request->otp =='' && $request->otp==null){
       $admin = Admin::where('email','=',$request->email)->first(); 
       return view('reset-password.otp-confirm',compact('admin'))->withErrors(['otp' => trans('The OTP field is required')]); 
     }   
      $admin  = Admin::where([['email','=',$request->email],['email_otp','=',$request->otp]])->first();
      if($admin){ 
        Admin::where('email','=',$request->email)->update(['otp_is_verified'=>'1']);   
        return view('reset-password.reset-pwd',compact('admin'));
      }
      else{ 
        $admin = Admin::where('email','=',$request->email)->first(); 
        return view('reset-password.otp-confirm',compact('admin'))->withErrors(['otp' => trans('Your OTP could not be match')]);
      }   
  }
public function resetPasswordPost(Request $request)
{    

    $admin = Admin::where('email','=',$request->email)->first();
    if($request->password ==null || $request->password ==''){
      return view('reset-password.reset-pwd',compact('admin'))->withErrors(['password' => trans('The password field is required')]); 
    }
    if($request->password_confirmation =='' || $request->password_confirmation ==null){
      return view('reset-password.reset-pwd',compact('admin'))->withErrors(['password_confirmation' => trans('The confirm password field is required')]); 
    }
   if($request->password == $request->password_confirmation){
        if(strlen($request->password) < 8 || (!preg_match("#[0-9]+#",$request->password)) || (!preg_match("#[A-Z]+#",$request->password)) || (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $request->password))){            
           return view('reset-password.reset-pwd',compact('admin'))->withErrors(['password' => trans(' Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.')]);
          } 
      }
      else{          
       return view('reset-password.reset-pwd',compact('admin'))->withErrors(['password_confirmation' => trans('The password and confirm password dose not match')]);     
      } 
    $admin  = Admin::where([['email','=',$request->email],['email_otp','=',$request->otp]])->first(); 
    if($admin){ 
        Admin::where('email','=',$request->email)->update(['password'=>Hash::make($request->password)]); return redirect('admin-login')->withSuccess('Password Created successfully...');
    }   

} 

}
