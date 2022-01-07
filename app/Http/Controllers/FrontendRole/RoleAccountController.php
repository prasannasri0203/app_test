<?php
 
namespace App\Http\Controllers\FrontendRole;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Superadmin\Subscription;
use App\Models\Superadmin\Individualuser;
use App\Models\Superadmin\Renewal_details;
use App\Http\Requests\ChangePasswordRequest;
use Auth; 
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use Kyslik\ColumnSortable\Sortable;
use Log;
use Session;
use DB;
use Hash;
use DateTime;
use DatePeriod;
use DateInterval;
use Stripe;

class RoleAccountController extends Controller
{
	public function changePassword(Request $request)
	{
         $Module['module'] = 'account-setting';
		  return view('frontend-role.changepwd',['Module'=>$Module]);
	}
	 public function updateChangePwd(ChangePasswordRequest $request)
	 { 
	 		$user_id=Auth()->guard('roleuser')->user()->id;
    		$users = User::find($user_id); 
    		$users = User::find($request->user_id);
            if(Hash::check($request->current_password,$users->password)){ 
                $users->password   = Hash::make($request->password);
                $users->save(); 
                return redirect('/role-user/role-change-password')->with('status','Password Updated Successfully');
            }else{
                $url    =   url('/role-user/role-change-password');
                return redirect($url)->withErrors(['current_password' => trans('Current Password Not Match')]);
            }
        
	 }
   
}
