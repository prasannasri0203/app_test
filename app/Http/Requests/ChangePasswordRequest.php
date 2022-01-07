<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;


class ChangePasswordRequest extends FormRequest
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
        $rules =[
           'password'                 => ['required', 
               'min:8', 
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$~#%^&*@]).*$/', 
               'confirmed'], 
           'password_confirmation'    => 'required|min:8',
           'current_password'      => 'required|min:8', 
        ];
        return $rules;
    }

    public function messages() {
        return [ 
            'password.regex'=>'The Password should contain at least 1 uppercase, 1 lowercase, 1 numeric and 1 special character.', 
        ];
    }

}
