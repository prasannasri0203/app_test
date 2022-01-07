<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Superadmin\Subscription;
use App\Models\Superadmin\Individualuser;
use App\Models\Superadmin\Renewal_details;
use Auth;
use DB;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Superadmin\Coupon;
use DateTime;
use DatePeriod;
use DateInterval;
use Stripe;
use Session;
use Mail;
use App\Mail\UserUpgardeMail;
use App\Models\Superadmin\Tax;
use App\Models\State;
use App\Models\UserTemplate;
use App\Models\FlowchartProject;
use App\Models\Comment;
use App\Models\UserTemplateTrack;
use App\Models\TemplateShare;
use App\Models\DegradedSubUser;
use App\Models\StripRenwalRecords;
use App\Http\Traits\UserUpgrade;

class PlanController extends Controller{

    use Sortable;
    use UserUpgrade;
    public function planHistoryList(Request $request,$id=null){
        $Module['module']   =   'account-setting';
        $upgradeBtn =0;
        $planstatus=0;
        if($id){
            if($id != 2){
                $planstatus =1;
            }else{
                $planstatus =2;
            }
        }
        $user_id=Auth::user()->id;
        $userid = User::find($user_id); 
        $role_name=UserRole::find($userid->user_role_id);
        $rolename=$role_name->role;
        $renewal_list = Renewal_details::where('user_id',$user_id)->where('is_activate','1')->where('status',1)->first(); 
        $plans=Subscription::Where('id',$renewal_list->plan_id)->first();
        $planname=$plans->plan_name;  
        $currentDate = date('Y-m-d');
        if(date('Y-m-d',strtotime($renewal_list['renewal_date'])) <= $currentDate){
            $upgradeBtn =1;
        }
        $user_plans =  User::join('user_details','users.id','user_details.user_id')                   
                    ->join('renewal_details','users.id','renewal_details.user_id')  
                    ->join('super_admin_subscription_plan','renewal_details.plan_id','super_admin_subscription_plan.id') 
                     ->whereIn('renewal_details.is_activate',['0','1'])->where('renewal_details.user_id',$user_id)->where('renewal_details.status',1)                  
                    ->select('users.*','renewal_details.amount as renewal_amt','renewal_details.coupon_id as renewal_coupon_id','renewal_details.status as renewal_status','renewal_details.created_at as renewal_updated_at','renewal_details.updated_at as renewal_updated_date','renewal_details.renewal_date as renewaldate','renewal_details.is_activate as renewal_is_activate','renewal_details.payment_type as renewal_paytype','renewal_details.plan_id as renewal_plan_id','user_details.*','super_admin_subscription_plan.*') ;

        $user_plans=$user_plans->sortable()->orderBy('renewal_details.id', 'ASC')->paginate(10);
                $stripe_payment_collection = StripRenwalRecords::where('user_id',$user_id)->whereNull('deleted_at')->first();

        return view('frontend.plan.planlist',compact('user_plans','rolename','planname','upgradeBtn','planstatus','user_id','stripe_payment_collection'),['Module'=>$Module]);
    }

    public function Changeplan(){
        $Module['module']   =   'account-setting';
        $user = User::with('planDetail','userRenewalDatail')->find(Auth::user()->id);
        $planval = 'upgrade';
        if(date('Y-m-d') >= date('Y-m-d',strtotime($user['userRenewalDatail']['renewal_date']))){
            $planval = 'register';
        }
        $planlist   =   Subscription::where('user_role_id','!=',2)->where('display_in_site',1)->where('status',1)->get();
        return view('frontend.plan.planpage',['plandetails'=>$planlist,'Module'=>$Module,'user'=>$user,'planval'=>$planval]);
    }

    public function updateplan(Request $request){
        date_default_timezone_set('Asia/Kolkata');
        $userdetails    =   User::with('planDetail')->where('id',$request->user_id)->get();
        if($userdetails[0]->user_role_id == '4' && $request->user_role_id == '4'){
            $plan_id = $userdetails[0]->plan_id;
        }else{
            $plan_id = $request->user_plan_id;
        }
        $plantype       =   Subscription::where('id',$plan_id)->get();
        $nextrenewal = date('Y-m-d');
        $amt=0;
        // User::where('id',$request->user_id)->update(['status'=>1]);
        if(count($plantype) > 0){
            $planbase       =   $plantype[0]->payment_type;        
            if($planbase=='yearly') $nextrenewal   = date('Y-m-d', strtotime("365 days +1 day"));
            else if($planbase=='monthly') $nextrenewal   =  date('Y-m-d', strtotime("30 days +1 day"));
            $amt=$plantype[0]->amount;
        }
        $Updatedstatus  =   'Not Updated'; 
        if($request->planuser != 1){//team,individual
            Session::put('sub_role_id', $request->user_role_id);
            Session::put('sub_plan_id', $request->user_plan_id);
            $Updatedstatus  =   'Updated';
            return $Updatedstatus;
        }else{//Enterpriser
            if($userdetails[0]->user_role_id==$request->user_role_id){//same user role
                // if($userdetails[0]->plan_id==$request->user_plan_id){//same plan
                    // if($request->user_role_id == '4'){
                        User::where('id',$request->user_id)->update(['team_count'=>$request->team_count]);
                        $renewalinsert =   $this->updatePlanDetails($request->user_id,$plan_id,$nextrenewal,$request->user_role_id,$amt);
                        // Renewal_details::where('user_id',$request->user_id)->where('status',1)->where('is_activate',1)->where('plan_id',$plan_id)->update(['renewal_date'=>$nextrenewal,'amount'=>$amt]);
                        $userUpdate=$this->userRegistration($request->user_id,$userdetails[0]->name,'4',0,'3','0');
                        $Updatedstatus  =   'Updated';
                    /*}else{
                        Renewal_details::where('user_id',$request->user_id)->where('plan_id',$request->user_plan_id)->where('is_activate',1)->update(['renewal_date'=>$nextrenewal,'is_activate'=>'0','status'=>'0','amount'=>$amt]);
                    }
                    $Updatedstatus  =   'Updated';

                }else{
                //other plan 
                    $renewalinsert =   $this->updatePlanDetails($request->user_id,$request->user_plan_id,$nextrenewal,$request->user_role_id,$amt);
                    $Updatedstatus  =   'Updated';
                // }*/
                 
                
            }else{//new plan subscribed
                $renewalinsert =   $this->updatePlanDetails($request->user_id,$request->user_plan_id,$nextrenewal,$request->user_role_id,$amt);
                User::where('id',$request->user_id)->update(['plan_id'=>$request->user_plan_id,'user_role_id'=>$request->user_role_id,'team_count'=>$request->team_count]);
                $userUpdate=$this->userRegistration($request->user_id,$userdetails[0]->name,'4',0,'2','0');
                $Updatedstatus  =   'Updated';
            }
        }
        return $Updatedstatus;
    }
    public function getTax(Request $request){
        $getTaxes =Tax::select('taxes.*','states.states_name')->join('states', 'taxes.state_id', '=', 'states.id')->where('state_id',$request->state_id)->where('taxes.status',1)->get();
            
        if(count($getTaxes) > 0){
            return $getTaxes;
        }else{
            return 0;
        }
        
    }

    public function updatePlanDetails($user_id,$plan_id,$renewaldate,$role_id,$amount){
        Renewal_details::where('user_id',$user_id)->where('is_activate',1)->where('status',1)->update(['is_activate'=>0]); 
        $deleteplanexist =  Renewal_details::where('user_id',$user_id)->where('is_activate',0)->where('status',0)->delete();      
        $renewalinsert                  =   new Renewal_details();
        $renewalinsert->user_id         =   $user_id;
        $renewalinsert->plan_id         =   $plan_id;
        if($role_id == '4'){
            $renewalinsert->is_activate    =   '1'; 
            $renewalinsert->status         =   '1'; 
        }
        $renewalinsert->renewal_date    =   $renewaldate;
        $renewalinsert->amount          =   $amount;                  
        $renewalinsert->save();
        $user = User::find($user_id);
        Session::put('old_user_role_id', $user['user_role_id']);   
        if($role_id == '4'){//enterpriser
            $planDetail = Subscription::find($plan_id);            
            if($user['user_role_id'] != 4){
                $emailcontent['new']   =   '1';
            }else{
                $emailcontent['new']   =   '2';
            }
            $emailcontent['date']   =   $renewaldate;
            $emailcontent['name']      =   $user['name'];
            if($planDetail){
                $emailcontent['plan']      =   $planDetail['plan_name'];
                $emailcontent['plan_type'] =   $planDetail['payment_type'];
            }else{
                $emailcontent['plan']      =  '';
                $emailcontent['plan_type'] =  '';
            }
            $emailcontent['amt']   =   $amount;
            \Mail::to($user['email'])->send(new UserUpgardeMail($emailcontent));
            if($user['user_role_id'] != 4){
                $this->changeUserAccount($role_id);
            }
        }
    }
    public function planPreview($id){
        $Module['module']   =   'account-setting';
        $user = User::find($id);
        $plan_id = Session::get('sub_plan_id');
        $user_role_id=Session::get('sub_role_id');
        $planDetail = Subscription::find($plan_id);
        $chkRenewal = Renewal_details::where('user_id',$id)->where('is_activate',1)->where('status',1)->first();
        $state = State::get();
        // if($chkRenewal){
        //     return redirect('/plan-setting');
        // }else{
            return view('frontend.plan.subscription-plan-preview')->with(['user'=>$user,'plan'=>$planDetail,'Module'=>$Module,'states'=>$state,'user_role_id'=>$user_role_id]);
        // }
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
            if($coupon['coupon_count'] != 2){//checking coupon is used previously or not by user
                $chkCoupon = Renewal_details::where('user_id',$request->user_id)->where('coupon_id',$coupon['id'])->where('status',1)->first();
                if($chkCoupon){
                    return response()->json(['status' => '4','plan_amt'=>$request->total_amt]);
                }
            }
            if($coupon->amount_type == 'price'){
                $couponAmt = $coupon->price;
                if($couponAmt > $request->total_amt){
                    return response()->json(['status' => '5','plan_amt'=>$request->total_amt]);
                }
            }else{
                $couponAmt = $coupon->discount;
                // $couponAmt = ceil($request->total_amt*$coupon->discount/100);
            }
            $totalAmt = ceil($planDetail->amount-$couponAmt);
            return response()->json(['status' => '1','coupon_amt'=>$couponAmt,'coupon_id'=>$coupon->id,'total_amt'=>$totalAmt,'discount'=>$coupon->discount,'plan_amt'=>$request->total_amt,'type'=>$coupon->amount_type]);
        }else{
            return response()->json(['status' => '0','plan_amt'=>$request->total_amt]);
        }
    }
    
    public function makePayment(Request $request){
        $updateTeam =0;
        $user = User::find($request->user_id);
        if($request->user_role_id == 1 || $request->user_role_id == 3){//team-1,individual-3
             try { 

                $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
                // $stripe = new Stripe\StripeClient('sk_test_Fl47TIOHWr8jSDdBbVRtp3dj00hCrmxtjy');
                // Stripe\Stripe::setApiKey('sk_test_Fl47TIOHWr8jSDdBbVRtp3dj00hCrmxtjy');
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $orderID = "U".$request->user_id."C".$request->coupon_id."P".$request->user_plan_id."T".$request->tax_percentage;
                
                $subscription_plan = Subscription::where('user_role_id',$request->user_role_id)->where('id',$request->user_plan_id)
                ->first();

                $coupon_data = Coupon::where('id',$request->coupon_id)->first();

                $user_detail = User::where('id',$request->user_id)->first();

                if(isset($request->state) && $request->state !=''){                
                    DB::table('users')->where("id",$request->user_id)->update(array("location_id"=>$request->state));
                }

                //create
                if(isset($subscription_plan->plan_name) && $subscription_plan->plan_name !=''){

                    $product = \Stripe\Product::create([
                           'name' => $subscription_plan->plan_name,
                           'type' => 'service',
                    ]);

                    if(isset($product->id) && isset($subscription_plan->payment_type) && $subscription_plan->payment_type !=''){

                        if($subscription_plan->payment_type == 'yearly') {
                             $interval ='year';
                        }else if($subscription_plan->payment_type == 'monthly'){
                            $interval ='month';
                        }else if($subscription_plan->payment_type == 'day'){
                            $interval ='day';
                        }
                        $price = \Stripe\Price::create([
                          'product' => $product->id,
                          'unit_amount' => ($request->plan_amt*100),
                          'currency' => 'inr',
                          'recurring' => [
                                'interval' => $interval,
                          ],
                          'metadata'      => [
                             'order_id'  => $orderID
                          ]
                        ]);
                    }
                }
                $state_data = State::where('id',$request->state)->first();

                if(isset($state_data) && $state_data->states_name !='' && $request->tax_percentage !=''){
                    $tax_value = $stripe->taxRates->create([
                      'display_name' => $state_data->states_name,
                      'description' => 'state Percentage',
                      // 'jurisdiction' => 'DE',
                      'percentage' => $request->tax_percentage,
                      'inclusive' => false,
                       'metadata'      => ['order_id'  => $orderID]
                    ]);
                }
                
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
                
                 $subscriptions = \Stripe\Subscription::create([
                       'customer' => $customer->id,
                       'items' => [['price' => $price->id]],
                       // 'coupon' => $create_coupon->id,
                       'default_tax_rates' =>[$tax_value->id],
                       'metadata'      => ['order_id'  => $orderID]
                 ]);
                $coupon_id = null;

                $charge = \Stripe\Charge::create([
                    'customer'      => $customer->id,
                    'receipt_email' => $user_detail->email,
                    'amount'        => ($request->total_amt*100),
                    'currency'      => 'INR',
                    'description'   => "This payment is tested purpose",
                    'metadata'      => [
                    'order_id'  => $orderID
                    ]
                ]);
                
                $stripRenewalRecords = StripRenwalRecords::where('user_id',$request->user_id)->whereNull('deleted_at')->first();

                if(isset($stripRenewalRecords) && $stripRenewalRecords !=''){
                
                    $stripe->subscriptions->cancel($stripRenewalRecords->stripe_subcription_id,[]);

                    $deleteStripRenwalRecords = StripRenwalRecords::find($stripRenewalRecords->id);
                    
                    $deleteStripRenwalRecords->delete();

                    // $stripe->customers->deleteTaxId($stripRenewalRecords->stripe_customer_id,$stripRenewalRecords->stripe_tax_id,[]);
                    
                    $stripe->customers->delete($stripRenewalRecords->stripe_customer_id,[]);

                    if(isset($stripRenewalRecords->stripe_coupon_id) && is_null(!$stripRenewalRecords->stripe_coupon_id)){
                        $stripe->coupons->delete($stripRenewalRecords->stripe_coupon_id, []);
                    }

                    $pricedata = $stripe->prices->update($stripRenewalRecords->stripe_price_id,['active'=>false]);

                    // $productdata =$stripe->products->delete($stripRenewalRecords->stripe_product_id,[]);
                }
                
                $stripeRenewalRecords = new StripRenwalRecords();
                $stripeRenewalRecords->user_plan_id = $request->user_plan_id;
                $stripeRenewalRecords->user_id = $request->user_id;
                $stripeRenewalRecords->user_role_id = $request->user_role_id;
                $stripeRenewalRecords->stripe_product_id =$product->id;
                $stripeRenewalRecords->stripe_price_id = $price->id;
                // $stripeRenewalRecords->stripe_coupon_id = $coupon_id;
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
        if($updateTeam == '1'){
            $plantype       =   Subscription::where('id',$request->user_plan_id)->get();
            $nextrenewal = date('Y-m-d');
            $amt=$request->total_amt;
            if(count($plantype) > 0){
                $planbase       =   $plantype[0]->payment_type;        
                if($planbase=='yearly') $nextrenewal   = date('Y-m-d', strtotime("365 days +1 day"));
                else if($planbase=='monthly') $nextrenewal   =  date('Y-m-d', strtotime("30 days +1 day"));
            }
            $renewalinsert =   $this->updatePlanDetails($request->user_id,$request->user_plan_id,$nextrenewal,$request->user_role_id,$amt);
            Session::put('old_user_role_id', $user['user_role_id']); 
            if($user['user_role_id'] != $request->user_role_id){
                $userUpdate=$this->userRegistration($request->user_id,$user['name'],$request->user_role_id,$plantype[0]->plan_name,'2',$amt); 
                $this->changeUserAccount($request->user_role_id); 
            }
            if($user['user_role_id'] == $request->user_role_id){    
                if($user['plan_id'] != $request->user_plan_id){//update
                    $userUpdate=$this->userRegistration($request->user_id,$user['name'],$request->user_role_id,$plantype[0]->plan_name,'4',$amt); 
                }else{//renewal
                    $userUpdate=$this->userRegistration($request->user_id,$user['name'],$request->user_role_id,$plantype[0]->plan_name,'3',$amt); 
                }
            }
            User::where('id',$request->user_id)->update(['plan_id'=>$request->user_plan_id,'user_role_id'=>$request->user_role_id]);  
            $userRenewal = Renewal_details::where('user_id',$request->user_id)->where('is_activate',0)->where('status',0)->where('plan_id',$request->user_plan_id)->latest()->first();
            if($userRenewal){
                $user = User::with('planDetail')->find($request->user_id);
                $user->status = '1';
                $user->save();
                $update = Renewal_details::find($userRenewal->id);
                $update->amount     = $request->total_amt;
                $update->is_activate    =   '1'; 
                $update->status         =   '1'; 
                $update->payment_type   =   '3';
                $update->coupon_id      =   $request->coupon_id;        
                $update->save();
                $emailcontent['new']   =   '2';
                $emailcontent['date']   =   $update->renewal_date;
                $emailcontent['name']      =   $user['name'];
                $emailcontent['plan']      =   $user['planDetail']['plan_name'];
                $emailcontent['plan_type']      =   $user['planDetail']['payment_type'];
                $emailcontent['amt']   =   $update->amount;
                \Mail::to($user['email'])->send(new UserUpgardeMail($emailcontent));
                return response()->json(['status' => '1']);
            }else{
                return response()->json(['status' => '0']);
            }
           
        }
    }

    public function pausePayment(Request $request){
    
        $stripRenewalRecords = StripRenwalRecords::where('user_id',$request->user_id)->whereNull('deleted_at')->first();

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        if($stripRenewalRecords !='' && $request->plan_turn ==1){

            $date = date('Y-m-d H:i:s');
            $dateInteger = strtotime($date);

            \Stripe\Subscription::update($stripRenewalRecords->stripe_subcription_id,['pause_collection' => ['behavior' => 'void',
                'resumes_at' => $dateInteger,],]);

            StripRenwalRecords::where('user_id',$request->user_id)
            ->update(['stripe_payment_collection_status' => 1]);


            return 1;

        } else if($stripRenewalRecords !='' && $request->plan_turn ==0){

            \Stripe\Subscription::update($stripRenewalRecords->stripe_subcription_id,['pause_collection' => ['behavior' => 'void',],]);

            StripRenwalRecords::where('user_id',$request->user_id)->update(['stripe_payment_collection_status' => 0]);

            return 0;
        }
    }

    public function changeUserAccount($role_id){
        $user_id = Auth::user()->id;
        $db_role_id = Session::get('old_user_role_id');
        // dd($db_role_id);
        $getChilds=[];
        $getTeams=[];
        $getTemplates=[];
        $getTeamChilds=[];
        $fcIds=[];
        $proIds = [];
        $proIds = FlowchartProject::where('created_by',$user_id)->pluck('id')->toArray();
        if($db_role_id == 2 ||  $db_role_id == 3){//trial or individual user's upgrading to enterpriser or team,moving active fc to account
            $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();
            if(count($getTemplates) > 0){
                foreach ($getTemplates as $template) {
                    // if($template->status == 1 )
                    // {
                        UserTemplate::where('user_id',$user_id)->where('id',$template->id)->update(['is_approved'=>1]);
                    // }
                }
            }
        }
        else if($db_role_id == 4 || $db_role_id == 1){ //enterpriser or team user's upgrading to enterpriser/team or individual
            if($db_role_id == 4){//enterpriser
                $getTeams = User::where('parent_id',$user_id)->where('user_role_id',1)->pluck('id')->toArray();//getting team users id
                $proIds2 = FlowchartProject::whereIn('created_by',$getTeams)->pluck('id')->toArray();
                $fcIds = array_merge($proIds2,$proIds);
                if(count($fcIds) > 0){ 
                    $getTemplates = UserTemplate::whereIn('project_id',$fcIds)->get();//update user id in template
                }
                if(count($getTeams) > 0){                   
                    $getTeamChilds = User::whereIn('parent_id',$getTeams)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();
                    $getChilds = array_merge($getTeamChilds,$getTeams);
                }
            }else if($db_role_id == 1){//team
                if($role_id != $db_role_id){                
                    $getTemplates = UserTemplate::whereIn('project_id',$proIds)->get();//update user id in template
                    $getChilds = User::where('parent_id',$user_id)->whereIn('user_role_id',['1','2','3','4'])->pluck('id')->toArray();   
                }
            }   

            if(count($getTemplates) > 0){
                foreach ($getTemplates as $template) {                   
                    // if($template->is_approved == 1 && $template->status==1){
                        UserTemplate::where('id',$template->id)->update(['user_id'=>$user_id]); //moving active fc to account
                    // }
                }
            }
            //inactive the subusers            
            $this->inactiveSubUsers($getChilds);
        }
    }
    public function inactiveSubUsers($user_ids){
        if(count($user_ids) > 0){
            foreach ($user_ids as $child) {
                $userDetail = User::find($child);
                $user = new DegradedSubUser();
                $user->name = $userDetail->name;
                $user->email = $userDetail->email;
                $user->user_id = $userDetail->id;
                $user->parent_id = $userDetail->parent_id;
                $user->user_role_id = $userDetail->user_role_id;
                $user->save();
                $userDetail->forceDelete();
                $contact = UserDetail::where('user_id',$child)->delete();
            }
        }
    }
}

?>