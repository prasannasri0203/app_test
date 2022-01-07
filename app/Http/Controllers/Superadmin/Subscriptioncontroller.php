<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Superadmin\Subscription; 

class Subscriptioncontroller extends Controller
{

    protected	$Subscription;
    public function __construct()
    {
        $this->Subscription	=	new Subscription();
    }

    public function index(){

        $subscriptionlist['module']         = 'subscription';
        $subscriptionplan   =   $this->Subscription->subscriptionvalues();
        $subscriptionlist['listvalues']         =  $subscriptionplan['subscriptionlist'];
        $subscriptionlist['filtervalues']       =  $subscriptionplan['filtervalues'];
        return view('super-admin.subscriptionplan.index',['Module'=>$subscriptionlist]);
    }

    public function edit($id=''){
        
        $subscriptionedit['module']     = 'subscription';
        $subscriptionedit['rolelist']   =  $this->Subscription->Userrolelist();
        if($id!='') $subscriptionedit['editvalues'] = $this->Subscription->editpagevalues($id);
        
        return view('super-admin.subscriptionplan.edit',['Module'=>$subscriptionedit]);
    }

    public function save(Request $request){
        if(isset($request['plan_id'])){
            if($request['plan_type']=='Free'){
                $validatedData = $request->validate([
                    'plan_name' => ['required',Rule::unique('super_admin_subscription_plan')->where(function($query) use  ($request){
                        $query->where('id', '!=', $request['plan_id']);
                      })
                    ],
                    'activation_period'=>'required',
                    'role_type'=>'required'
                ]);
            }else if($request['plan_type']=='Paid'){
                $validatedData = $request->validate([
                    'plan_name' => ['required',Rule::unique('super_admin_subscription_plan')->where(function($query) use  ($request){
                        $query->where('id', '!=', $request['plan_id']);
                      })
                    ],
                    'paid_amount'=>'required',
                    'role_type'=>'required'
                ]);
            } 
        }else{
            if($request['plan_type']=='Free'){
                $validatedData = $request->validate([
                    'plan_name' => 'required|unique:super_admin_subscription_plan',
                    'activation_period'=>'required',
                    'role_type'=>'required'
                ]);
            }else if($request['plan_type']=='Paid'){
                $validatedData = $request->validate([
                    'plan_name' => 'required|unique:super_admin_subscription_plan',
                    'paid_amount'=>'required',
                    'role_type'=>'required'
                ]);
            } 
        }

        $status = $this->Subscription->saveplans($request);
        $url = url('/super-subscription-plan');
        return redirect($url)->with('status',$status);
    }

    public function delete($id){
       $status = $this->Subscription->deletesubscriptionplan($id);
        $url = url('/super-subscription-plan');
        if($status != 'Subscription Plan Deleted Successfully'){
            return redirect($url)->with('failure',$status);
        }else{
            return redirect($url)->with('status',$status);
        }
        
    }
}
