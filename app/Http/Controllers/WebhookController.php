<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StripRenwalRecords;
use Illuminate\Support\Facades\Log;
use App\Models\Superadmin\Renewal_details;
use App\Models\Superadmin\Subscription;
use App\Models\User;
use App\Models\Superadmin\Tax;
use Session;
use DB;
use App\Models\Notification;
use App\Models\Admin;

class WebhookController extends Controller
{
    
    public function webhookResponse(){
    
      // webhook.php
      //
      // Use this sample code to handle webhook events in your integration.
      //
      // 1) Paste this code into a new file (webhook.php)
      //
      // 2) Install dependencies
      //   composer require stripe/stripe-php
      //
      // 3) Run the server on http://localhost:4242
      //   php -S localhost:4242

      require 'vendor/autoload.php';

      // This is your Stripe CLI webhook secret for testing your endpoint locally.
      $endpoint_secret = 'whsec_LAcSN9wgdV0uskdazwaWtaSEIyv3uTvn';

      $payload = @file_get_contents('php://input');
      $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
      $event = null;

      try {
        $event = \Stripe\Webhook::constructEvent(
          $payload, $sig_header, $endpoint_secret
        );
      } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        exit();
      } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        http_response_code(400);
        exit();
      }
      // Handle the event
       switch ($event->type) {
        
        case 'customer.subscription.created':

            $customer = $event->data->object;

            Log::info("customer.subscription.created");
            
            $arr_subscription = $customer->jsonSerialize();
            
            Log::info($arr_subscription);
            

        case 'charge.failed':
          $charge = $event->data->object;
          Log::info('failed');
          Log::info($charge);
          $ser_charge = $charge->jsonSerialize();
          Log::info($ser_charge);

        case 'charge.succeeded':

            $charge = $event->data->object;
            $paymenyResponse = $charge->jsonSerialize();

            if($event->data->object->id != ''){
              
              Session::put('stripe_charge_id',$event->data->object->id);
              Log::info("put_session_stripe_charge_id".$event->data->object->id);
            }

            
            // if($stripe_subscription_id){
            //   Log::info("here have a stripekey");
            //   Log::info($stripe_subscription_id);
            // }else{
            //   Log::info("there is no stripkey");
            // }

            // if($stripe_subscription_id != ''){

            //     $stripe_details = StripRenwalRecords::where('stripe_subcription_id',$stripe_subscription_id)->where('stripe_charge_id','!=',$paymenyResponse['id'])->first();

            //     Log::info("stripe_details");
            //     Log::info($stripe_details);
               

            //     if($stripe_details !=''){
            //        $db_renewal_details = Renewal_details::where('user_id',$stripe_details['user_id'])->where('is_activate',1)->where('status',1)->get();

            //         Log::info("db_renewal_details");
            //         Log::info($db_renewal_details);
               
            //         if($db_renewal_details->count() > 0){

            //             $status = DB::table('renewal_details')->where("user_id",$stripe_details['user_id'])->where('is_activate',1)->where('status',1)->update(array("is_activate"=>0));

            //             Log::info("renewal details status");
            //             Log::info($status);

            //             $user_detail = User::where('id',$stripe_details['user_id'])->first();
                        
                        
            //             $tax_detail = Tax::where('state_id',$user_details->location_id)->first();
                        
            //             $tax_percentage = $tax_detail['gst']+$tax_detail['pst']+$tax_detail['hst']+$tax_detail['qst'];

            //             $amount =$stripe_details['amount']+$stripe_details['amount']*($tax_percentage/100);

            //             $renewalDetails = new Renewal_details();
            //             $renewalDetails->user_id = $stripe_details['user_id'];
            //             $renewalDetails->coupon_id = 0;
            //             $renewalDetails->plan_id = $stripe_details['user_plan_id']; 
            //             $renewalDetails->renewal_date =  date("Y-m-d", $paymenyResponse["created"]);
            //             $renewalDetails->amount = $amount;
            //             $renewalDetails->payment_type = 3;
            //             $renewalDetails->is_activate = 1;
            //             $renewalDetails->status = 1;
            //             $renewalDetails->save();

            //             $admins = Admin::where('status',1)->get();
            //             $plandet = Subscription::find($stripe_details['user_plan_id']);
            //             $msg = ucfirst($user_detail['name']) .' has renewed his/her '.$plandet['plan_name'].' plan and paid CAD '.$amount;
            //             foreach ($admins as $admin) {
            //               $chkNotify = Notification::where('from_id',$stripe_details['user_id'])->where('to_id',$admin->id)->where('message',$msg)->where('type',7)->first();
            //               if(!$chkNotify){
            //                   $addnotificate = new Notification; 
            //                   $addnotificate->from_id = $stripe_details['user_id'];
            //                   $addnotificate->to_id = $admin->id; 
            //                   $addnotificate->type = 7; 
            //                   $addnotificate->message = $msg;
            //                   $addnotificate->updated_by = 1;
            //                   $addnotificate->save();  
            //               }
            //             }
            //             if($renewalDetails){

            //               Log::info("renewalDetails->true");
            //             }else{
            //               Log::info("renewaldetails->false");
            //             }
            //         }
            //     }
            // }else{

            //    Log::info("condition failed");
            // }

            case 'invoice.payment_succeeded':
              
                  $stripe_charge_id  = Session::get('stripe_charge_id');

                  Log::info('get_session_stripe_charge_id'.$stripe_charge_id);

                  Log::info('json_data'.$event->data);

                  $stripe_subscription_id = $event->data->object->subscription;
                  
                  Log::info('stripe_subcription_id'.$event->data->object->subscription);

                  Log::info('varible_stripe_subcription_id'.$stripe_subscription_id);

                  Log::info("sub and charge condition");
                  
                  $paymenyResponse = $charge->jsonSerialize();


                  if($stripe_subscription_id !='' || $stripe_charge_id !=''){
                    
                    Log::info("condition true");
                    
                    $stripe_details = StripRenwalRecords::where('stripe_subcription_id',$stripe_subscription_id)->where('stripe_charge_id','!=',$stripe_charge_id)->first();
                    
                          Log::info("stripe_details");
                          Log::info($stripe_details);
                      if($stripe_details !=''){

                          $db_renewal_details = Renewal_details::where('user_id',$stripe_details['user_id'])->where('is_activate',1)->where('status',1)->get();

                          Log::info("db_renewal_details");
                          Log::info($db_renewal_details);
                     
                          if($db_renewal_details->count() > 0){

                              Log::info("if condition count");

                              $renewal_details_status = DB::table('renewal_details')->where("user_id",$stripe_details['user_id'])->where('is_activate',1)->where('status',1)->update(array("is_activate"=>0));

                              Log::info("renewal details status");
                              Log::info($renewal_details_status);

                              $user_detail = User::where('id',$stripe_details['user_id'])->first();
                              
                              $tax_detail = Tax::where('state_id',$user_detail->location_id)->first();
                              
                              $tax_percentage = $tax_detail['gst']+$tax_detail['pst']+$tax_detail['hst']+$tax_detail['qst'];

                              $amount =$stripe_details['amount']+$stripe_details['amount']*($tax_percentage/100);
                              
                              $renewalDetails = new Renewal_details();
                              $renewalDetails->user_id = $stripe_details['user_id'];
                              $renewalDetails->coupon_id = 0;
                              $renewalDetails->plan_id = $stripe_details['user_plan_id']; 
                              $renewalDetails->renewal_date =  date("Y-m-d", $event->data->object->subscription->created);
                              $renewalDetails->amount = $amount;
                              $renewalDetails->payment_type = 3;
                              $renewalDetails->is_activate = 1;
                              $renewalDetails->status = 1;
                              $renewalDetails->save();

                              Log::info("saveRenewalDetails");
                              Log::info($renewalDetails);
                    
                              $admins = Admin::where('status',1)->get();
                              $plandet = Subscription::find($stripe_details['user_plan_id']);
                              $msg = ucfirst($user_detail['name']) .' has renewed his/her '.$plandet['plan_name'].' plan and paid CAD '.$amount;
                              foreach ($admins as $admin) {
                                $chkNotify = Notification::where('from_id',$stripe_details['user_id'])->where('to_id',$admin->id)->where('message',$msg)->where('type',7)->first();
                                if(!$chkNotify){
                                    $addnotificate = new Notification; 
                                    $addnotificate->from_id = $stripe_details['user_id'];
                                    $addnotificate->to_id = $admin->id; 
                                    $addnotificate->type = 7; 
                                    $addnotificate->message = $msg;
                                    $addnotificate->updated_by = 1;
                                    $addnotificate->save();  
                                }
                              }
                              if($renewalDetails){

                                Log::info("renewalDetails->true");
                              }else{
                                Log::info("renewaldetails->false");
                              } 
                          }
                      }
                  }else{

                     Log::info("condition failed");
                  }
                  
          // ... handle other event types
        default:
          echo 'Received Data ' . $event->type;
      }
      http_response_code(200);
  }
}