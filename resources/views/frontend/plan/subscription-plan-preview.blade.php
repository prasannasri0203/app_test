@extends('layouts.frontend.header')
<style>
#divLoading
{
    display:    none;     
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url("{{asset('images/loading.gif')}}") 
                50% 50% 
                no-repeat
}
</style>
@section('content') 
    <div class="container-fluid px-md-4 px-2 main_part">
        <input type="hidden" id="base_url" value="{{url('/')}}">
        <input type="hidden" id="user_plan_id" value="{{!empty($user->id) ? $plan->id : '0'}}">
        <input type="hidden" id="user_id" value="{{!empty($user->id) ? $user->id : '0'}}">
        <input type="hidden" id="user_role_id" value="{{!empty($user->id) ? $user_role_id : '0'}}">
        <input type="hidden" id="coupon_id" value="0">
        <input type="hidden" id="tax_percent" value="0">
        <input type="hidden" id="total_amt" value="{{$plan->amount}}">
        <input type="hidden" id="plan_amount" value="{{$plan->amount}}">
        <input type="hidden" id="coupon_amt_value" value="0">
        <input type="hidden" id="coupon_type" value="0">
        <div id="divLoading"><div class="loader"></div></div>
        <div class="t-c">
            <div class="cont">
                <div class="sc">
                    <h5 id="sp">Subscription Preview</h5>
                    <a href="{{ route('plan-page') }}"><h6 id="sp">Change Plan <span id="ar"><img src="{{asset('images/front_images/arrow.png')}}" alt="arrow"></span></h6></a>
                </div>
                <p id="sub">Subscription preview details for the plan of "{{ucwords($plan->plan_name)}}"</p>
                @if($plan->user_role_id == '2')
                    <p id="sub"><span id="dol">{{$plan->activation_period}}</span> days activation period</p>
                @else
                    <p id="sub"><span id="dol">CAD {{$plan->amount}}</span> / Per User / Per @if($plan->payment_type=='monthly') Month @else Year @endif</p>
                    <div class="zip-btn">
                        <div class="promo col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" autocomplete='off' id="apply_coupon_code" class="coupon_code" placeholder="Enter the coupon code">
                            <button class="btn blue_btn-2 coupon_apply">Apply Now</button>
                        </div>
                        <span class="remove_coupon" style="display: none;color:darkgreen;cursor: pointer;">Remove coupon code</span>
                    </div>
                    <span class="invalid-feedback" role="alert">
                        <strong id="coupon_err"></strong>
                    </span>
                @endif
            </div>
            @if($plan->user_role_id != '2')
            <div class="total-card total overall-review">
                <div class="t-p">
                    <p id="tot">Price for the {{ucwords($plan->plan_name)}} <span id="do">CAD {{number_format($plan->amount,2)}}</span></p> 
                    <p id="tot-b">Coupon Offer<span id="percent_val"></span><span id="do" class="coupon_amt">CAD 0.00</span></p>                         
                    <p><span>Sub Total</span><span id="tot-percentamt">CAD 0.00</span></p>
                    <input type="hidden"  id="tot-percentamt_hdn" value=""> 
                    <p id="tot-o"></p>
                    <p id="tot">Total <span id="do" class="total_cad">CAD {{number_format($plan->amount,2)}}</span></p>
                </div>
            </div>
            @endif
        </div>

        @if($plan->user_role_id == '1' || $plan->user_role_id == '3')
        <input type='hidden' id='stripeKey' value="{{ env('STRIPE_KEY') }}" />
        <!-- <input type='hidden' id='stripeKey' value="pk_test_Inc3FKbiZDAjR61SlYT5LW70000ezPoNZw" /> -->
       <input type='hidden' id='stripeToken' />
         <div class='form-row row'>
            <div class='col-md-6 error form-group hide'  style="display: none;">
                <div class='alert-danger alert alert_payment_err'>Please correct the errors and try again.</div>                
            </div>
        </div>
        <div class="bi">
            <h5 id="sp">Billing Information<span style="color: red">*</span></h5>
            <div id="fl">
                <input id="b-inp" type="text" class="firstname" placeholder="First Name">
                <input id="b-inp" type="text" class="lastname" placeholder="Last name">
            </div>
            <input id="p-inp" class="line1" type="text" placeholder="Address Line1">
            <div id="fl">
                <input id="b-inp" type="text" class="city" placeholder="City">
                <input type="hidden" value="{{url('/')}}" id="baseurl">
                <select id="b-inp" type="text" class="state" placeholder="State" onchange="statePercentage()">
                    <option value="">Select State</option>
                    @foreach($states as $value)
                       <option value="{{$value->id}}">{{$value->states_name}}</option>
                    @endforeach
                </select>   
            </div>
            <div id="fl">
                <input id="b-inp" type="text" class="country" placeholder="Country">
                <input id="b-inp" type="text" class="postal_code" placeholder="Postal Code">
            </div>
        </div> 
         <div class="pm">
            <h5 id="sp">Card Details<span style="color: red">*</span></h5>
            <div class="card-tot-split">
                <div class="cardsplit">
                    <div id="sb">
                        <img class="card-s" src="{{asset('images/front_images/master-card.png')}}" alt="card">
                        <img class="card-s" src="{{asset('images/front_images/visa.png')}}" alt="card">
                        <img class="card-s" src="{{asset('images/front_images/american-epress.png')}}" alt="card">
                        <img class="card-s" src="{{asset('images/front_images/discover.png')}}" alt="card">

                    </div>
                    <div class="c-c">
                        <input id="p-inp ca" autocomplete='off' class="card-number" onkeypress="return isNumberKey(event)" maxlength='16' type="text" placeholder="Enter Card Number">
                         <!-- <img class="cc" src="{{asset('images/front_images/card.png')}}" alt="card">  -->
                    </div>
                    <div id="fl">
                        <input id="b-inp" type="text" autocomplete='off' onkeypress="return isNumberKey(event)" class="card-expiry-month" placeholder="Expiration Month ex.12" maxlength='2'>
                        <input id="b-inp" type="text" autocomplete='off' onkeypress="return isNumberKey(event)" class="card-expiry-year" maxlength='4' placeholder="Expiration Year ex.2024">
                        
                         <!-- <img class="cvv" src="{{asset('images/front_images/queston.png')}}" alt="card">  -->
                    </div>
                    <div id="fl">
                        <input id="b-inp cvv" autocomplete='off' maxlength='3' onkeypress="return isNumberKey(event)" class="card-cvc" type="password" placeholder="CVV ex.311">
                    </div>
                </div> 
                 
            </div> 
            <div class="button_lp">
                <a href="{{url('/plan-setting')}}"><button class="btn white_btn">Cancel</button></a>
                <button class="btn blue_btn stripesubmit">Pay Now</button>

            </div>
        </div>
        @endif
        
    </div>
@endsection
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>