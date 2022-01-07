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
use App\Models\User;

class ThemeSettingController extends Controller
{
     
    public function setTheme(){     	
    	$user_id=Auth::user()->id;
    	$user = User::find($user_id);
    	if($user->theme_setting_id != 0){
    		$theme = ThemeSetting::withTrashed()->where('id',$user->theme_setting_id)->first();
    		return response()->json(['success' => '1', 'background_color' => $theme->background_color, 'font_color' => $theme->font_color]);
    	}else{
    		return response()->json(['success' => '0']);
    	}
    }
    public function themeList(){  
		$Module['module'] = 'themes';
		$user_id=Auth::user()->id;
    	$user = User::find($user_id);
    	$theme_id=$user->theme_setting_id;
        $themes = ThemeSetting::where('status',1)->latest()->get();
		return view('frontend.themelist',compact('themes'),['Module'=>$Module,'theme_id'=>$theme_id]);
    }
    public function updateTheme(Request $request){   	
		$user_id=Auth::user()->id;
    	$user = User::find($user_id);
		$user->theme_setting_id    =   $request->theme_id;        
    	$user->save();
    	return response()->json(['success' => '1', 'message' => 'Theme updated']);
    }

    public function setDefaultTheme()
    {
        $user_id=Auth::user()->id;
        $user = User::find($user_id);
        $user->theme_setting_id   = "0";        
        $user->save();
        return response()->json(['success' => '1', 'message' => 'Default Theme updated']);
    }
}
