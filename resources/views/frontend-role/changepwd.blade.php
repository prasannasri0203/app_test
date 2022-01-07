@extends('layouts.frontend-role.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{url('/role-user/account-setting')}}">
                <h4 class="Acc_Setting">Account Settings</h4>
            </a> 
            <a href="{{url('/role-user/role-change-password')}}">
                <h4 class="Acc_Setting as">Change Password</h4>
            </a>
           
        </div>
        <form method="POST" action="{{url('/role-user/role-chngpwd-submit')}}"> 
            @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="inner-wrapper-g">
                <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                    <div class="container-fluid p-0 mt-4">
                        <div class="row">
                             <input type="hidden" name="user_id" value="{{Auth()->guard('roleuser')->user()->id}}">
                            <div class="cp">
                                <h5>Change Password</h5>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>New Password</label>
                                    <input type="password" placeholder="Enter Your New Password" name="password">
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
                                    <input type="password" placeholder="Confirm Your Password" name="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Current password</label>
                                    <input type="password" placeholder="Current Password" name="current_password">
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button class="btn blue_btn"> Update </button>
                <a href="{{url('/role-user/role-change-password')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection