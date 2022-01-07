<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Models\User;


class UserEditRequest extends FormRequest{

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

    public function rules(Request $request)
    {

        $rules = [
            'full_name' => 'required|regex:/^[a-zA-Z.\s ]*$/u|max:40',
            'organization_name' => ['required'], 
             'pincode' => 'regex:/(^[A-Za-z0-9.\s]+$)+/|max:6|nullable',
            'password'                => ['min:8','regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/','confirmed','nullable'],
            'password_confirmation'   => 'min:8|nullable', 
            // 'image'=>'max:300kb|Mimes:jpeg,jpg,png| dimensions:max_width=166,max_height=55',
            'image'=>'max:300kb|Mimes:jpeg,jpg,png',
            'contact_no' => ['required','max:12',Rule::unique('user_details')->where   (function($query) use  ($request){
                $query->where('user_id', '!=', $request->user_id);
              })]
        ];

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
            'password_confirmation' => 'Confirm Password'
        ];
    }

    public function messages() {
        return [
            //'organization_name.regex' => 'The Family Name already exists.',
            'image.dimensions'=>'The image should be in "166px*55px" dimension.',
            'email.regex'=>'The email format is invalid.',
            'password.regex'=>' The Password should contain at least 1 uppercase, 1 lowercase, 1 numeric and 1 special character.'
            
        ];
    }

}