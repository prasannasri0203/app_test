@extends('layouts.frontend.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{route('user-list.index')}}">
                  <h4 class="Acc_Setting name-title">Edit User</h4>
            </a>
           
        </div>
        <form method="post"  action="{{route('user-list.update',$user->id)}}">
        @csrf @method('put')
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
                            <div class="pr-la-in">
                                <input type="hidden" name="id" value="{{!empty($user->id) ? $user->id : null}}">
                                    <label>Full Name<span style="color: red">*</span></label>
                                    <?php $full_name = (old('full_name'))?old('full_name'):$user->name; ?>
                                    <input type="text" value="{{$full_name}}" placeholder="Enter Name" name="full_name">
                                    @error('full_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Email</label>
                                    <?php $email = (old('email'))?old('email'):$user->email; ?>
                                    <input type="text" readonly="" value="{{$email}}" placeholder="Enter Email" name="email" >
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
                                    <?php $contact_no = (old('contact_no'))?old('contact_no'): optional($user->userDetail)->contact_no; ?>
                                    <input type="text" value="{{$contact_no}}" placeholder="Enter Number" name="contact_no" data-mask="999-999-9999">
                                    @error('contact_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                    

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Role<span style="color: red">*</span></label>
                                    <?php $role_id = (old('role_id'))?old('role_id'): $user->user_role_id; ?>
                                 
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
                                    <label>Status</label>
                                    <?php $status = (old('status'))?old('status'): $user->status; ?>
                                 
                                    <select name="status">
                                        <option value="1" @if($status ==1) selected @endif > Active </option> 
                                        <option value="0" @if($status ==0) selected @endif > Inactive </option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Address</label>
                                    <?php $address = (old('address'))?old('address'): optional($user->userDetail)->address; ?>
                                    <input type="text" value="{{$address}}"  placeholder="Enter Address" name="address">
                                </div>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>City</label>
                                    <?php $city = (old('city'))?old('city'): optional($user->userDetail)->city; ?>
                                    <input type="text" value="{{$city}}" placeholder="Enter City" name="city">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Province</label>
                                    <?php $province = (old('province'))?old('province'): optional($user->userDetail)->province; ?>
                                    <input type="text" value="{{$province}}" placeholder="Enter Province" name="province">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>Postal Code</label>
                                    <?php $postal_code = (old('pincode'))?old('pincode'): optional($user->userDetail)->postal_code; ?>
                                    <input type="text"  maxlength="6" value="{{$postal_code}}" placeholder="Enter Postal Code" name="pincode">
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <label>New Password</label>
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
                                    <label>Confirm Password</label>
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
                <button class="btn blue_btn">Update </button>
                <a href="{{route('user-list.index')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection