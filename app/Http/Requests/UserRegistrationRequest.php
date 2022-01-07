<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use App\Models\Superadmin\Renewal_details;
class   UserRegistrationRequest extends FormRequest
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
        
        $rules = [
            'user_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
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
            'organization_name' => ['required'],
            'password'                      => ['required', 
               'min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/'],
            'contact_no' => ['required','max:12'],
        ];
        

        return $rules;
    }

    public function messages() {
        return [
            'email.regex'=>'The email format is invalid.',
            'password.regex'=>' The Password should contain atleast 1 uppercase, 1 numeric and 1 special character.'
        ];
    }

    public function attributes()
    {
        return [
            'user_name'=> 'Name',
            'organization_name' => 'Organization Name',
            'email' =>'Email',
            'contact_no' => 'Contact No',
            'password' => 'Password',
        ];
    }
}
