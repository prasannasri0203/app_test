<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FlowchartProject;
class FlowchartProjectRequest extends FormRequest
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
        if(Request::get('id')){

            /*if(Auth::user()->user_role_id == 4){
                $rules = [
                // 'project_name' => ['required','unique:flowchart_projects,project_name,'.Request::get('id'),],
                'project_name' => ['required',
                function($attribute, $value, $fail) {
                    $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->where('id','!=',Request::get('id'))->first();
                    if($chkFC){ 
                        $fail('The Project Name has already been taken.');
                    }
                }],
                'team_user_id' => 'required',  
            ];
            }else if(Auth::user()->user_role_id == 1){
                $rules = [
                    'project_name' => ['required',
                    function($attribute, $value, $fail) {
                        $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->where('id','!=',Request::get('id'))->first();
                        if($chkFC){ 
                            $fail('The Project Name has already been taken.');
                        }
                    }],
                    'admin_id' => 'required',  
                ];
            }else{*/
                $rules = [
                    'project_name' => ['required',
                    function($attribute, $value, $fail) {
                        $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->where('id','!=',Request::get('id'))->first();
                        if($chkFC){ 
                            $fail('The Project Name has already been taken.');
                        }
                    }],
                ];
            // }
        }else{
            /*if(Auth::user()->user_role_id == 4){
                $rules = [
                // 'project_name' => 'required|unique:flowchart_projects,project_name|max:40',
                'project_name' => ['required',
                function($attribute, $value, $fail) {
                    $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->first();
                    if($chkFC){ 
                        $fail('The Project Name has already been taken.');
                    }
                }],
                'team_user_id' => 'required',
                ];
            }else if(Auth::user()->user_role_id == 1){
                $rules = [
                    'project_name' => ['required',
                    function($attribute, $value, $fail) {
                        $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->first();
                        if($chkFC){ 
                            $fail('The Project Name has already been taken.');
                        }
                    }],
                    'admin_id' => 'required',
                ];
            }else{*/
                $rules = [
                    'project_name' => ['required',
                    function($attribute, $value, $fail) {
                        $chkFC = FlowchartProject::where('project_name',$value)->where('created_by',Auth::user()->id)->first();
                        if($chkFC){ 
                            $fail('The Project Name has already been taken.');
                        }
                    }],
                ];
            // }
           
        }

        return $rules;
    }
    public function attributes()
    {
        return [
            'project_name'=> 'Project Name',
            'admin_id' =>'Admin',
            'team_user_id' =>'Team User',
        ];
    }

    public function messages() {
        return [
            //'organization_name.regex' => 'The Family Name already exists.',
            // 'email.regex'=>'The email format is invalid.',
            // 'password.regex'=>' The Password should contain at least 1 uppercase, 1 lowercase, 1 numeric and 1 special character.'
          
            
        ];
    }
 
}
