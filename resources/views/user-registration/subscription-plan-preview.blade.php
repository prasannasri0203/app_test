<!DOCTYPE html>
<html lang="en">

<head>
    <title>Subscription</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css link -->
    <link rel="icon" type="image/png" href="{{asset('images/fevi.png')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/style.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- responsive link -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_responsive.css')}}">

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
</head>

<body>
    <div class="page-wrapper">

        <!-- main section -->
        <main class="page-c">
            <div class="dash-r">
                <div class="logo payment_logo">
                    <img src="{{asset('images/kaizen-logo 1.png')}}">
                    <!-- <img src="{{asset('images/front_images/user_module/kaizen-logo.png')}}" alt="kaizen"> -->
                </div>
            </div>
            <input type="hidden" value="{{url('/')}}" id="baseurl">
            <input type="hidden" id="user_plan_id" value="{{!empty($user->id) ? $user->plan_id : '0'}}">
            <input type="hidden" id="user_id" value="{{!empty($user->id) ? $user->id : '0'}}">
            <input type="hidden" id="user_role_id" value="{{!empty($user->id) ? $user->user_role_id : '0'}}">
            <input type="hidden" id="coupon_id" value="0">
            <input type="hidden" id="tax_percent" value="0">
            <input type="hidden" id="total_amt" value="{{$plan->amount}}">
            <input type="hidden" id="plan_amount" value="{{$plan->amount}}">
            <input type="hidden" id="coupon_amt_value" value="0">
            <input type="hidden" id="coupon_type" value="0">            
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            @if (Session::has('failure'))
                <div class="alert alert-danger text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('failure') }}</p>
                </div>
            @endif

            <div id="divLoading"><div class="loader"></div></div>
            
            <section class="whole">
                <div class="t-c">
                    <div class="cont">
                        <div class="sc">
                            <h5 id="sp">Subscription Preview</h5>
                            <a href="{{ route('edit.plan',[!empty($user->id) ? $user->id : 0 ]) }}"><h6 id="sp">Change Plan <span id="ar"><img src="{{asset('images/front_images/arrow.png')}}" alt="arrow"></span></h6></a>
                        </div>                        
                        <p id="sub">Subscription preview details for the plan of "{{ucwords($plan->plan_name)}}"</p>
                        @if($plan->user_role_id == '2')
                            <p id="sub"><span id="dol">{{$plan->activation_period}}</span> days activation period</p>
                        @else
                            <p id="sub"><span id="dol">CAD {{$plan->amount}}</span> / Per User / Per @if($plan->payment_type=='monthly') Month @else Year @endif</p>
                            <div class="zip-btn">
                                <div class="promo col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <input type="text" id="apply_coupon_code" autocomplete="off" class="coupon_code" placeholder="Enter the coupon code">
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
                    <div class=" total-card-reg total overall-review">
                        <div class="t-p">
                            <p id="tot">Price for the {{ucwords($plan->plan_name)}} <span id="do">CAD {{number_format($plan->amount,2)}}</span></p> 
                            <p id="tot-b">Coupon Offer<span id="percent_val"></span><span id="do" class="coupon_amt">CAD 0.00</span></p>                           
                            
                            <p><span>Sub Total</span><span id="tot-percentamt">CAD 0.00</span></p>
                            <p id="tot-o"></p>
                            <input type="hidden"  id="tot-percentamt-hdn" value="">
                            <p id="tot">Total <span id="do" class="total_cad">CAD {{number_format($plan->amount,2)}}</span></p>                            
                        </div>
                    </div>
                    @endif
                </div>

                @if($plan->user_role_id == '1' || $plan->user_role_id == '3')
                <input type='hidden' id='stripeKey' value="{{ env('STRIPE_KEY') }}" />
                <!-- <input type='hidden' id='stripeKey' value="pk_test_Inc3FKbiZDAjR61SlYT5LW70000ezPoNZw"/> -->
                <input type='hidden' id='stripeToken' />
                 <div class='form-row row'>
                    <div class='col-md-12 error form-group hide'  style="display: none;">
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
                        <select id="b-inp" type="text" class="state" id="state" placeholder="State" onchange="statePercentage();">
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
                    <div class="card-tot-split-reg">
                        <div class="cardsplit">
                            <div id="sb">
                                <img class="card-s" src="{{asset('images/front_images/master-card.png')}}" alt="card">
                                <img class="card-s" src="{{asset('images/front_images/visa.png')}}" alt="card">
                                <img class="card-s" src="{{asset('images/front_images/american-epress.png')}}" alt="card">
                                <img class="card-s" src="{{asset('images/front_images/discover.png')}}" alt="card">

                            </div>
                            <div class="c-c">
                                <input id="p-inp ca" autocomplete='off' onkeypress="return isNumberKey(event)" class="card-number" maxlength='16' type="text" placeholder="Enter Card Number">
                                 <!-- <img class="cc" src="{{asset('images/front_images/card.png')}}" alt="card">  -->
                            </div>
                            <div id="fl">
                                <input id="b-inp" onkeypress="return isNumberKey(event)" type="text" class="card-expiry-month" autocomplete='off' placeholder="Expiration Month ex.12" maxlength='2'>
                                <input id="b-inp" onkeypress="return isNumberKey(event)" type="text" class="card-expiry-year" autocomplete='off' maxlength='4' placeholder="Expiration Year ex.2024">
                                
                                 <!-- <img class="cvv" src="{{asset('images/front_images/queston.png')}}" alt="card">  -->
                            </div>
                            <div id="fl">
                                <input id="b-inp cvv" onkeypress="return isNumberKey(event)" autocomplete='off' maxlength='3' class="card-cvc" type="password" placeholder="CVV ex.311">
                            </div>
                            </div>
                          
                    </div>
                    
                    <div class="button_lp">
                        <a href="{{ url('/') }}"><button class="btn white_btn">Cancel</button></a>
                        <button class="btn blue_btn stripesubmit">Pay Now</button>

                    </div>
                </div>

                @endif
                @if($plan->user_role_id == '2')
                   <div class="button_lp">
                        <a href="{{ url('/') }}"><button class="btn white_btn">Cancel</button></a>
                        <button class="btn blue_btn submit">Submit</button>

                    </div>
                @endif
            </section>
        </main>

    </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('input').attr('autocomplete','off');
    });
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    var taxvals =[];
    var taxvals_name =[];   
    $('.remove_coupon').click(function(){
        var total_amt =$('#plan_amount').val();
        $('#percent_val').html('');
        $('.coupon_amt').html('CAD 0.00');
        $('#total_amt').val(total_amt);
        $('.total_cad').html('CAD '+total_amt);
        $('#coupon_id').val('0');
        $('.coupon_code').val('');
        $('.coupon_code').focus(); 
        $('.remove_coupon').hide();
        $('#coupon_type').val(0);
        $('#coupon_amt_value').val(0);
        addTaxAmt(taxvals,taxvals_name);                    
        return false;
    });
    $('.coupon_apply').click(function(){
        var coupon_code = $('.coupon_code').val();
        var user_id =$('#user_id').val();
        var user_plan_id =$('#user_plan_id').val();
        var total_amt = $('#total_amt').val();
        $('#coupon_err').html('');
        $('.remove_coupon').hide();
        $('#coupon_amt_value').val(0);
        $('#percent_val').hide();
        if(coupon_code == ''){
            $('#coupon_err').html('Enter the coupon code!');
            $('.coupon_code').focus();
            return false;
        }else{
            let URL =  '{{ route("coupon-apply") }}';
            $.post(URL,
            {
                "_token": "{{ csrf_token() }}",
                "code": coupon_code,
                "user_id": user_id,
                "user_plan_id":user_plan_id,
                "total_amt":total_amt
            },
            function(response) {                
                if(response['status'] == '1'){
                    $('.coupon_amt').html('CAD '+response['coupon_amt']);
                    $('.total_cad').html('CAD '+response['total_amt']);
                    $('#coupon_amt_value').val(response['coupon_amt']);
                    $('#coupon_id').val(response['coupon_id']);
                    $('#total_amt').val(response['total_amt']);
                    if(response['type'] == 'discount'){
                        $('#percent_val').html('('+response['discount']+'%)');
                        $('#percent_val').show();
                    }
                    $('.remove_coupon').show();
                    $('#coupon_type').val(response['type']);
                    addTaxAmt(taxvals,taxvals_name);
                }else if(response['status'] == '2' ||  response['status'] == '3'){
                    if(response['status'] == '2'){
                        $('#coupon_err').html('Coupon code has been expired!');
                    }else{
                        $('#coupon_err').html('You can not use this coupon code now!');
                    }
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }else if(response['status'] == '4'){
                    $('#coupon_err').html('Coupon amount is greater than your plan amount!');
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }else{
                    $('#coupon_err').html('Invalid coupon code!');
                    $('#percent_val').html('');
                    $('.coupon_amt').html('CAD 0.00');
                    $('#total_amt').val(response['plan_amt']);
                    $('.total_cad').html('CAD '+response['plan_amt']);
                    $('#coupon_id').val('0');
                    $('.coupon_code').val('');
                    $('.coupon_code').focus();
                    addTaxAmt(taxvals,taxvals_name);
                    return false;
                }
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log('failure');
            });
        }
    })
    $('.submit').click(function(){
        var coupon_id = $('#coupon_id').val();
        var user_id =$('#user_id').val();
        var user_plan_id =$('#user_plan_id').val();
        var total_amt =$('#total_amt').val();
        var user_role_id =$('#user_role_id').val();
        $('.loader').html('Processing...');
        $('#divLoading').show();
        let URL =  '{{ route("payment") }}';
        $.post(URL,
        {
            "_token": "{{ csrf_token() }}",
            "coupon_id": coupon_id,
            "user_id": user_id,
            "user_role_id":user_role_id,
            "user_plan_id":user_plan_id,
            "total_amt":total_amt
        },
        function(response) {
            $('#divLoading').hide();
            if(response['status'] == '1'){
                let redirect_url = "{{ url('success-payment/') }}/"+user_id;
                window.location = redirect_url;
            }else{
                //alert('Something went wrong!');
                console.log(response['status']);
                return false;
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log('failure');
        });
        
    });
    $('.stripesubmit').click(function(){  
        //payemnt details  
        var fname =$('.firstname').val();
        var lname =$('.lastname').val();
        var name = fname+''+lname;
        var line1 =$('.line1').val();
        var city =$('.city').val();
        var state =$('.state').val();
        var country =$('.country').val();
        var postal_code =$('.postal_code').val();
        var cardno =$('.card-number').val();
        var cvv =$('.card-cvc').val();
        var expirymon =$('.card-expiry-month').val();
        var expiryyr =$('.card-expiry-year').val();
        var total_amt =$('#total_amt').val();
        // alert(total_amt);
        if(name == '' || line1 == '' || city == '' || state == '' || country == '' || postal_code == '' || cardno == '' || cvv == '' || expirymon == '' || expiryyr == ''){
            $('.error').show();
            $('.error').find('.alert').text('Please make sure you have entered all details in Billing Information and Card Details');
            return false;
        }else{
            $('.loader').html('Payment is processing...');
            $('#divLoading').show();
            Stripe.setPublishableKey($('#stripeKey').val());
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
              }, stripeResponseHandler); 
        }       
        
    });
    function stripeResponseHandler(status, response) {

         
        if (response.error) {
            $('#divLoading').hide();
            $('.error').show();
            $('.error').find('.alert').text(response.error.message);
        } else {
             // $('#divLoading').show(); 
            var token = response['id'];
            $('#stripeToken').val(token);
            var coupon_id = $('#coupon_id').val();
            var user_id =$('#user_id').val();
            var user_plan_id =$('#user_plan_id').val();
            var plan_amt = $('#plan_amount').val();
            var sub_amt = $('#tot-percentamt-hdn').val();
            var total_amt =$('#total_amt').val();
            var user_role_id =$('#user_role_id').val();
            var stripetoken =$('#stripeToken').val();
            var fname =$('.firstname').val();
            var lname =$('.lastname').val();
            var name = fname+''+lname;
            var line1 =$('.line1').val();
            var city =$('.city').val();
            var state =$('.state').val();
            var tax_percentage = $('#tax_percent').val();
            var country =$('.country').val();
            var postal_code =$('.postal_code').val(); 
            let URL =  '{{ route("payment") }}';
            if(stripetoken != ''){
                $.post(URL,
                {
                    "_token": "{{ csrf_token() }}",
                    "coupon_id": coupon_id,
                    "user_id": user_id,
                    "user_role_id":user_role_id,
                    "user_plan_id":user_plan_id,
                    "plan_amt":plan_amt,
                    "sub_amt":sub_amt,
                    "total_amt":total_amt,
                    "stripetoken":stripetoken,
                    "username":name,
                    "line1":line1,
                    "city":city,
                    "state":state,
                    "tax_percentage":tax_percentage,
                    "country":country,
                    "postal_code":postal_code,
                },
                function(response) {

                      $('#divLoading').hide();
                    console.log(response);

                    if(response['status'] == '1'){
                        let redirect_url = "{{ url('success-payment/') }}/"+user_id;
                        window.location = redirect_url;
                    }else if(response['status'] == '2'){
                        if(response['msg'] != ''){
                            $('.error').show();
                            $('.error').find('.alert').text(response['msg']);
                        }
                        return false;
                    }else{
                        alert('Something went wrong!');
                        return false;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log('failure');
                });
            }
        }
    }
    function statePercentage(){
        var state_id= $('.state').val();
        var total = $('#plan_amount').val();
        var coupon_amt = $('#coupon_amt_value').val();
        var baseurl = $('#baseurl').val();  
        var tot_percent_amt =0; 
        var tot_tax_percent =0;
        var stype='';
        let URL =  '{{ route("get-state-tax") }}';
        $.post(URL,
            {
                "_token": "{{ csrf_token() }}",
                "state_id": state_id
            },
            function(response) {                
                // console.log(response);
                taxvals =[];
                taxvals_name =[];  
                if(response !='0'){
                    $.each(response, function (key, value) {
                        if(value.gst != 0) {
                            taxvals.push(value.gst);
                            taxvals_name.push("GST");
                        }
                        if(value.pst != 0) { 
                            taxvals.push(value.pst);
                            taxvals_name.push("PST");
                        }
                        if(value.hst != 0) { 
                            taxvals.push(value.hst);
                            taxvals_name.push("HST"); 
                        }
                        if(value.qst != 0) { 
                            taxvals.push(value.qst);
                            taxvals_name.push("QST"); 
                        }
                        tot_tax_percent+=parseFloat(value.gst)+parseFloat(value.pst)+parseFloat(value.hst)+parseFloat(value.qst);                    
                    });
                    $('#tax_percent').val(tot_tax_percent);
                    addTaxAmt(taxvals,taxvals_name);
                }else{
                    $('#tax_percent').val(0);
                    document.getElementById("tot-o").innerHTML = '<span id="tax_val">(0%)</span><span id="tax_percentage" class="coupon_amt1"> CAD 0.00';                    
                    addTaxAmt(taxvals,taxvals_name);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log('failure');
            });        
    };
    function addTaxAmt(taxvals,taxvals_name){
        var total = $('#plan_amount').val();
        var coupon_type =$('#coupon_type').val();
        var coupon_amt = $('#coupon_amt_value').val();
        var tax_percent = $('#tax_percent').val();
        //var percentage_amt = total*(tax_percent/100);
        var sub_total='';
        if(coupon_type == 'price'){
            //var total_val =(parseFloat(total)+parseFloat(percentage_amt)-parseFloat(coupon_amt)).toFixed(2);
            $('.coupon_amt').html('CAD '+coupon_amt); 
            sub_total=(parseFloat(total)-parseFloat(coupon_amt)).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+sub_total; 
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }else if(coupon_type == 'discount'){
            var taxtotal = ((parseFloat(total)*parseFloat(coupon_amt))/100).toFixed(2);
            $('.coupon_amt').html('CAD '+taxtotal);  
            sub_total=(parseFloat(total)-parseFloat(taxtotal)).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+sub_total;
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }else{
            $('.coupon_amt').html('CAD 0.00'); 
            sub_total= parseFloat(total).toFixed(2);
            document.getElementById("tot-percentamt").innerHTML = 'CAD '+parseFloat(total).toFixed(2);
            var percentage_amt =(sub_total*tax_percent)/100;
            var total_val =(parseFloat(sub_total)+parseFloat(percentage_amt)).toFixed(2);
        }     
        $('.total_cad').html('CAD '+total_val);
        $('#total_amt').val(total_val);
        $('#tot-o').empty();
        if(taxvals.length > 0){
            var state_tax='<table style="width: 100%;" class="state_tax_tbl"><tbody>';
            $.each(taxvals, function (key2, value2) {
                var tax_amt = parseFloat(value2);
                var per_amt = sub_total*(tax_amt/100);
                state_tax+='<tr><td class="tax_td"><span id="tax_val">'+taxvals_name[key2]+'('+tax_amt+'%)</span></td><td class="tax_amt_td"><span id="tax_percentage" class="coupon_amt1"> CAD ' +per_amt.toFixed(2)+'</td>';
            });
            state_tax+='</<tbody></table>';
            document.getElementById("tot-o").innerHTML=state_tax;
        }else{
            document.getElementById("tot-o").innerHTML = '<span id="tax_val">(0%)</span><span id="tax_percentage" class="coupon_amt1"> CAD 0.00';
        }
    }
</script>
</body>

</html>