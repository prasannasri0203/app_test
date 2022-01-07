<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;  
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use App\Models\Superadmin\Coupon;
use App\Models\Superadmin\Subscription;
use App\Models\Superadmin\Renewal_details;
use Session;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use Stripe;
use Mail;
use App\Mail\UserPlanMail;
use App\Models\State;
use App\Models\StripRenwalRecords;
use App\Http\Traits\UserUpgrade;

class UsersController extends Controller{
    use UserUpgrade;
    public function index($id=null){
        $sid ='';
        if($id){
            Auth::logout();
            if($id == 1){
                $sid ='Your admin renewal has been expired, please contact admin!';
            }else if($id == 2){
                $sid ='Your admin role is not valid, please contact admin!';
            }else if($id == 3){
                $sid ='You have changed plan as enterpriser successfully. Your enterprise account request needs to be approved by our team, after the account gets approved, you can able to log in!';
            }
            return view('user-login',['sid'=>$sid]);
        }else{
            if(Auth()->guard('web')->check()){
                return redirect('/user-dashboard');
            }else if(Auth()->guard('roleuser')->check()){
                return redirect('role-user/dashboard');
            }else if(Auth()->guard('superadmin')->check()){
              return redirect('super-dashboard');
            }
            return view('user-login',['sid'=>$sid]);
        }
        
    }
    public function register(){
        return view('user-registration.user-register');
    }
    public function postRegister(UserRegistrationRequest $request){
        //dd($request->all());
        $userExist =0;        
        $chkMobile = UserDetail::where('contact_no',$request->contact_no)->first();
        if($chkMobile){ 
            $chk = User::where('id',$chkMobile->user_id)->first(); 
            if($chk){
                if($chk['parent_id'] != 0){
                    return redirect('user-register')->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
                }
                $chkRenewal = Renewal_details::where('user_id',$chk->id)->where('is_activate','!=','0')->where('status','!=','0')->get();
                if(count($chkRenewal) > 0){
                    return redirect('user-register')->withInput()->withErrors(['contact_no' => trans('The Contact No has already been taken.')]);
                }
            }
        }
        $chkEmail = User::where('email',$request->email)->first();
        if($chkEmail){
            // $chkExistRenewal = Renewal_details::where('user_id',$chkEmail->id)->where('is_activate','0')->where('status','0')->get();
            // if(count($chkExistRenewal) > 0){
                $userExist =1;
                $userInsert = User::find($chkEmail->id);
            // }
        }else{
            $userInsert = new User();
        }
        
        $userInsert->name = $request->user_name;
        $userInsert->email = $request->email;
        $userInsert->password = Hash::make($request->password);
        $userInsert->status = '0';
        $userInsert->is_own = '1';
        $userInsert->save();
        $user_id= $userInsert->id;
        if($userExist == 0){
            $userDetail = new UserDetail();
        }else{
            $userExistDetail = UserDetail::where('user_id',$user_id)->first();
            $userDetail = UserDetail::find($userExistDetail->id);
        }
        $userDetail->user_id = $user_id;
        $userDetail->contact_no = $request->contact_no;
        $userDetail->organization_name = $request->organization_name;
        $userDetail->save();
        if($user_id){           
            $user = User::find($user_id);
            $plans = Subscription::where('display_in_site',1)->where('status',1)->latest()->get();
            return view('user-registration.user-plan')->with(['user'=>$user,'plans'=>$plans]);
        }
    }
    public function updatePlan($id){
        $user = User::with('planDetail')->find($id);
        $chkRenewal = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();
        if($chkRenewal){
            return redirect('/');
        }else{
            if($user->user_role_id == '4'){
                return redirect('/');
            }
            $plans = Subscription::where('display_in_site',1)->where('status',1)->latest()->get();
            return view('user-registration.user-plan')->with(['user'=>$user,'plans'=>$plans]);
        }
    }
    public function postSubscription(Request $request){
        $user = User::find($request->user_id);
        $subscriptionDetail = Subscription::find($request->plan);
        $currentDate = date('Y-m-d');
        if($subscriptionDetail){
            if($subscriptionDetail->payment_type=='yearly'){
                $renewaldate =  date('Y-m-d', strtotime("365 days +1 day"));
            } 
            else if($subscriptionDetail->payment_type=='monthly') $renewaldate =  date('Y-m-d', strtotime("30 days +1 day"));
            else   $renewaldate =  date('Y-m-d', strtotime($subscriptionDetail->activation_period." days +1 day"));
        }
        $userRenewal = Renewal_details::where('user_id',$request->user_id)->get();
        if(count($userRenewal) > 0){
            Renewal_details::where('user_id',$request->user_id)->delete();
        }
        // if($user->plan_id == 0 && !$userRenewal){
            $renewalDetail = new Renewal_details();
            $renewalDetail->user_id         =   $request->user_id;
            if($request->user_role_id != '2' && $request->user_role_id != '4'){
                $renewalDetail->amount      =   $subscriptionDetail->amount;
            }else{
                $renewalDetail->amount      = 0;
            }
            if($request->user_role_id == '4'){
                $renewalDetail->is_activate    =   '1'; 
                $renewalDetail->status         =   '1'; 
            }
            if($request->user_role_id != '4'){
                $renewalDetail->plan_id         =   $request->plan; 
                $renewalDetail->renewal_date    =   $renewaldate; 
            }else{
                $renewalDetail->plan_id         =   0; 
                $renewalDetail->renewal_date    =   $currentDate; 
            }       
            $renewalDetail->save();
        // }
        if($request->user_role_id == '4'){
            $user->team_count = $request->team_count;
            $user->status         =   '1';
        }
        $user->plan_id = $request->plan;
        $user->user_role_id = $request->user_role_id;
        $user->save();
        if($request->user_role_id == '4'){
            $emailcontent['role']   =   '4';
            $emailcontent['date']   =   '';
            $emailcontent['name']      =   $user['name'];
            $emailcontent['plan']      =   '';
            $emailcontent['plan_type']      =   '';
            $emailcontent['amt']   =   '';
            \Mail::to($user['email'])->send(new UserPlanMail($emailcontent));
            $userUpdate=$this->userRegistration($user['id'],$user['name'],'4',0,'1','0');
        }
        return response()->json(['success' => '1', 'message' => 'Plan updated']);
    }
    public function planPreview($id){
        $user = User::find($id);
        $planDetail = Subscription::find($user->plan_id);
        $state = State::get();
        $chkRenewal = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();
        if($chkRenewal){
            return redirect('/');
        }else{
            return view('user-registration.subscription-plan-preview')->with(['user'=>$user,'plan'=>$planDetail,'states'=>$state]);
        }
    }
    public function couponApply(Request $request){
        $coupon = Coupon::where('coupon_code',$request->code)->where('status',1)->first();
        $planDetail = Subscription::find($request->user_plan_id);
        $couponAmt =0;
        if($coupon){
            $currentDate = date('Y-m-d');
            $begin = new DateTime($coupon['start_date']);
            $end = new DateTime($coupon['end_date']);
            $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
            $alldates=[];
            foreach($daterange as $datel){
                $alldates[] = $datel->format("Y-m-d");
            }
            $alldates[] = $coupon['end_date'];
            if(date('Y-m-d',strtotime($coupon['start_date'])) > $currentDate){
                return response()->json(['status' => '3','plan_amt'=>$request->total_amt]);
            }
            if(!in_array($currentDate, $alldates)){
                return response()->json(['status' => '2','plan_amt'=>$request->total_amt]);
            }
            if($coupon->amount_type == 'price'){
                $couponAmt = $coupon->price;
                if($couponAmt > $request->total_amt){
                    return response()->json(['status' => '4','plan_amt'=>$request->total_amt]);
                }
            }else{
                // $couponAmt = ceil($planDetail->amount*$coupon->discount/100);
                $couponAmt = $coupon->discount;
            }
            $totalAmt = ceil($planDetail->amount-$couponAmt);
            return response()->json(['status' => '1','coupon_amt'=>$couponAmt,'coupon_id'=>$coupon->id,'total_amt'=>$totalAmt,'discount'=>$coupon->discount,'plan_amt'=>$request->total_amt,'type'=>$coupon->amount_type]);
        }else{
            return response()->json(['status' => '0','plan_amt'=>$request->total_amt]);
        }
    }
    public function makePayment(Request $request){
        $updateTeam =0;
        if($request->user_role_id == 1 || $request->user_role_id == 3){//team/individual
            try {
                $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
                // $stripe = new Stripe\StripeClient('sk_test_Fl47TIOHWr8jSDdBbVRtp3dj00hCrmxtjy');
                // Stripe\Stripe::setApiKey('sk_test_Fl47TIOHWr8jSDdBbVRtp3dj00hCrmxtjy');
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                
                $orderID = "U".$request->user_id."C".$request->coupon_id."P".$request->user_plan_id."T".$request->tax_percentage;

                $subscription = Subscription::where('user_role_id',$request->user_role_id)->first();

                $user_detail = User::where('id',$request->user_id)->first();

                if(isset($request->state) && $request->state !=''){                
                    DB::table('users')->where("id",$request->user_id)->update(array("location_id"=>$request->state));
                }

                if(isset($subscription->plan_name) && $subscription->plan_name !=''){
                  
                   $product = \Stripe\Product::create([
                    'name' => $subscription->plan_name,
                    'type' => 'service',
                    'metadata'      => [
                         'order_id'  => $orderID
                        ]
                   ]);
                }
                
                if(isset($product->id) && $product->id != ''){

                    if ($subscription->payment_type == 'yearly') {
                        $interval ='year';
                    }else if($subscription->payment_type == 'monthly'){
                        $interval ='month';
                    }else if($subscription->payment_type == 'day'){
                        $interval ='day';
                    }
                    
                    $price = \Stripe\Price::create([
                      'product' => $product->id,
                      'unit_amount' =>($request->plan_amt*100),
                      'currency' => 'inr',
                      'recurring' => [
                            'interval' => $interval,
                      ],
                      'metadata'      => [
                         'order_id'  => $orderID
                      ]
                      ]);
                }
                $state_data = State::where('id',$request->state)->first();

                if(isset($state_data->states_name) && $state_data->states_name !='' && $request->tax_percentage !=''){
                    
                    $tax_value = $stripe->taxRates->create([
                      'display_name' => $state_data->states_name,
                      'description' => 'state Percentage',
                      // 'jurisdiction' => 'DE',
                      'percentage' => $request->tax_percentage,
                      'inclusive' => false,
                      'metadata'      => [
                         'order_id'  => $orderID
                        ]
                    ]);
                }

                if(isset($state_data->states_name) && $state_data->states_name !=''){

                    $customer = \Stripe\Customer::create([
                        'name'      => $request->username,
                        'email' => $user_detail->email,
                        'source'    => $request->stripetoken,
                        'address'   => [
                        'line1'       => $request->line1,
                        'postal_code' => $request->postal_code,
                        'city'        => $request->city,
                        'state'       => $state_data->states_name,
                        'country'     => $request->country,
                        ],
                    ]);
                }

                if(isset($create_coupon->id) && $create_coupon->id !=''){
                    
                    $subscriptions = \Stripe\Subscription::create([
                       'customer' => $customer->id,
                       'items' => [['price' => $price->id]],
                       // 'coupon' => $create_coupon->id,
                       'default_tax_rates' =>[$tax_value->id],
                       'metadata'      => [
                         'order_id'  => $orderID
                        ]
                        
                    ]);
                    $coupon_id = $create_coupon->id;
                }else{

                    $subscriptions = \Stripe\Subscription::create([
                       'customer' => $customer->id,
                       'items' => [['price' => $price->id]],
                       // 'coupon' => $create_coupon->id,
                       'default_tax_rates' =>[$tax_value->id],
                       'metadata'      => [
                         'order_id'  => $orderID
                        ]
                    ]);
                    $coupon_id = null;
                }
                $charge = \Stripe\Charge::create([
                    'customer'      => $customer->id,
                    'receipt_email' => $user_detail->email,
                    // 'amount'        => ($request->total_amt*100),
                    'amount'        => ($request->total_amt*100),
                    'currency'      => 'INR',
                    'description'   => "This payment is tested purpose",
                    'metadata'      => [
                        'order_id'  => $orderID
                    ]
                ]);

                $stripeRenewalRecords = new StripRenwalRecords();
                $stripeRenewalRecords->user_plan_id = $request->user_plan_id;
                $stripeRenewalRecords->user_id = $request->user_id;
                $stripeRenewalRecords->user_role_id = $request->user_role_id;
                $stripeRenewalRecords->stripe_product_id =$product->id;
                $stripeRenewalRecords->stripe_price_id = $price->id;
                // $stripeRenewalRecords->stripe_coupon_id = $coupon_id;s
                $stripeRenewalRecords->stripe_tax_id = $tax_value->id;
                $stripeRenewalRecords->stripe_customer_id = $customer->id;
                $stripeRenewalRecords->stripe_charge_id = $charge->id;
                $stripeRenewalRecords->stripe_subcription_id =$subscriptions->id; 
                $stripeRenewalRecords->stripe_payment_collection_status = 1;
                $stripeRenewalRecords->save();

                $paymenyResponse = $charge->jsonSerialize();
                
                if($paymenyResponse['amount_refunded'] == 0 && empty($paymenyResponse['failure_code']) && ($paymenyResponse['paid'] == 1 ||$paymenyResponse['paid'] == true) && ($paymenyResponse['captured'] == 1 || $paymenyResponse['captured'] == true)){
                    $updateTeam =1;
                }
            } catch (\Exception $ex) {
                return response()->json(['status' => '2','msg'=>$ex->getMessage()]);
            }
        }
        $userRenewal = Renewal_details::where('user_id',$request->user_id)->where('is_activate',0)->where('status',0)->first();
        
        if($userRenewal){
            $user = User::with('planDetail')->find($request->user_id);
            if($user->user_role_id == '2' || $updateTeam == '1'){
                $user->status = '1';
                $user->save();
            }

            $update = Renewal_details::find($userRenewal->id);
            if($user->user_role_id != '2'){
                $update->amount     = $request->total_amt;
            }else{
                $update->amount     = 0;
            }
            if($user->user_role_id == '2' || $updateTeam == '1'){
                $update->is_activate    =   '1'; 
                $update->status         =   '1'; 
                if($user->user_role_id != '2'){
                    $update->payment_type   =   '3';
                }
            } 
            $update->coupon_id      =   $request->coupon_id;        
            $update->save();

            $emailcontent['role']             =   $user['user_role_id'];
            $emailcontent['date']             =   $update->renewal_date;
            $emailcontent['name']             =   $user['name'];
            $emailcontent['plan']             =   $user['planDetail']['plan_name'];
            $emailcontent['plan_type']        =   $user['planDetail']['payment_type'];
            $emailcontent['activation_period']=$user['planDetail']['activation_period'];
            $emailcontent['amt']   =   $update->amount;
            \Mail::to($user['email'])->send(new UserPlanMail($emailcontent));
            $userUpdate=$this->userRegistration($user['id'],$user['name'],$user['user_role_id'],$user['planDetail']['plan_name'],'1',$update->amount);
            return response()->json(['status' => '1']);
        }else{
            return response()->json(['status' => '0']);
        }
    }
    public function successPayment($id){
        $user = User::find($id);
        // $chkRenewal = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();
        // if($chkRenewal){
        //     return redirect('/');
        // }else{
        if($user){
            return view('user-registration.success-payment')->with(['user'=>$user]);
        }else{
            return redirect('user-login');
        }
        // }
    }

    public function LoginUser(Request $request){

        $request->validate([ 
            'email' => 'required|email|max:255', 
            'password' => 'required|max:255',
        ]); 

        $useremail      =   User::where('email',$request->email)->where('parent_id',0)->get();
        if(count($useremail)==0){
          $team =  User::where('email',$request->email)->whereNotNull('parent_id')->where('user_role_id',1)->first();
          if($team){            
            $useremail      =   User::where('id',$team['parent_id'])->get();
            if(count($useremail) > 0){
                if($useremail[0]['user_role_id'] != 4){
                    return redirect('user-login')->with('status','Please check your role url!');
                }
            }else{
                return redirect('user-login')->with('status','Your parent is not available!');
            }
          }else{
            return redirect('user-login')->withInput()->withErrors(['email' => trans('Invalid Email-ID')]);
          }
        }
        // dd($useremail);
        if(count($useremail)==0){
          return redirect('user-login')->withInput()->withErrors(['email' => trans('Invalid Email-ID')]);
        }
        if(Auth::guard('web')->attempt(['email'=> $request->email,'password' => $request->password])){
            if($useremail[0]->user_role_id==2){//trial
                $status =   $this->Validatetrailuser($useremail[0]['plan_id'],$useremail[0]->id);
            }else if($useremail[0]->user_role_id==4){//enterpriser
                if($useremail[0]->is_approved != 1){
                    Session::flush();
                    Auth::guard('web')->logout();
                    return redirect('user-login')->with('status','Your enterpriser request has not been approved yet!');  
                }else{
                   $status =   $this->Validateotherusers($useremail[0]['plan_id'],$useremail[0]->id);
                }
            }else{
                $status =   $this->Validateotherusers($useremail[0]['plan_id'],$useremail[0]->id);
            }
            // // if($status=='Expired'){
            // //     User::where('email',$request->email)->update(['status'=>0]);
            // // }
            $currentTime = date('Y-m-d h:i:s');
            
            if(Auth::user()->user_role_id == 1 && Auth::user()->parent_id != 0){//team user of enterpriser
                $chkParent = User::find($useremail[0]->id);
                if($chkParent->user_role_id != 4){
                    Session::flush();
                    Auth::guard('web')->logout();
                    return redirect('user-login')->with('status','Your admin role is not valid, please contact admin!'); 
                }
                if($status=='Expired'){
                    Session::flush();
                    Auth::guard('web')->logout();
                    return redirect('user-login')->with('status','Your admin renewal has been expired, please contact admin!'); 
                }else if($status=='Not a valid user'){
                    Session::flush();
                    Auth::guard('web')->logout();
                   return redirect('user-login')->with('status','Your owner is not a valid user!');   
                }
            }
            if($status=='Expired'){
                return redirect('plan-setting/2'); 
                // return redirect('user-login')->with('status','Your plan has been expired. Please change or upgrade your plan!');
            }else if($status=='Not a valid user'){
                Session::flush();
                Auth::guard('web')->logout();
               return redirect('user-login')->with('status','You are not a valid user!');   
            }else{
                $chkUserActive = User::where('email',$request->email)->where('status',1)->first();
                if($chkUserActive){
                    User::where('email',$request->email)->update(['login_at'=>$currentTime]);
                    return redirect('user-dashboard');
                }else{
                   Session::flush();
                   Auth::guard('web')->logout(); 
                   return redirect('user-login')->with('status','You cannot access, please contact admin!');   
                }
            } 

        }else{
            return redirect('user-login')->withInput()->withErrors(['password' => trans('Invalid Password')]);   
        }
    }
    public function Userlogout($id=null){ 
        Session::flush();
        Auth::logout();        
        if($id){
            return redirect('/user-login/'.$id);
            // if($id == 1){
            //     return redirect('/user-login/1')->with('status','Your admin renewal has been expired, please contact admin!');
            // }else if($id == 2){
            //     return redirect('/')->with('status','Your admin role is not valid, please contact admin!');
            // }else if($id == 3){
            //     return redirect('/')->with('success','You have changed plan as enterpriser successfully. Your enterprise account request needs to be approved by our team, after the account gets approved, you can able to log in!');
            // }
        }else{
            return redirect('user-login');
        }    
    }

    public function Validatetrailuser($planid,$user_id){
        $plandetails    = Renewal_details::where('user_id',$user_id)->where('plan_id',$planid)->where('is_activate',1)->where('status',1)->latest()->first();
        $currentTime = date('Y-m-d');
        if($plandetails){
            if($currentTime >= date('Y-m-d',strtotime($plandetails->renewal_date))){
                return "Expired";
            }else{
                return "Not Expired";
            }
        }else{
            return "Not a valid user";
        }
    }

    public function Validateotherusers($planid,$user_id){
        $plandetails      =   DB::table('renewal_details')->where('user_id',$user_id)->where('plan_id',$planid)->where('is_activate',1)->where('status',1)->latest()->first();
        // dd($plandetails,$planid,$user_id);
        $currentTime      =   date('Y-m-d');
        if($plandetails){
            if($currentTime >= date('Y-m-d',strtotime($plandetails->renewal_date))){
                return "Expired";
            }else{
                return "Not Expired";
            }
        }else{
            return "Not a valid user";
        }
    }

    public function editprofile(){
        $Module['module'] = 'account-setting';
        $userdetails    =   DB::table('users')->join('user_details','user_details.user_id','users.id')->select('users.id','users.name','users.email','user_details.organization_name','user_details.contact_no','user_details.address','user_details.city','user_details.province','user_details.postal_code','users.image')->where('users.id',Auth::user()->id)->get();
        return view('frontend.editprofile',['userdetails'=>$userdetails,'Module'=>$Module]);
    }

    public function edituserupdate(UserEditRequest $request){
        $url    =   url('/account-setting');
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
            $userDetailupdate->organization_name    =   $request->organization_name;
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
        //         $status    =  "Profile Updated Successfully";
        //          return redirect($url)->with('status','Profile Updated Successfully');
        //     }else{
        //         return redirect($url)->withErrors(['current_password' => trans('The Old Password Not Match')]);
        //     }
        // }elseif($request->password!='' && $request->current_password=='' ){
        //       return redirect($url)->withErrors(['current_password' => trans('The old password field is required.')]);
        // }


        
        return redirect($url)->with('status','Profile Updated Successfully');
    }
    
    
}