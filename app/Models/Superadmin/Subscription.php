<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;

use Kyslik\ColumnSortable\Sortable; 

class Subscription extends Model
{
    use Sortable;
    protected $table  = 'super_admin_subscription_plan';

    public function saveplans($request){
        date_default_timezone_set('Asia/Kolkata');
		$currentTime = date('Y-m-d H:i:s',time ());

        $data['plan_name']          =   $request['plan_name'];
        $data['description']          =   $request['description'];
        $data['user_role_id']       =   $request['role_type'];
        $data['plan_type']          =   $request['plan_type'];

        if($request['plan_type']=='Free'){
            $basedon            = "";
            $paidamount         = "";
            $activation_period  = $request['activation_period'];
        } 
        else{
            $basedon            = $request['based_on'];
            $paidamount         = $request['paid_amount'];
            $activation_period  = "";
        } 

        $data['activation_period']  =   $activation_period;
        $data['display_in_site']    =   $request['display_in_site'];
        $data['status']             =   $request['status'];
        $data['amount']             =   $paidamount;
        $data['payment_type']       =   $basedon;
        
        if($request['plan_id']==''){
            $data['created_at']         =   $currentTime;
            Subscription::insert($data);
            $status = 'Subscription Plan Added Successfully';
        }else{
            $data['updated_at']         =   $currentTime;
            Subscription::where('id',$request['plan_id'])->update($data);
            $status = 'Subscription Plan Updated Successfully';
        }
        return $status;
    }

    public function subscriptionvalues(){
       
       
        $subscritionlist    =   array();
        $filtervalues       =   array();

        $planname   = '';
        $plantype   = '';
        $status     = '';
		$roleType     = '';

        if(isset($_GET['planname']) && $_GET['planname']!='') $planname     = $_GET['planname'];
        if(isset($_GET['plantype']) && $_GET['plantype']!='') $plantype     = $_GET['plantype'];
        if(isset($_GET['status'])   && $_GET['status']!='')   $status       = $_GET['status'];
        if(isset($_GET['roletype']) && $_GET['roletype']!='')   $roleType = $_GET['roletype'];

        $subscritionlist = Subscription::where('deleted_at',NULL);
        //dd($_GET['role_type']);
        if($planname!='') $subscritionlist->where('plan_name','like','%'.$planname.'%');
        if($plantype!='') $subscritionlist->where('plan_type','like','%'.$plantype.'%');
        if($status!='')   $subscritionlist->where('status','like','%'.$status.'%');
        if($roleType!='') $subscritionlist->where('user_role_id','=',$roleType);
        $subscritionlist = $subscritionlist->sortable()->orderBy('id','DESC')->paginate(20);
        
        $filtervalues['plananame']   =  $planname;
        $filtervalues['plantype']    =  $plantype;
        $filtervalues['status']      =  $status;
        $filtervalues['roletype']      =  $roleType;

        $arrayvalues['subscriptionlist']    =   $subscritionlist;
        $arrayvalues['filtervalues']        =   $filtervalues;
       
        return $arrayvalues;
    }

    public function editpagevalues($id){
        $subsriptionvalues  =   Subscription::where('super_admin_subscription_plan.id',$id)->get();
        return $subsriptionvalues;
    }

    public function deletesubscriptionplan($id){
        $plan_applylist=User::where('plan_id',$id)->get(); 
        
        if(count($plan_applylist) == 0)
        {     
            $subscription = Subscription::find($id);
            $subscription->delete(); 
            $status='Subscription Plan Deleted Successfully';  
        }else
        { 
            $status='In this plan, user has been subscribed, so you can not able to delete!';
        }

        return $status;

    }

    public function Userrolelist(){
        $role = DB::table('user_roles')->get();
        return $role;
    }
    public $sortable = ['id','plan_name', 'description','user_role_id','activation_period','display_in_site', 'status', 'amount', 'payment_type'];

}

?>