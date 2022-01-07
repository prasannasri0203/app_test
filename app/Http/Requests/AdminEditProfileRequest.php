<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AdminEditProfileRequest extends FormRequest{

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
            'password'  => ['min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed','nullable'],
            'password_confirmation'   => 'min:8|nullable',
            'contact_no' => ['required','max:12']
            
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'full_name'=> 'Name',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password'
        ];
    }

    public function messages() {
        return [ 
            'password.regex'=>' The Password should contain at least 1 uppercase, 1 lowercase, 1 numeric and 1 special character.'
            
        ];
    }

}