<?php 
$coupon_name = (isset($_GET['coupon_name']) && $_GET['coupon_name'] != '') ? $_GET['coupon_name'] : '';
$coupon_code = (isset($_GET['coupon_code']) && $_GET['coupon_code'] != '') ? $_GET['coupon_code'] : ''; 
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';  
?>
@extends('layouts.header')
@section('content')
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
        <div class="main_content">
            <div class="main_head">
                  @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif  
                @if (session('failure'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failure') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif
              
             <div class="menue_first_head">
                <div class="list_head">
                    <h1>Manage Coupon </h1>
                </div>
                <div class="distict_btn">
                    <a href="{{URL('add-coupon')}}"><button class="btn blue_btn">Add Coupon</button></a>
                </div>
            </div>
        </div> 
                <form action="{{URL::to('coupon-view')}}" autocomplete="off" method="get" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                     
                 <div class="filter_by_sec">
                    <input type="text" placeholder="Coupon Name" name="coupon_name"  value="{{$coupon_name}}">
                    <input type="text" placeholder="Coupon Code" name="coupon_code"  value="{{$coupon_code}}"> 
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif >Active</option>
                        <option value="0" @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{url('/coupon-view')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
                
            </div>

        </form> 


<div class="table_main_dist table-responsive">
    <table class="distict_table">
        <thead>
            <tr class="tab">
                <th>S.NO </th>  
                   <th>@sortablelink('coupon_code','COUPON CODE')</th>
                   <th>@sortablelink('coupon_name','COUPON NAME')</th>
                   <th>@sortablelink('amount_type','TYPE')</th>
                   <th>@sortablelink('discount','CAD/PERCENTAGE')</th>
                   <th>@sortablelink('start_date','START DATE')</th>
                   <th>@sortablelink('end_date','END DATE')</th>
                   <th>DATE</th>
                   <th>@sortablelink('status','STATUS')</th> 
                <th class="cen">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
             @if(count($coupons) > 0)
            @foreach($coupons as $key=>$coupon)
            <tr>
                 <td class="text_cen">{{ $key + $coupons->firstItem()}}</td>   
                <td class="text_cen">{{$coupon->coupon_code}}</td>
                <td>{{ucfirst($coupon->coupon_name)}}</td>     
                <td> @if($coupon->amount_type=='price') Price @else Percentage @endif</td> 
                <td class="text_cen">@if($coupon->amount_type=='price')<small>CAD</small> {{$coupon->price}}@else{{$coupon->discount}} %@endif</td><td>{{date('m/d/Y', strtotime($coupon->start_date))}}</td>
                <td>{{date('m/d/Y', strtotime($coupon->end_date))}}</td> 
                @if($coupon->updated_at !='')
                    <td><?php $date = explode(' ',$coupon->updated_at); echo date('m/d/Y',strtotime($date[0])); ?></td>
                @else
                    <td><?php $date = explode(' ',$coupon->created_at); echo date('m/d/Y',strtotime($date[0])); ?></td>
                @endif
                <td>@if($coupon->status=='1') Active @else Inactive @endif</td> 
                <td>
                    <div class="table_last">

                        <span><a href="{{url('/add-coupon/'.$coupon->id)}}"><img src="images/Edit.png" title="Edit"></a></span>
                        <span><a Onclick="deletecoupon(this);" data-delete-id="{{$coupon->id}}"><img src="images/Delete.png"></a></span>


                    </div>
                </td>
            </tr> 
            @endforeach
            @else
            <tr> 
                <td class="text_cen" colspan="10">No Coupon Available</td>
            </tr>
            @endif

        </tbody>
    </table>        
</div> 
</div> 
</div>
 
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
    {{ $coupons->appends([\Request::except('page'),'coupon_name'=>$coupon_name,'coupon_code'=>$coupon_code,'status'=>$status])->render('vendor.pagination.default')}}
</div>
</div>
</div>
@endsection