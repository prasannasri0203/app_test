<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use App\Models\Superadmin\Renewal_details;
class ManageIndividualUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
        if(Request::get('user_id') != null){
            $rules = [
                'full_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'address' => 'max:40|nullable',
                'organization_name' => ['required'],
                'city' => 'regex:/^[a-zA-Z.\s ]*$/u|max:40|nullable',
                'province' => 'regex:/^[a-zA-Z.\s ]*$/u|max:40|nullable',
                'pincode' => 'regex:/(^[A-Za-z0-9.\s]+$)+/|max:6|nullable',
                'password'                => ['min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed','nullable'],
                'password_confirmation'   => 'min:8|nullable',
                'contact_no' => ['required','max:12'],
                'plan_type'=> ['required'],
            ];
        }else{
            
            $rules = [
                'full_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'email' => ['required', 'email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                    function($attribute, $value, $fail) {
                        $chkSubUser = User::where('email',$value)->where('user_role_id','!=','0')->where('plan_id','0')->where('parent_id','!=',0)->first();
                       
                        if($chkSubUser){ 
                            $fail('The Email has already been taken.');
                        }
                        $chk = User::where('email',$value)->where('user_role_id','!=','0')->where('plan_id','!=','0')->first();
                     
                        if($chk){  
                            $chkRenewal = Renewal_details::where('user_id',$chk->id)->where('is_activate','!=','0')->where('status','!=','0')->get();
                            if(count($chkRenewal) > 0){
                                $fail('The Email has already been taken.');
                            }
                        }
                        
                    }],
                'address' => 'max:40|nullable',
                'organization_name' => ['required'],
                'city' => 'regex:/^[a-zA-Z.\s ]*$/u|max:40|nullable',
                'province' => 'regex:/^[a-zA-Z.\s ]*$/u|max:40|nullable',
                'pincode' => 'regex:/(^[A-Za-z0-9.\s]+$)+/|max:6|nullable',
                'password'                      => ['required', 
               'min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed'],
                'password_confirmation'         => 'required|min:8',
                'contact_no' => ['required','max:12'],
                'plan_type'=> ['required'],
            ];
        }

        return $rules;
    }
    public function attributes()
    {
        return [
            'full_name'=> 'Name',
            'organization_name' => 'Organization Name',
            'email' =>'Email',
            'pincode' => 'Postal Code',
            'contact_no' => 'Contact No',
            'address' =>'Address',
            'city' => 'City',
            'province' => 'Province',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            'plan_type'=> 'Subscription Plan',
        ];
    }

    public function messages() {
        return [
            //'organization_name.regex' => 'The Family Name already exists.',
            'email.regex'=>'The email format is invalid.',
            'password.regex'=>' The Password should contain at least 1 uppercase, 1 lowercase, 1 numeric and 1 special character.'
            
        ];
    }
 
}
