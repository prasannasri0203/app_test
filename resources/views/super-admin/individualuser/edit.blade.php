@extends('layouts.header')
@section('content')
    <div class="header_left">
        <h1>@if((array_key_exists('editpagevalues',$Module))) Edit User @else Add user @endif</h1>
    </div>
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
            <div class="container-fluid p-0 mt-2">
                <?php 
                        
                        if(old('plan_type')) $plantype    =   old('plan_type');
                        else $plantype  =   '';

                        if(old('full_name')) $fullname    =   old('full_name');
                        else $fullname  =   '';

                        if(old('email'))    $email    =   old('email');
                        else $email  =   '';
                        
                        if(old('contact_no')) $contactno    =   old('contact_no');
                        else $contactno  =   '';

                        if(old('organization_name')) $organization    =   old('organization_name');
                        else $organization  =   '';

                        if(old('address')) $address    =   old('address');
                        else $address  =   '';

                        if(old('city')) $city    =   old('city');
                        else $city  =   '';

                        if(old('province')) $province    =   old('province');
                        else $province  =   '';

                        if(old('pincode')) $postalcode    =   old('pincode');
                        else $postalcode  =   '';

                        $userid         =   '';

                    if(array_key_exists('editpagevalues',$Module)){

                        if(old('plan_type')) $plantype    =   old('plan_type');
                        else $plantype  =   $Module['editpagevalues'][0]->plan_id;

                        if(old('full_name')) $fullname    =   old('full_name');
                        else $fullname       =   $Module['editpagevalues'][0]->name;

                        if(old('email'))    $email    =   old('email');
                        else  $email          =   $Module['editpagevalues'][0]->email;

                        if(old('contact_no')) $contactno    =   old('contact_no');
                        else $contactno      =   $Module['editpagevalues'][0]->contact_no;

                        if(old('organization_name')) $organization    =   old('organization_name');
                        else $organization   =   $Module['editpagevalues'][0]->organization_name;

                        if(old('address')) $address    =   old('address');
                        else $address        =   $Module['editpagevalues'][0]->address;

                        if(old('city')) $city    =   old('city');
                        else  $city           =   $Module['editpagevalues'][0]->city;

                        if(old('province')) $province    =   old('province');
                        else $province       =   $Module['editpagevalues'][0]->province;

                        if(old('pincode')) $postalcode    =   old('pincode');
                        else $postalcode     =   $Module['editpagevalues'][0]->postal_code;

                        if(old('status')) $status    =   old('status');
                        else $status    =   $Module['editpagevalues'][0]->status;


                        $userid         =   $Module['editpagevalues'][0]->user_id;
                        
                    }
                ?>
                <form method="POST" action="{{url('/saveindividualuser')}}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$userid}}">
                    <div class="row">

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Full Name <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="text" name="full_name" value="{{$fullname}}" placeholder="Enter Name">
                                @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Email Address <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="email" name="email" value="{{$email}}" placeholder="Enter Email" autocomplete="off" @if(array_key_exists('editpagevalues',$Module)) readonly @endif>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Contact Number <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="text" name="contact_no" value="{{$contactno}}" placeholder="Enter Number" autocomplete="off" data-mask="999-999-9999">
                                @error('contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                          <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Organization Name <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="text" name="organization_name" value="{{$organization}}" placeholder="Enter Organization Name" autocomplete="off">
                                @error('organization_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if(count($Module['plans'])>0)
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Subscription Plan <span style="color: red;margin-left: 0px; ">*</span></label>
                                    <select name="plan_type" class="plan_type form-pik-er frm_width ">
                                        <option value="">Select Plan</option>
                                        @foreach($Module['plans'] as $planname)
                                            <option value="{{$planname->id}}" @if($plantype==$planname->id) selected @endif>{{$planname->plan_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Password <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="password" name="password" value="{{old('password')}}" placeholder="Enter Password" autocomplete="off">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Confirm Password <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}" placeholder="Enter Confirm Password" autocomplete="off">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        @if(!empty($userid))
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Status</label>
                                    <select class="form-pik-er frm_width" name="status" value="">
                                        <option value="1" @if($status==1) selected @endif>Active</option>
                                        <option value="2" @if($status==2) selected @endif>In active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                  <label>Upgrade Plan</label>
                                  <select class="form-pik-er frm_width" name="change_plan">
                                      <option value="2">No</option>
                                      <option value="1">Yes</option>                                
                                  </select>
                                  
                                </div>
                            </div>
                        @endif

                      
                         <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Postal Code</label>
                                <input type="text" name="pincode" value="{{$postalcode}}" placeholder="Enter Postal Code" autocomplete="off">
                                @error('pincode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Address</label>
                                <input type="text" name="address" value="{{$address}}" placeholder="Enter Address" autocomplete="off">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>City</label>
                                <input type="text" name="city" value="{{$city}}" placeholder="Enter City" autocomplete="off">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Province</label>
                                <input type="text" name="province" value="{{$province}}" placeholder="Enter Province" autocomplete="off">
                                @error('province')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                       
                        
                    </div>
                    <div class="button_section">
                        <button type="submit" class="btn blue_btn">@if((array_key_exists('editpagevalues',$Module))) Update User @else Add user @endif</button> 
                        <a href="{{URL('individualuser')}}"><button type="button" class="btn white_btn">Cancel</button></a> 
                    </div>
                </form> 
            </div>
        </div> 
    </div>
@endsection