@extends('layouts.header')
@section('content')
<div class="header_left">
    <h1>{{ (!empty($user->id)) ? 'Edit Trial User' : 'Add Trial User'}}</h1>
            </div>
          <form method="POST" action="{{ route('trial-users-store',[!empty($user->id) ? $user->id : null ]) }}" enctype="multipart/form-data">
            @csrf
            <div class="inner-wrapper-g">
                <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                  
                  <div class="container-fluid p-0 mt-2">
                    <div class="row">
                      
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                          <label>Full Name <span style="color: red;margin-left: 0px; ">*</span></label>
                          <input type="hidden" name="id" value="{{!empty($user->id) ? $user->id : null}}">
                          <input type="text" name="full_name" value="{{ $user ? $user->name : old('full_name') }}" placeholder="Enter Name">
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
                          @if(empty($user->id))
                          <input type="text" placeholder="Enter Email" name="email" value="{{$user ? $user->email : old('email') }}">
                          @else
                          <input type="text" placeholder="Enter Email" name="email" value="{{$user ? $user->email : old('email') }}" readonly="">
                          @endif
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
                          <input type="text" maxlength="12"  data-mask="999-999-9999" placeholder="Enter Number" name="contact_no"
                        value="{{$user ? $user['userDetail']['contact_no'] : old('contact_no') }}">
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
                          <input type="text" placeholder="Enter Organization Name" name="organization_name" value="{{$user ? $user['userDetail']['organization_name'] : old('organization_name') }}">
                          @error('organization_name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                          <label>Subscription Plan <span style="color: red;margin-left: 0px; ">*</span></label>
                          <select class=" form-pik-er frm_width"name="subscription_plan">
                            <option value="">Select Plan</option>
                              @if(count($subscriptions)>0)
                                @foreach($subscriptions as $subscription)
                                  
                                  @if(empty($user->id))
                                    <option value="{{$subscription->id}}">{{ucfirst($subscription->plan_name)}}</option>
                                  @else
                                  <option value="{{$subscription->id}}" {{ $user->plan_id == $subscription->id ? 'selected' : '' }}>{{ucfirst($subscription->plan_name)}}</option>
                                  @endif
                                @endforeach
                              @endif
                          </select>
                          @error('subscription_plan')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      </div>
                       <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                          <label>Password <span style="color: red;margin-left: 0px; ">*</span></label>
                          <input type="password" placeholder="Enter Password" name="password" value="{{old('password') }}">
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
                          <input type="password" placeholder="Enter Confirm Password" name="password_confirmation" value="{{old('password_confirmation') }}">
                          @error('password_confirmation')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      </div>
                      @if(!empty($user->id))
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                          <div class="pr-la-in">
                            <label>Status</label>
                            <select class="form-pik-er frm_width" name="status" value="{{ $user ? $user->status: 1}}">
                                <option value="1" {{$user && $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{$user && $user->status == 2 ? 'selected' : '' }}>In active</option>
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
                          <label>Address</label>
                          <input type="text" placeholder="Enter Address" name="address"
                        value="{{$user ? $user['userDetail']['address'] : old('address') }}">
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
                          <input type="text" placeholder="Enter City" name="city"
                        value="{{$user ? $user['userDetail']['city'] : old('city') }}">
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
                          <input type="text" placeholder="Enter Province" name="province"
                        value="{{$user ? $user['userDetail']['province'] : old('province') }}">
                          @error('province')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                          <label>Postal Code</label>
                          <input type="text" maxlength="6" placeholder="Enter Postal Code" name="pincode"
                        value="{{$user ? $user['userDetail']['postal_code'] : old('pincode') }}">
                          @error('pincode')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      </div>
                   
                     
                    </div>
                  </div> 
                  <div class="button_section">
                    <button type="submit" class="btn blue_btn">{{(!empty($user->id)) ? 'Update' : 'Add User'}}</button>
                    <a href="{{route('trial-users')}}"><button type="button" class="btn white_btn">Cancel</button></a>
                  </div>    
                </div> 
               
            </div>
          </form>
@endsection