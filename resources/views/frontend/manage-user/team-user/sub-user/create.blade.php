@extends('layouts.frontend.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{route('user-list.index')}}">
                <h4 class="Acc_Setting as">{{ (!empty($user->id)) ? 'Edit User' : 'Add User'}}</h4>
            </a>
           
        </div>
        <form method="post"  action="{{route('sub-user-store',[!empty($user->id) ? $user->id : null ])}}">
        @csrf
            
            <div class="inner-wrapper-g">
                <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                    <div class="container-fluid p-0 mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Full Name<span style="color: red">*</span></label>
                                    <input type="hidden" name="id" value="{{!empty($user->id) ? $user->id : null}}">
                                    <input type="text" value="{{$user ? $user->name :old('full_name')}}" placeholder="Enter Name" name="full_name">
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
                                    @if($user)
                                        <input type="text" readonly="" value="{{$user ? $user->email :old('email')}}" placeholder="Enter Email" name="email" >
                                    @else
                                        <input type="text" value="{{$user ? $user->email :old('email')}}" placeholder="Enter Email" name="email" >
                                    @endif
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
                                    <input type="text" value="{{$user ? $user['userDetail']['contact_no'] :old('contact_no')}}" placeholder="Enter Number" name="contact_no" data-mask="999-999-9999">
                                    @error('contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Select Team User<span style="color: red">*</span></label>
                                    <?php $parent_id = ($user ? $user->parent_id : old('team_user_id')); ?>
                                    <select name="team_user_id">
                                        <option value=""> Select Team User </option>
                                        @foreach($teamUsers as $teamuser)
                                        <option value="{{$teamuser->id}}" @if($parent_id == $teamuser->id) selected @endif > {{ucwords($teamuser->name)}} </option>
                                        @endforeach
                                    </select>
                                    @error('team_user_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Role<span style="color: red">*</span></label>
                                    <?php $role_id = ($user ? $user->user_role_id : old('user_role_id')); ?>
                                    <select name="user_role_id">
                                        <option value=""> Select Role </option>
                                        @foreach($roleList as $list)
                                        <option value="{{$list->id}}" @if($role_id == $list->id) selected @endif > {{$list->role}} </option>
                                        @endforeach
                                    </select>
                                    @error('user_role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Address</label>
                                    <input type="text" value="{{$user ? $user['userDetail']['address'] :old('address')}}" placeholder="Enter Address" name="address">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>City</label>
                                    <input type="text" value="{{$user ? $user['userDetail']['city'] :old('city')}}" placeholder="Enter City" name="city">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Province</label>
                                    <input type="text" value="{{$user ? $user['userDetail']['province'] :old('province')}}" placeholder="Enter Province" name="province">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Postal Code</label>
                                    <input type="text"  maxlength="6" value="{{$user ? $user['userDetail']['postal_code']:old('pincode')}}" placeholder="Enter Postal Code" name="pincode">
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    @if($user)
                                        <label>Password</label>
                                    @else
                                        <label>Password<span style="color: red">*</span></label>
                                    @endif
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
                                    @if($user)
                                        <label>Confirm Password</label>
                                    @else
                                        <label>Confirm Password<span style="color: red">*</span></label>
                                    @endif
                                    <input type="password" value="{{old('password_confirmation')}}" placeholder="Enter ConfirmPassword" name="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if(!empty($user->id))
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                              <div class="pr-la-in">
                                <label>Status</label>
                                <select class="form-pik-er" name="status" value="{{ $user ? $user->status: 1}}">
                                    <option value="1" {{$user && $user->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{$user && $user->status == 2 ? 'selected' : '' }}>In active</option>
                                </select>
                                
                              </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button class="btn blue_btn">Save</button>
                <a href="{{url('sub-user-list')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection