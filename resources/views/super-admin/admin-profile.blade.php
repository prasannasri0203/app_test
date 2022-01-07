@extends('layouts.header')
@section('content')
<div class="header_left">
  <h1> Edit Profile </h1>
</div>
<form method="POST" action="{{ route('update-profile') }}" enctype="multipart/form-data">
  @csrf
  <div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
     
      <div class="container-fluid p-0 mt-2">
        @if (session('status'))
                      <div class="alert alert-success" role="alert">
                          {{ session('status') }}
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      </div>
                    @endif
        <div class="row">
              <input type="hidden" name="admin_id" value="{{Auth::guard('superadmin')->user()->id}}">
          
              <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <div class="pr-la-in as-i">
                    <label>Full Name<span style="color: red">*</span></label>
                    <input type="text" value="{{$admin->name}}" placeholder="Enter Name" name="full_name">
                    @error('full_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div> 
              <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <div class="pr-la-in as-i">
                    <label>Contact Number<span style="color: red">*</span></label>
                    <input type="text" maxlength="12"  data-mask="999-999-9999" placeholder="Enter Number" name="contact_no"
                        value="{{$admin ? $admin->mobile : old('contact_no') }}">
                    @error('contact_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div> 
              <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                <div class="pr-la-in as-i">
                    <label>Email Address</label>
                    <input type="text" value="{{$admin->email}}" placeholder="Enter Email" name="email" readonly="readonly">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>  
             <div class="cp">
                                <h5>Change Password</h5>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Password</label>
                                    <input type="password" placeholder="Enter Password" name="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
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
                           <!--  <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
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
      <div class="button_section">
        <button type="submit" class="btn blue_btn">Update </button>
        <a href="{{route('admin-profile')}}"><button type="button" class="btn white_btn">Cancel</button></a>
      </div>    
    </div> 

  </div>
</form>
@endsection