@extends('layouts.header')
@section('content')
<div class="header_left">
    <h1>@if(array_key_exists('addeditcouponvalue',$Module)) Edit Coupon  @else Add New Coupon  @endif</h1>
</div>
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
        <div class="container-fluid p-0 mt-2">
            <?php 
            if(old('coupon_name')) $couponname  = old('coupon_name');
            else $couponname =  '';                     
    

            if(old('coupon_code')) $couponcode  = old('coupon_code');
            else $couponcode =  '';

            if(old('price')) $price  = old('price');
            else $price =  '';
            if(old('discount')) $discount   = old('discount');
            else $discount  =  '';

            if(old('start_date')) $start_date  = old('start_date');
            else $start_date =  '';
            if(old('end_date')) $end_date  = old('end_date');
            else $end_date =  '';

            if(old('status')) $status    =   old('status');
            else $status =   ''; 
            if(old('amount_type')) $amount_type    =   old('amount_type');
            else $amount_type =   ''; 
            if(old('coupon_count')) $coupon_count    =   old('coupon_count');
            else $coupon_count =   ''; 
            $couponid  =   '';

            if(array_key_exists('addeditcouponvalue',$Module)){

                if(old('coupon_name')) $couponname = old('coupon_name');
                else $couponname =  $Module['addeditcouponvalue'][0]->coupon_name;
 

                if(old('coupon_code')) $couponcode = old('coupon_code');
                else $couponcode =  $Module['addeditcouponvalue'][0]->coupon_code;

                if(old('price')) $price = old('price');
                else $price = $Module['addeditcouponvalue'][0]->price;
                

                if(old('discount')) $discount  = old('discount');
                else $discount  =  $Module['addeditcouponvalue'][0]->discount;

                if(old('start_date')) $start_date = old('start_date');
                else $start_date =  $Module['addeditcouponvalue'][0]->start_date;
                 $start_date = (new DateTime($start_date))->format('m/d/Y');

                if(old('end_date')) $end_date = old('end_date');
                else $end_date =  $Module['addeditcouponvalue'][0]->end_date;                
                $end_date = (new DateTime($end_date))->format('m/d/Y');


                if(old('status')) $status    =   old('status');
                else $status =  $Module['addeditcouponvalue'][0]->status;

                if(old('amount_type')) $amount_type    =   old('amount_type');
                else $amount_type =  $Module['addeditcouponvalue'][0]->amount_type;
                
                if(old('coupon_count')) $coupon_count    =   old('coupon_count');
                else $coupon_count =  $Module['addeditcouponvalue'][0]->coupon_count;
                $couponid = $Module['addeditcouponvalue'][0]->id;
            }

            ?>
            <form action="{{url('stored-Coupon')}}" method="POST">
                @csrf
                <input type="hidden" name="coupon_id" value="{{$couponid}}"/>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Coupon Name <span style="color: red;margin-left: 0px; ">*</span></label>
                            <input type="text" class="@error('coupon_name') is-invalid @enderror" name="coupon_name" value="{{$couponname}}" placeholder="Coupon Name" autocomplete="off">
                            @error('coupon_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                   
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in">
                        <label>Coupon Code     <span style="color: red;margin-left: 0px; ">*</span></label>
                        <input type="text" class=" @error('coupon_code') is-invalid @enderror" name="coupon_code" value="{{$couponcode}}" placeholder="Coupon Code" autocomplete="off">
                        @error('coupon_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                 <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in ">
                        <label>Coupon Type <span style="color: red;margin-left: 0px; ">*</span></label>
                        <select class="form-pik-er frm_width amount_type"  name="amount_type" >
                            <option value="price" @if($amount_type=='price') selected @endif>Fixed Price</option>
                            <option value="discount" @if($amount_type=='discount') selected @endif> Percentage</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 price_col hide">
                    <div class="pr-la-in">
                        <label>Amount <small>(CAD)</small></label>
                        <input type="text" class=" @error('price') is-invalid @enderror" name="price" value="{{$price}}" placeholder="CAD Amount" autocomplete="off">
                        @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div> 
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 discount_col hide">
                    <div class="pr-la-in">
                        <label> Percentage(%)  </label>
                        <input type="text" class=" @error('discount') is-invalid @enderror" name="discount" value="{{$discount}}" placeholder="Percentage %" autocomplete="off">
                        @error('discount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div> 
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in">
                        <label>Start Date <span style="color: red;margin-left: 0px; ">*</span></label>
                        <input type="text" class="start_datepicker @error('start_date') is-invalid @enderror" name="start_date" value="{{$start_date}}" placeholder="Coupon Start Date" autocomplete="off">
                        @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in">
                        <label>End Date <span style="color: red;margin-left: 0px; ">*</span></label>
                        <input type="text" class="end_datepicker @error('end_date') is-invalid @enderror" name="end_date" value="{{$end_date}}" placeholder="Coupon End Date" autocomplete="off">
                        @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>                       

                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="pr-la-in ">
                        <label>Status</label>
                        <select class="form-pik-er frm_width"  name="status">
                            <option value="1" @if($status==1) selected @endif>Active</option>
                            <option value="0" @if($status=='0') selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>  
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in ">
                        <label>Coupon Usage <span style="color: red;margin-left: 0px; ">*</span></label>
                        <select class="form-pik-er frm_width"  name="coupon_count" >
                            <option value="">Select</option>
                            <option value="1" @if($coupon_count=='1') selected @endif>Single Use</option>
                            <option value="2" @if($coupon_count=='2') selected @endif> Multiple Use</option>
                        </select>
                        @error('coupon_count')
                        <span class="invalid-feedback" role="alert">
                            <strong>The coupon usage field is required.</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button type="submit" class="btn blue_btn">@if(array_key_exists('addeditcouponvalue',$Module)) Update   @else Add Coupon  @endif</button>
                <a href="{{url('/coupon-view')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form> 
    </div>
</div> 
</div>
@endsection
 
 