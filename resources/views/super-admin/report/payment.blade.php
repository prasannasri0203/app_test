<?php
 // $email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
 $user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
 $mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
 $from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : ''; 
 $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : ''; 
 $exportpayment = (isset($_GET['exportpayment']) && $_GET['exportpayment'] != '') ? $_GET['exportpayment'] : '0';  
?>
@extends('layouts.header')
@section('content')
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
            <div class="main_content">
                <div class="main_head">
                    <div class="menue_first_head">
                        <div class="list_head">
                            <h1>Payment Report </h1>
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ route('report-payment') }}" >    
                <div class="filter_by_sec">
                    <input type="text" placeholder="Name" name="user_name" value="{{$user_name}}">
                    <!-- <input type="text" placeholder="Email Address" name="email"> -->
                    <input type="text" placeholder="Contact Number" name="mobile" value="{{$mobile}}"> 
                    <input type="date" placeholder="From Date" class="datetimepicker" name="from_date" value="{{$from_date}}" >
                    <input type="date" placeholder="To Date" name="to_date" value="{{$to_date}}" >
                    <input type="hidden" name="exportpayment" value="{{$exportpayment}}" id="export_val">
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{url('/report-payment')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                         <button class="btn blue_btn export_btn" style="background:#ffc107;"> Export</button> 
                     </div>
                </div>
                </form>
                <div class="table_main_dist table-responsive">
                    <table class="distict_table">
                        <thead>
                            <tr class="tab">
                                <th>S.NO </th>    
                                <th>NAME</th>
                                <th>CONTACT NUMBER</th> 
                                <th>INVOICE ID</th>  
                                <th>@sortablelink('created_at','DATE')</th> 
                                <th>@sortablelink('renewal_date','NEXT RENEWAL DATE')</th>   
                                <th>@sortablelink('plan_id','PLAN')</th>  
                                <th>@sortablelink('coupon_id','COUPON CODE')</th>   
                                <th>@sortablelink('status','STATUS')</th>    
                                <th>@sortablelink('payment_type','PAID BY')</th> 
                                <th>AMOUNT(CAD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($payments) > 0)
                                @foreach($payments as $key=>$payment)
                                <tr>
                                    <td>{{$key + $payments->firstItem()}}</td>
                                    <td>{{ucfirst($payment['user']['name'])}}</td>
                                    <td>{{$payment['user']['userDetail']['contact_no']}}</td>
                                    <td>#KH{{$payment->plan_id}}{{$key+1}}u{{ $payment->user_id}}</td>
                                    <td>{{date('m/d/Y',strtotime($payment->created_at))}}</td>
                                    <td>{{date('m/d/Y',strtotime($payment->renewal_date))}}</td>
                                    <td style="text-transform: capitalize;">{{ optional($payment->subscription)->plan_name }}</td>
                                    <td>@if(optional($payment->coupon_id) !='')
                                        {{ optional($payment->coupon)->coupon_code }} 
                                         @else
                                            - 
                                         @endif
                                    </td>
                                    <td>@if($payment->status=='1')
                                          Success
                                       @elseif($payment->status=='2')
                                         Faild  
                                       @endif 
                                    </td>  
                                    <td>  
                                        @if($payment->payment_type=='1')
                                            Cash
                                        @elseif($payment->payment_type=='2')
                                            Cheque
                                        @elseif($payment->payment_type=='3')
                                            Online Payment
                                        @elseif($payment->payment_type=='0')
                                            @if($payment['user']['user_role_id'] == 2)
                                                -
                                            @else
                                                Offline Payment
                                            @endif 
                                        @endif 
                                    </td> 
                                    <td>@if($payment->amount !='0.00')
                                      CAD {{$payment->amount}}
                                      @else
                                            - 
                                      @endif
                                    </td> 
                                </tr>
                                @endforeach
                            @else
                                <tr> 
                                    <td class="text_cen" colspan="11">No Users Payment Reports Available</td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>        
                </div> 
            </div> 
  

      
</div>
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
    {{ $payments->appends(['user_name'=>$user_name,'mobile'=>$mobile,'from_date'=>$from_date,'to_date'=>$to_date,Request::except('page')])->links('vendor.pagination.default')}}
 </div>
</div>
</div>
@endsection