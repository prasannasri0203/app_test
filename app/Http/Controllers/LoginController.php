<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\User;   
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Mail\ForgotPasswordOtpMail;
use Illuminate\Support\Facades\Mail;
use Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;  

class LoginController extends Controller
{ 
   	public function forgotPassword(Request $request)
   	{
   		return view('front_reset_pwd.forgot-pwd'); 
    }

    public function forgotPasswordOtpPost(Request $request)
	{  
	   $request->validate([ 
	    'email' => 'required|email',  
	   ]);   
	   // dd($request->email);die;
	   $otp = rand(1000,9999);
	   Log::info("otp = ".$otp);  
	   $user_count = User::where('email','=',$request->email)->update(['otp' => $otp,'otp_is_verified'=>0]); 
	   $user = User::where('email','=',$request->email)->first(); 
	   if($user_count>0){   
			$data = [
				'title' => 'Hi '. $user->name,  
				'body' => 'Please use this OTP "'.$otp.'" to reset your password.'
			];
			\Mail::to($request->email)->send(new ForgotPasswordOtpMail($data));
			//return view('front_reset_pwd.otp-verify',compact('user'))->with('Check Your Email OTP Number..');    
			return redirect()->route('otp-view', ['email' => $user->email]);
		}else{
		    return redirect('forgot-password')->withErrors(['email' => trans('Sorry,Email does not Exist')]);
		}   

	} 

	public function Otpviewpage(){
		
		return view('front_reset_pwd.otp-verify',['email'=>$_GET['email']]);
	}


	public function confirmOtpPost(Request $request)
	{  
		
		$request->validate([ 
			'otp' => 'required'
		]);

		$user  = User::where([['email','=',trim($request->email)],['otp','=',trim($request->otp)]])->first();
	
		if($user!=''){ 
			
			User::where('email','=',$request->email)->update(['otp_is_verified'=>'1']);   
			return view('front_reset_pwd.reset-pwd',compact('user'));
		}
		else{ 
		
			User::where('email','=',$request->email)->update(['otp_is_verified'=>'0','otp' => NULL]); 
			return redirect()->route('forgot-password')->withErrors(['email' => trans('Sorry! Your OTP is wrong..')]);
		}    
	}

	public function ResetPassword(Request $request){

		if($request->password == '' && $request->password_confirmation==''){
			$user = User::where('email','=',$request->email)->first(); 
			return view('front_reset_pwd.reset-pwd',compact('user'))->withErrors(['password' => trans('Password is required'),'password_confirmation' => trans('Confirm Password is required')]);
		}
		else if($request->password == ''){
			$user = User::where('email','=',$request->email)->first(); 
			return view('front_reset_pwd.reset-pwd',compact('user'))->withErrors(['password' => trans('Password is reqiured')]);
		}else if($request->password_confirmation == ''){
			$user = User::where('email','=',$request->email)->first(); 
			return view('front_reset_pwd.reset-pwd',compact('user'))->withErrors(['password_confirmation' => trans('Confirm Password is required')]);
		}

		if($request->password != $request->password_confirmation){
			$user = User::where('email','=',$request->email)->first(); 
			return view('front_reset_pwd.reset-pwd',compact('user'))->withErrors(['password_confirmation' => trans('Confirm password is not matching')]);
		}else{
			if(strlen($request->password) < 8 || (!preg_match("#[0-9]+#",$request->password)) || (!preg_match("#[A-Z]+#",$request->password)) || (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $request->password))){
				$user = User::where('email','=',$request->email)->first(); 
				return view('front_reset_pwd.reset-pwd',compact('user'))->withErrors(['password' => trans('The Password should be at least 8 characters in length and should include at least 1 upper case letter, 1 number, and 1 special character')]);
			}
		}

		$user  = User::where([['email','=',$request->email],['otp','=',$request->otp]])->first(); 
		if($user){ 
			User::where('email','=',$request->email)->update(['password'=>Hash::make($request->password)]); 

			if($user->parent_id)
			return redirect('role-user-login')->withSuccess('Password Reset Successfully...');
			else
			return redirect('user-login')->withSuccess('Password Reset Successfully...');
		}   
	}

	public function ResendOtp($email){
		$otp = rand(1000,9999);
		Log::info("otp = ".$otp);  
		$user_count = User::where('email','=',$email)->update(['otp' => $otp,'otp_is_verified'=>0]); 
		$user = User::where('email','=',$email)->first(); 
		if($user_count>0){   
			 $data = [
				 'title' => 'Welcome to the KaizenHub web Site '. $user->name,  
				 'body' => 'Your registered email-id is'.$user->email .'your reset password OTP ' .$otp
			 ];
 
			 \Mail::to($email)->send(new ForgotPasswordOtpMail($data));
			 //return view('front_reset_pwd.otp-verify',compact('user'))->with('Check Your Email OTP Number..');    
			 return redirect()->route('otp-view', ['email' => $user->email])->withSuccess('Otp Resend Successfully to your Email');
		 }else{
			 return redirect('forgot-password')->withErrors(['email' => trans('Sorry,Email does not Exist')]);
		 }   
 
	}





}
