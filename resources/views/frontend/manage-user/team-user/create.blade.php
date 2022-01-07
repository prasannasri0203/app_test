@extends('layouts.frontend.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{route('team-user-list.index')}}">
                <h4 class="Acc_Setting as">Add Team User</h4>
            </a>
           
        </div>
        <form method="post"  action="{{route('team-user-list.store')}}">
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
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Full Name<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('full_name')}}" placeholder="Enter Name" name="full_name">
                                    @error('full_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Email<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('email')}}" placeholder="Enter Email" name="email" >
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Contact Number<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('contact_no')}}" placeholder="Enter Number" name="contact_no" data-mask="999-999-9999">
                                    @error('contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                         
                            <input type="hidden" value="1" name="user_role_id">
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Address<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('address')}}" placeholder="Enter Address" name="address">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>City<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('city')}}" placeholder="Enter City" name="city">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Province<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('province')}}" placeholder="Enter Province" name="province">
                                    @error('province')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Postal Code<span style="color: red">*</span></label>
                                    <input type="text"  maxlength="6" value="{{old('pincode')}}" placeholder="Enter Postal Code" name="pincode">
                                    @error('pincode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Password<span style="color: red">*</span></label>
                                    <input type="password" value="{{old('password')}}"  placeholder="Enter Password" name="password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Confirm Password<span style="color: red">*</span></label>
                                    <input type="password" value="{{old('password_confirmation')}}" placeholder="Enter Confirm Password" name="password_confirmation">
                                    @error('password_confirmation')
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
                <button class="btn blue_btn">Save</button>
                <a href="{{route('team-user-list.index')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection