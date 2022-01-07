<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class ManageEnterpriseTeamUserRequest extends FormRequest
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
        if(Request::get('id') != null){
            $rules = [
                'full_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'address' => 'max:40|required',
                'user_role_id' => 'required',
                'city' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'province' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'pincode' => 'required|regex:/(^[A-Za-z0-9.\s]+$)+/|max:6',
                'password'                => ['min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed','nullable'],
                'password_confirmation'   => 'min:8|nullable',
                'contact_no' => ['required','max:12'],
               
            ];
        }else{
            $rules = [
                'full_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'email' => ['required', 'email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'unique:users,email,'.Request::get('id')],
                'address' => 'max:40|required',
                'user_role_id' => 'required',
                'city' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'province' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
                'pincode' => 'required|regex:/(^[A-Za-z0-9.\s]+$)+/|max:6',
                'password'                      => ['required', 
               'min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed'],
                'password_confirmation'         => 'required|min:8',
                'contact_no' => ['required','max:12','unique:user_details,contact_no,'.Request::get('id'),],
               
            ];
        }

        return $rules;
    }
    public function attributes()
    {
        return [
            'full_name'=> 'Name',
            'email' =>'Email',
            'pincode' => 'Postal Code',
            'contact_no' => 'Contact No',
            'address' =>'Address',
            'user_role_id' => 'Role',
            'city' => 'City',
            'province' => 'Province',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            
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
