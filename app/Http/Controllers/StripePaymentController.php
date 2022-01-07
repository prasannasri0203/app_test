<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use Session;
use Stripe;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Superadmin\Subscription;
use App\Models\Superadmin\Renewal_details;
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe($id,$amt)
    {
        $user = User::find($id);
        return view('user-registration.stripe')->with(['user'=>$user,'amt'=>$amt]);
    }
   
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $customer = \Stripe\Customer::create([
                'name' => $request->username,
                'address' => [
                    'line1'       => $request->line1,
                    'postal_code' => $request->postal_code,
                    'city'        => $request->city,
                    'state'       => $request->state,
                    'country'     => $request->country,
                ],
            ]);
            $charge = \Stripe\Charge::create([
                'customer'     => $customer['id'], 
                'source'      => $request->stripeToken,
                'currency'    => 'USD',
                'amount'      => $request->amt,
                'description' => "This payment is tested purpose",
            ]);
            /*Stripe\Charge::create ([
                    "amount" => 100 * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "This payment is tested purpose",
            ]);*/
            $userRenewal = Renewal_details::where('user_id',$request->user_id)->where('is_activate',0)->where('status',0)->first();
            if($userRenewal){
                $user = User::find($request->user_id);
                $user->status = '1';
                $user->save();
                $update = Renewal_details::find($userRenewal->id);
                $update->is_activate    =   '1'; 
                $update->status         =   '1'; 
                $update->payment_type   =   '3'; 
                $update->save();
            }
            return redirect('/success-payment/'.$request->user_id);
            //Session::flash('success', 'Payment successful!');
        } catch (\Exception $ex) {
            Session::flash('failure', $ex->getMessage());
        }
        return back();
    }
}