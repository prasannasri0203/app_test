<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Superadmin\Coupon;   
use App\Models\UserDetail;
use App\Models\UserRole;
use Carbon\Carbon;
use App\Models\Superadmin\ThemeSetting;
use App\Models\Superadmin\Renewal_details;
class CouponController extends Controller
{ 
    protected   $Cms;
    public function __construct()
    {
         $this->Coupon = new Coupon();
    }

    public function couponIndex()
    {
      	$couponlist['module'] = 'coupon';
        $coupon_name = (isset($_GET['coupon_name']) && $_GET['coupon_name'] != '') ? $_GET['coupon_name'] : '';
        $coupon_code = (isset($_GET['coupon_code']) && $_GET['coupon_code'] != '') ? $_GET['coupon_code'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
        $current_date=Carbon::now();  
 
        $coupons = Coupon::With('userRole')->sortable();

        if ($coupon_name != '') {
                $coupons->where('coupon_name', 'like', '%' . $coupon_name . '%');
            }
        if ($coupon_code != '') {
                $coupons->where('coupon_code', 'like', '%' . $coupon_code . '%');
            } 
        if ($status != '') {
                $coupons->where('status', 'like', '%' . $status . '%');
            }   
        $coupons =  $coupons->orderBy('id', 'DESC')->paginate(20);   

        return view('super-admin.coupon.coupon-index',compact('coupons'),['Module'=>$couponlist])
        ->with('coupon_name', $coupon_name)->with('coupon_code', $coupon_code)->with('status', $status); 
    }

    public function addCoupon($id='')
    {
       $couponadd_edit['module'] = 'coupon'; 
       $user_roles = UserRole::pluck('role', 'id'); 
       if($id!='') $couponadd_edit['addeditcouponvalue'] = $this->Coupon->addEditCouponValues($id); 
       return view('super-admin.coupon.add-coupon',['Module'=>$couponadd_edit],compact('user_roles'));
   }

    public function storedCoupon(Request $request){  
         
        if($request->coupon_id!=''){
          $request->validate([
        'coupon_name' => 'required',
        'coupon_code' => 'required',  
        'start_date' => 'required', 
        'end_date' => 'required', 
        'status' => 'required', 
        'amount_type' => 'required',
        'coupon_count' => 'required', 
        ]);    
      }else{
        $request->validate([
        'coupon_name' => 'required',
        'coupon_code' => 'required|unique:coupons',  
        'start_date' => 'required', 
        'end_date' => 'required', 
        'status' => 'required', 
        'amount_type' => 'required',
        'coupon_count' => 'required', 
        ]);  
      } 
      if($request->amount_type=='price')
      {
         if($request->coupon_id!=''){
             $request->validate([     
            'price' => 'required|numeric',  
            ]); 
        }else{
             $request->validate([     
            'price' => 'required|numeric',  
            ]);
        }
      } else if($request->amount_type=='discount')
      {
         if($request->coupon_id!=''){
             $request->validate([     
            'discount' => 'required|numeric',  
            ],[
                'discount.numeric' => 'The Percentage must be a number.', 
            ]); 
        }else{
             $request->validate([     
            'discount' => 'required|numeric',  
            ],[
                'discount.numeric' => 'The Percentage must be a number.', 
            ]);
        }
      }
       $status = $this->Coupon->saveCoupons($request); 
       $url = url('/coupon-view');
        return redirect($url)->with('status',$status);
    }
    
    public function deleteCoupon($id){ 
       $coupon_applylist=Renewal_details::where('coupon_id',$id)->get(); 
       
        if(count($coupon_applylist)==0)
        {  
          $coupon = Coupon::find($id);
          $coupon->delete();
          $status = 'Coupon Deleted Successfully';
           return redirect('/coupon-view')->with('status',$status);
        }
        else{ 
          $failure='In this Coupon, user has been applied, so you can not able to delete!';
          return redirect('/coupon-view')->with('failure',$failure);
        } 
       
    }
    public function themeList(){
        $themeList['module'] = 'themes';
        $color_name = (isset($_GET['color_name']) && $_GET['color_name'] != '') ? $_GET['color_name'] : '';
        $background_color = (isset($_GET['background_color']) && $_GET['background_color'] != '') ? $_GET['background_color'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        

        $themes = ThemeSetting::sortable();

        if ($color_name != '') {           
                $themes->where('color_name', 'like', '%' . $color_name . '%'); 
            }
        if ($background_color != '' && $background_color !='#FFFFFF') { 
                $themes->where('background_color', 'like', '%' . $background_color . '%');
            } 
        if ($status != '') {
                $themes->where('status', 'like', '%' . $status . '%');
            }  
        $themes =  $themes->orderBy('id', 'DESC')->paginate(20); 
        $showbtn =1;  
        $themescount=ThemeSetting::get();
        if(count($themescount) >= 7){
          $showbtn =0;
        }
        return view('super-admin.theme.index',compact('themes','showbtn'),['Module'=>$themeList])
        ->with('color_name', $color_name)->with('background_color', $background_color)->with('status', $status);
    }

    public function addTheme($id='')
    {
      if($id!=''){
       $themeList['module'] = 'themes';
      }
     $themeList['module'] = 'themes';
      if($id!='') $theme['addeditthemevalue'] = ThemeSetting::where('id',$id)->get();       
      return view('super-admin.theme.add-theme',['Module'=>$themeList]);
    }

    public function storedTheme(Request $request){ 
    //dd($request->background_color); 
      if($request->theme_id!=''){
          $request->validate([
        'theme_name' => 'required',
        'background_color' => ['required','unique:theme_settings,background_color,'.$request->theme_id,], 
        'font_color' => 'required'
        ]);    
      }else{
        $request->validate([
        'theme_name' => 'required',
        'background_color' => 'required|unique:theme_settings',  
        'font_color' => 'required'
        ]);  
      } 
      if($request->theme_id!=''){
        $themeInsert = ThemeSetting::find($request->theme_id);
        $status = 'Theme Updated Successfully';
      }else{
        $themeInsert = new ThemeSetting();
        $status = 'Theme Added Successfully';
      }
      $themeInsert->color_name = $request->theme_name;
      $themeInsert->background_color = $request->background_color;
      $themeInsert->font_color = $request->font_color;
      $themeInsert->status = $request->status;
      $themeInsert->save();
      $url = url('/themes');
      return redirect($url)->with('status',$status);
    }
    
    public function deleteTheme($id){
      $theme = ThemeSetting::find($id);
      $theme->delete();
      $status = 'Theme Deleted Successfully';
      $url = url('/themes');
      return redirect($url)->with('status',$status);
    }
}

