@extends('layouts.frontend.header')
@section('content')
<div class="container-fluid px-4 main_part">
    <div class="t-h">
        <a href="{{url('/account-setting')}}">
            <h4 class="Acc_Setting bl">Account Settings</h4>
        </a>
            <a href="{{url('/change-password')}}">
                <h4 class="Acc_Setting">Change Password</h4>
            </a>
        @if(Auth::user()->parent_id==0)
            <a href="{{url('/plan-setting')}}">
                <h4 class="Acc_Setting pl">Plan and Billings</h4>
            </a>
        @endif
    </div>
    @if($planstatus == 1)
        <div class="alert alert-success" role="alert">
            Your plan has been upgraded successfully!
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if($planstatus == 2)
        <div class="alert alert-danger" role="alert">
            <a href="{{url('/planchange')}}" style="cursor: pointer;color: #000;">Your plan has expired. Please Renew or Upgrade your plan.</a>
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if(session('status'))
        <div class="alert alert-danger" role="alert">
            {{ session('status') }}
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    <div id="plan_turn_alert"></div>

    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
            <h6 id="cp">Current Plan</h6>
            <div class="chart">
                <div class="c-img">
                <img src="{{asset('images/front_images/chart.png')}}" alt="chart">
            </div>
            <div class="kc">
                <div class="k-cont">
                    <img class="k-l" src="{{asset('images/kaizen_logo_new.png')}}" alt="chart">
                    <button class="fr">{{$rolename}}</button>                    
                    <p id="dv">{{$planname}} </p>  
                </div>
                <input type="hidden" id ="user_id" name="user_id" value="{{$user_id}}">
                <div class="k-cont">
                    @if(@$stripe_payment_collection)
                        <p></p>
                        <p id="dv">Turn OFF/ON : </p>
                        <input type="radio" class="plan_turn" id="plan_turn_on" name="plan_turn" @if($stripe_payment_collection->stripe_payment_collection_status == 1) ? checked : ''; @endif value="1">
                        <label>ON</label>
                        <input type="radio" class="plan_turn" id="plan_turn_off" name="plan_turn" @if($stripe_payment_collection->stripe_payment_collection_status == 0) ? checked : ''; @endif value="0">
                        <label>OFF</label>                    
                    @endif
                </div>
                
                <div id="change_btn">
                    
                    <a href="{{url('/planchange')}}" style="color: white;"><button class="btn change_plan_btn mb-lg-0 mb-3" style="background: #179FD7;color: white !important;font-size: 12px;border-radius: 10px;" type="btn">Upgrade Plan</button></a>
                   
                </div>
                
            </div>
        </div>
        <h6 id="bh">Billing history</h6>
            <div class="container-fluid p-0 mt-2 table-responsive">
                <table class="distict_table">
                    <thead>
                        <tr class="tab">
                            <th>S.No </th>
                            <th>#Invoice</th> 
                            <th>Plan</th>                            
                            <th>Coupon Code</th>                            
                            <th>Renewal Date</th>
                            <th>Updated On</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                         @if(count($user_plans) > 0)
                                @foreach($user_plans as $key=>$user)
                                <?php 
                                 if($user->renewal_coupon_id !=0){
                                  $couponname=DB::table('coupons')->where('id',$user->renewal_coupon_id)->select('*')->get();
                                 }
                                 $color="powderblue";

                                ?>
                                <tr  @if($user->renewal_is_activate =='1') style="background-color:{{$color}}  !important;" @endif>
                                    <td>{{++$key}}</td>  
                                    <td> #KH00{{ucfirst($user->id)}}{{++$key}}</td> 
                                    <td>{{ucfirst($user->plan_name)}}</td> 
                                     <td>@if($user->renewal_coupon_id !='0')
                                           {{$couponname[0]->coupon_code}}
                                       @elseif($user->renewal_coupon_id =='0')
                                          - 
                                       @endif
                                     </td>  
                                    <td>{{date('m/d/Y', strtotime( $user->renewaldate));}}</td> 
                                    <td>@if($user->renewal_is_activate =='0'){{date('m/d/Y', strtotime( $user->renewal_updated_at));}}@else{{date('m/d/Y', strtotime( $user->renewal_updated_date));}}@endif</td> 
                                    <td  class="cen"><small>CAD</small> {{number_format($user->renewal_amt,2)}}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="5">No Plans Available</td>
                                </tr>
                            @endif
                        
                    </tbody>
                </table>
            </div>
        </div>

<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
    {{ $user_plans->links('vendor.pagination.default')}}
</div>
</div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    
    $('.plan_turn').on('change', function() {

      var plan_turn = document.querySelector('input[name="plan_turn"]:checked').value;
      var user_id = $('#user_id').val();
      if(plan_turn == 0){
        var confirm_data = confirm("Do you want to stop your subscription ?");
      }else if(plan_turn ==1){
        var confirm_data = confirm("Do you want to resume your subscription ?");
      }
 
      if(confirm_data){
            $.ajax({
                url: '/user-subscription-plan',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
                data: { 
                    user_id:user_id,
                    plan_turn:plan_turn
                },
                success: function(response){ 

                    if(response == 0){
                        $("#plan_turn_alert").html('<div class="alert alert-success plan_turn_alert" role="alert">'+
                        'Your subscription has been stoped.'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span></button>'+
                        '</div>');

                    }else if(response == 1){

                        $("#plan_turn_alert").html('<div class="alert alert-success plan_turn_alert" role="alert">'+
                        'Your subscription resumed.'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span></button>'+
                        '</div>');
                    }
                }
            });
        }
  
});
});

</script>