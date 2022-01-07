@extends('layouts.frontend.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{url('/account-setting')}}">
                <h4 class="Acc_Setting as">Account Settings</h4>
            </a>
             <a href="{{url('/change-password')}}">
                <h4 class="Acc_Setting">Change Password</h4>
            </a>
            @if(Auth::user()->parent_id==0)
                <a href="{{url('/plan-setting')}}">
                    <h4 class="Acc_Setting">Plan and Billings</h4>
                </a>
            @endif

        </div>
        <form method="POST" action="{{url('/edituser-submit')}}"  enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="{{$userdetails[0]->id}}">
            @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div class="inner-wrapper-g">
                <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                    <div class="container-fluid p-0 mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Full Name<span style="color: red">*</span></label>
                                    <input type="text" value="{{$userdetails[0]->name}}" placeholder="Enter Name" name="full_name">
                                    @error('full_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Email</label>
                                    <input type="text" value="{{$userdetails[0]->email}}" placeholder="Enter Email" name="email" readonly>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Contact Number<span style="color: red">*</span></label>
                                    <input type="text" value="{{$userdetails[0]->contact_no}}" placeholder="Enter Number" name="contact_no" data-mask="999-999-9999">
                                    @error('contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if(Auth::user()->user_role_id != 1 || Auth::user()->parent_id == 0)
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Organization<span style="color: red">*</span></label>
                                    <input type="text" value="{{$userdetails[0]->organization_name}}" placeholder="Enter Organization Name" name="organization_name">
                                    @error('organization_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @else
                            <input type="hidden" value="0" name="organization_name">
                            @endif
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Address</label>
                                    <input type="text" value="{{$userdetails[0]->address}}" placeholder="Enter Address" name="address">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>City</label>
                                    <input type="text" value="{{$userdetails[0]->city}}" placeholder="Enter City" name="city">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Province</label>
                                    <input type="text" value="{{$userdetails[0]->province}}" placeholder="Enter Province" name="province">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Postal Code</label>
                                    <input type="text"  maxlength="6" value="{{$userdetails[0]->postal_code}}" placeholder="Enter Postal Code" name="pincode">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in as-i">
                                    <label>Image Logo</label>
                                    <input type="file" placeholder="Enter Image Logo" name="image">
                                     <!-- <span style="font-size: 12px; color: #dd4232; ">The image should be in "166px*55px" dimension.</span> -->
                                    @if($userdetails[0]->image!='')
                                    <img src="{{ asset('user_logo/'.$userdetails[0]->image) }}" width="100px"> 
                                    @endif
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  
                                </div>
                            </div>
                            

                             <!-- <div class="cp">
                                <h5>Change Password</h5>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>New Password</label>
                                    <input type="password" placeholder="Enter New Password" name="password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Confirm Password</label>
                                    <input type="password" placeholder="Enter Confirm Password" name="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Old Password</label>
                                    <input type="password" placeholder="Enter Old Password" name="current_password">
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> -->

                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button class="btn blue_btn">Save</button>
                <a href="{{url('/account-setting')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection