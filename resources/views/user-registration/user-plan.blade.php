<!DOCTYPE html>


<html>

<head>
    <title>Plan</title>
    <meta charset="utf-8">
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

</head>
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
<body>
    <div class="page-wrapper">



        <!-- main section -->

        <main class="page-cont">
            <div id="divLoading"><div class="loader"></div></div>
            <div class="cards">
                <div class="login_plan">
                    <div class="pp-lg">
                    <img src="{{asset('images/kaizen-logo 1.png')}}">
                </div>
                <div class="pp-cont">
                    <h3>Subscription Plans</h3>
                    <p>Choose the plan to suitable for you</p>
                </div>
                <div class="nb">&nbsp</div>
                </div>
                <input type="hidden" id="user_plan_id" value="{{!empty($user->id) ? $user->plan_id : '0'}}">
                <input type="hidden" id="db_user_plan_id" value="{{!empty($user->id) ? $user->plan_id : '0'}}">
                <input type="hidden" id="user_role_id" value="{{!empty($user->id) ? $user->user_role_id : '0'}}">
                <input type="hidden" id="db_user_role_id" value="{{!empty($user->id) ? $user->user_role_id : '0'}}">
                <input type="hidden" id="user_id" value="{{!empty($user->id) ? $user->id : '0'}}">
                <input type="hidden" id="user_plan_type" value="register">
                <div class="row cards-p mx-auto">
                    <?php $enterpriserPlans =[];
                    $trialPlans =[];
                    $individualPlans =[];
                    $teamPlans =[];
                    if(count($plans) > 0){
                    foreach($plans as $plan){
                        if($plan->user_role_id == '2'){
                            $trialPlans[] = array('id'=>$plan->id,'user_role_id'=>$plan->user_role_id,'plan_name'=>$plan->plan_name,'plan_type'=>$plan->plan_type,'activation_period'=>$plan->activation_period,'amount'=>$plan->amount,'payment_type'=>$plan->payment_type,'description'=>$plan->description);
                        }else if($plan->user_role_id == '1'){
                            $teamPlans[] = array('id'=>$plan->id,'user_role_id'=>$plan->user_role_id,'plan_name'=>$plan->plan_name,'plan_type'=>$plan->plan_type,'activation_period'=>$plan->activation_period,'amount'=>$plan->amount,'payment_type'=>$plan->payment_type,'description'=>$plan->description);
                        }else if($plan->user_role_id == '3'){
                            $individualPlans[] = array('id'=>$plan->id,'user_role_id'=>$plan->user_role_id,'plan_name'=>$plan->plan_name,'plan_type'=>$plan->plan_type,'activation_period'=>$plan->activation_period,'amount'=>$plan->amount,'payment_type'=>$plan->payment_type,'description'=>$plan->description);
                        }else if($plan->user_role_id == '4'){
                            $enterpriserPlans[] = array('id'=>$plan->id,'user_role_id'=>$plan->user_role_id,'plan_name'=>$plan->plan_name,'plan_type'=>$plan->plan_type,'activation_period'=>$plan->activation_period,'amount'=>$plan->amount,'payment_type'=>$plan->payment_type,'description'=>$plan->description);
                        }
                    }

                    $individualPlans = array_merge($individualPlans,$teamPlans); 
                    } ?> 
                    @if(count($trialPlans) > 0)                  
                        @foreach($trialPlans as $tplan)                       
                        <div class="cs col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-5">
                            <div class="card mr-4 plandet" style="width:200px">
                                <input type="hidden" id="role_{{$tplan['id']}}" value="{{$tplan['user_role_id']}}">
                                <div class="card-body card-c {{(!empty($user->id) && $user->plan_id == $tplan['id']) ? 'mb-ic' : ''}}" data-id="{{$tplan['id']}}">
                                    <input type="hidden" id="planname_{{$tplan['id']}}" value="{{ucwords($tplan['plan_name'])}}">
                                    <div class="iv">
                                        <h5 class="card-title text-center">{{ucwords($tplan['plan_name'])}}</h5>
                                    </div>

                                    <p class="card-title dollar text-center">Free</p>
                                    <p class="card-text text-center">{{$tplan['activation_period']}} Days</p>
                                     <!-- <p>{{$tplan['description']}}</p> -->
                                      <div class="disc-card">     
                                            <input type="hidden" id="plan_desc_{{$tplan['id']}}" value="{{$tplan['description']}}">            
                                                  <?php  $stringCut = substr($tplan['description'], 0, 90); ?>
                                                    <p class="desc_plan">{{$stringCut}}</p>
                                                    @if(strlen($tplan['description'] ) > 90)
                                                     <a  title="See Description" onclick='plan_desc("{{$tplan['id']}}")' href="#see_more_reg" data-toggle="modal" class="see-more-plan"> ... See More </a>
                                                    @endif 
                                            </div> 
                                    <div class="ig">
                                        <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                        <img class="tick" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        @endforeach
                    @endif
                    @if(count($individualPlans) > 0) 
                        @foreach($individualPlans as $iplan) 
                        <div class="cs col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-5">
                            <div class="card mr-4 plandet" style="width:200px">
                                <input type="hidden" id="role_{{$iplan['id']}}" value="{{$iplan['user_role_id']}}">
                                <div class="card-body card-c {{(!empty($user->id) && $user->plan_id == $iplan['id']) ? 'mb-ic' : ''}}" data-id="{{$iplan['id']}}">
                                    <input type="hidden" id="planname_{{$iplan['id']}}" value="{{ucwords($iplan['plan_name'])}}">
                                    <div class="iv">
                                        <h5 class="card-title text-center">{{ucwords($iplan['plan_name'])}}</h5>
                                        
                                    </div>
                                    <div class="plan_month">
                                        <p id="st">Starts At</p>
                                        <p class="card-title dollar text-center">CAD {{$iplan['amount']}}</p>
                                        <p class="card-text text-center">Per @if($iplan['payment_type']=='monthly') Month @else Year @endif</p>
                                    </div>
                                      <!-- <p>{{$iplan['description']}}</p> -->
                                        <div class="disc-card">     
                                            <input type="hidden" id="plan_desc_{{$iplan['id']}}" value="{{$iplan['description']}}">            
                                                  <?php  $stringCut = substr($iplan['description'], 0, 90); ?>
                                                    <p class="desc_plan">{{$stringCut}}</p>
                                                    @if(strlen($iplan['description'] ) > 90)
                                                     <a  title="See Description" onclick='plan_desc("{{$iplan['id']}}")' href="#see_more_reg" data-toggle="modal" class="see-more-plan"> ... See More </a>
                                                    @endif 
                                            </div> 
                                    <div class="ig">
                                        <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                        <img class="tick" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                      
                    <div class="cs col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-5">
                        <div class="card mr-4 plandet" style="width:200px">    
                            <div class="card-body card-c regis-enterprise {{(!empty($user->id) && $user->user_role_id == 4) ? 'mb-ic' : ''}}" data-id="0">
                                <input type="hidden" id="role_0" value="4"> 
                                <input type="hidden" id="planname_0" value="Enterprise">
                                <div class="iv-2">
                                    <h5 class="card-title text-center">Enterprise</h5>
                                </div>
                                <h4 class="card-text gq text-center">GET A QUOTE</h4>
                                <div class="ig">
                                    <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                    <img class="tick" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- @if(count($enterpriserPlans) > 0) 
                        @foreach($enterpriserPlans as $eplan)  
                        <input type="hidden" id="role_{{$eplan['id']}}" value="{{$eplan['user_role_id']}}">   
                        <div class="cs col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-5">
                            <div class="card mr-4" style="width:200px">    
                                <div class="card-body card-c {{(!empty($user->id) && $user->plan_id == $eplan['id']) ? 'mb-ic' : ''}}" data-id="{{$eplan['id']}}">
                                    <input type="hidden" id="planname_{{$eplan['id']}}" value="{{ucwords($eplan['plan_name'])}}">
                                    <div class="iv-2">
                                        <h5 class="card-title text-center">{{ucwords($eplan['plan_name'])}}</h5>
                                    </div>
                                    <h4 class="card-text gq text-center">GET A QUOTE</h4>
                                    <div class="ig">
                                        <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                        <img class="tick" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif -->
                    
                </div>
                <div class="button_section-c">
                    <a href="{{ url('/') }}"><button class="btn cancel_registration">Cancel</button></a>
                    @if(!empty($user->id))
                        @if($user->plan_id != 0)
                            <button class="btn blue_btn">Continue with the {{$user['planDetail']['plan_name']}} plan</button>
                        @else
                            <button class="btn blue_btn">Continue with the plan</button>
                        @endif
                    @else
                        <button class="btn blue_btn">Continue with the plan</button>
                    @endif
                </div>
            </div>

        </main>
    </div>

<!-- see more  -->
    
<div id="see_more_reg" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title mt-0 ">Plan Description</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
          <div id="plandescription"></div>
      
      </div>
      <div class="modal-footer justify-content-center">
        <!-- <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>  -->
      </div> 
    </div>
  </div>
</div> 

    <!-- Modal -->
    <div class="modal " id="myModal" role="dialog" >
      <div class="modal-dialog enterprise_plan">
        <!-- Modal content-->
        <div class="modal-content pop-cont">
            <!-- Modal Body -->
            <button type="button" class="close cl cancel_btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

          <div class="modal-body cust-body ">
              <div class="form_main">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                      <label>No. of teams</label>
                      <input type="text" autocomplete="off" onkeypress="return isNumberKey(event)" id="team_count" value="{{!empty($user->id) ? $user->team_count : '0'}}" class="form-input" placeholder="Enter no. of teams">  
                    </div>
                  </div>
                 
                </div>
                </div>
            </div>
            <!-- Modal footer Start-->
            <div class="button_sec">
                <button class="btn ok_btn">OK</button>
                <button class="btn cancel_btn" data-dismiss="modal" aria-label="Close">CANCEL</button>
            </div>
        </div>
      </div>
    </div>
     
<!-- Modal -->
    <!-- Bootstrap link -->
    <script src="{{asset('js/front_js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/front_js/popper.min.js')}}"></script>
    <script src="{{asset('js/front_js/slim.min.js')}}"></script>
    <!-- js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{asset('js/front_js/custom.js')}}" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $('.menu-ico-g').click(function() {
            $('.page-wrapper').toggleClass('hide')
        })
    </script>
    

<script>
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $('.card-body.card-c').click(function(){
        var id = $(this).attr('data-id');
        var planname = $('#planname_'+id).val();
        if($(this).hasClass('mb-ic')) {
            //deselect
            $('#user_plan_id').val('0');
            $('#user_role_id').val('0');
            $('.blue_btn').html('Continue with the plan');
        }else{
            //selected
            $('.blue_btn').html('Continue with the '+planname+' plan');
            $('#user_plan_id').val(id);
            var role = $('#role_'+id).val();
            $('#user_role_id').val(role);
            if(role == '4'){
                $('#myModal').show();
            }
        }
    });
    $('.ok_btn').click(function(){
        var team_count =$('#team_count').val();
        var user_role_id =$('#user_role_id').val();
        if(user_role_id == '4' && team_count == '0'){
            alert('Please enter no. of teams!');
            $('#team_count').focus();
            return false;
        }else{
            $('#myModal').hide();
        }
    });
    $('.cancel_btn').click(function(){
        $('#team_count').val(0);
        $('#myModal').hide();
    });
    $(".blue_btn").click(function() {
        var user_id =$('#user_id').val();
        var user_plan_id =$('#user_plan_id').val();
        var user_role_id =$('#user_role_id').val();
        var team_count =$('#team_count').val();
        if(user_plan_id == 0 && user_role_id != '4'){
            alert('Select any subscription plan which is suitable to you!');
            return false;
        }else{
            if(user_role_id == '4' && team_count == '0'){
                alert('Please enter no. of teams!');
                $('#myModal').show();
                $('#team_count').focus();
                return false;
            }
            $('.loader').html('Processing...');
            $('#divLoading').show();
            let URL =  '{{ route("subscription") }}';
            $.post(URL,
            {
                "_token": "{{ csrf_token() }}",
                "plan": user_plan_id,
                "user_id": user_id,
                "team_count":team_count,
                "user_role_id":user_role_id
            },
            function(response) {
                $('#divLoading').hide();
                if(response['success'] == '1'){
                    if(user_role_id == '4'){
                        let redirect_url = "{{ url('success-payment/') }}/"+user_id;
                        window.location = redirect_url;
                    }else{
                        let redirect_url = "{{ url('plan-preview/') }}/"+user_id;
                        window.location = redirect_url;
                    }
                }else{
                    alert('Something went wrong!');
                    return false;
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log('failure');
            });
            
        }
    });

 function plan_desc(val) { 
    var plan_desc = $('#plan_desc_'+val).val();
    $("#plandescription").html(plan_desc);     
 }
</script>
</body>

</html>