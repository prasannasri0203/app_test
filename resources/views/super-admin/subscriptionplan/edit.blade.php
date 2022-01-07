@extends('layouts.header')
@section('content')
    <div class="header_left">
        <h1>@if(array_key_exists('editvalues',$Module)) Edit Subscription Plan  @else Add Subscription Plan  @endif</h1>
    </div>
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
            <div class="container-fluid p-0 mt-2">
                <?php 
                    if(old('plan_name')) $planname    =   old('plan_name');
                    else $planname          =   '';

                    if(old('user_role_id')) $roleid    =   old('user_role_id');
                    else $roleid          =   '';
                    
                    if(old('plan_type')) $plantype    =   old('plan_type');
                    else $plantype          =   '';
                    
                    if(old('activation_period')) $activate_period    =   old('activation_period');
                    else $activate_period          =   '';

                    if(old('display_in_site')) $display_in_site    =   old('display_in_site');
                    else $display_in_site          =   '';
                     
                    if(old('status')) $status    =   old('status');
                    else $status          =   '';

                    if(old('paid_amount')) $paid_amount    =   old('paid_amount');
                    else $paid_amount          =   ''; 

                    if(old('description')) $description    =   old('description');
                    else $description          =   '';
                     
                    if(old('based_on')) $based_on    =   old('based_on');
                    else $based_on          =   '';
                     
                    $planid            =   '';
                  
                    if(array_key_exists('editvalues',$Module)){

                        if(old('plan_name')) $planname    =   old('plan_name');
                        else $planname          =   $Module['editvalues'][0]->plan_name;

                        if(old('user_role_id')) $roleid    =   old('user_role_id');
                        else $roleid    =   $Module['editvalues'][0]->user_role_id;

                        if(old('plan_type')) $plantype    =   old('plan_type');
                        else $plantype          =   $Module['editvalues'][0]->plan_type;

                        if(old('activation_period')) $activate_period    =   old('activation_period');
                        else $activate_period   =   $Module['editvalues'][0]->activation_period;

                        if(old('display_in_site')) $display_in_site    =   old('display_in_site');
                        else $display_in_site   =   $Module['editvalues'][0]->display_in_site;

                        if(old('status')) $status    =   old('status');
                        else $status            =   $Module['editvalues'][0]->status;
                        
                        if(old('paid_amount')) $paid_amount    =   old('paid_amount');
                        else $paid_amount       =   $Module['editvalues'][0]->amount; 

                        if(old('description')) $description    =   old('description');
                        else $description       =   $Module['editvalues'][0]->description;

                        if(old('based_on')) $based_on    =   old('based_on');
                        else $based_on          =   $Module['editvalues'][0]->payment_type;
                        
                        $planid            =   $Module['editvalues'][0]->id;
                    }
                    

                ?>
                <form action="{{url('savesubscription')}}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{$planid}}"/>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Plan Name <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="text" name="plan_name" value="{{$planname}}" placeholder="Plan Name" autocomplete="off">
                                @error('plan_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                       
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Role Type</label>
                               <select class="form-pik-er frm_width role_type" name="role_type" >
                                    <option value="">Select Role Type</option>
                                    @if(count($Module['rolelist'])>0)  
                                        @foreach($Module['rolelist'] as $rolename)  
                                            <option value={{$rolename->id}} @if($roleid==$rolename->id) selected @endif>{{$rolename->role}}</option>  
                                        @endforeach
                                    @endif
                                </select>
                                @error('role_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Plan Type</label>
                                <input type="text" name="plan_type" value="{{$plantype}}" autocomplete="off" readonly>
                                @error('plan_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 paid_col">
                            <div class="pr-la-in">
                                <label>Number Of Days</label>
                                <input type="text" placeholder="Activation period" name="activation_period" value="{{$activate_period}}" autocomplete="off" onkeypress="return isNumberKey(event)">
                                @error('activation_period')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Display In Site</label>
                                 <select class="form-pik-er frm_width" name="display_in_site">
                                    <option value="1" @if($display_in_site==1) selected @endif>Yes</option>
                                    <option value="0" @if($display_in_site==0) selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Status</label>
                                 <select class="form-pik-er frm_width" name="status">
                                    <option value="1" @if($status==1) selected @endif>Active</option>
                                    <option value="0" @if($status==0) selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 trail_col hide">
                            <div class="pr-la-in">
                                <label>Paid Amount (CAD) <span style="color: red;margin-left: 0px; ">*</span></label>
                                <input type="text" placeholder="Paid Amount" name="paid_amount" value="{{$paid_amount}}" onkeypress="return isDecimalNumber(event, this)">
                                @error('paid_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
								@enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 trail_col hide">
                            <div class="pr-la-in">
                                <label>Based On</label>
                                <select class="form-pik-er frm_width"  name="based_on">
                                    <option value="monthly" @if($based_on=='monthly') selected @endif>Monthly</option>
                                    <option value="yearly" @if($based_on=='yearly') selected
                                    @endif>Yearly</option>
                                </select>
                            </div>  
                        </div>
                         <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                <label>Plan Description</label>

                                <textarea type="text" class=""  row="2" placeholder="Plan Description"  name="description">{{$description}}</textarea>
                                <span></span>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="button_section">
                        <button type="submit" class="btn blue_btn">@if(array_key_exists('editvalues',$Module)) Update Plan  @else Add Plan   @endif</button>
                        <a href="{{url('/super-subscription-plan')}}"><button type="button" class="btn white_btn">Cancel</button></a>
                    </div>
                </form> 
            </div>
        </div> 
    </div>
@endsection