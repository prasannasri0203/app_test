<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Superadmin\ThemeSetting;
use Auth;
use DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserTemplate;
use App\Models\TemplateShare;

class ReceivedFlowChartController extends Controller
{
      public function receivedFlowChart(){
        $Module['module']   =  'receive-flowchart';
        $user=Auth::user()->email;
        $sharedTemplate = TemplateShare::get();
        $templates=[];
        $usertemplate=[];
        $templateIDs=[];
        if(count($sharedTemplate) > 0){
            foreach ($sharedTemplate as $value) {
                $dbemails= unserialize($value['user_email']);
                if(in_array($user, $dbemails)){
                    $templates[]=$value['id'];
                    $templateIDs[] =$value['user_template_id'];
                }
            }
            //dd($templates);
            if(count($templates) > 0){
                $chkTemplates = UserTemplate::whereIn('id',$templateIDs)->pluck('id')->toArray();
                $usertemplate = TemplateShare::with('userDetail','userTemplate')->whereIn('user_template_id',$chkTemplates)->orderBy('id', 'desc')->get();
            }
        }
        //dd($usertemplate);
        return view('frontend.myflowchart.received_flowchart',compact('usertemplate'),['Module'=>$Module]);
    }     
}