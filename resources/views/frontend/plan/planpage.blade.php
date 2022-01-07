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
    <div id="divLoading"><div class="loader"></div></div>
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="header_top">
            <!-- <div class="pp-lg">
                <img src="{{asset('images/kaizen-logo 1.png')}}">
            </div> -->
            <h2 class="recent_flow mt-4 ml-1 mb-0">Pricing Plan</h2>
            <p class="ml-1 mt-2">Choose the plan to suitable for you</p>
        </div>
        <?php
           
            $individualplan =   array();
            $teamplan       =   array();
            $enterprise     =   array();
            if(count($plandetails)>0){
                foreach($plandetails as $plans){
                    if($plans->user_role_id==1){
                        $teamplan[] = array('id'=>$plans->id,'user_role_id'=>$plans->user_role_id,'plan_name'=>$plans->plan_name,'plan_type'=>$plans->plan_type,'activation_period'=>$plans->activation_period,'amount'=>$plans->amount,'payment_type'=>$plans->payment_type,'description'=>$plans->description);
                    }else if($plans->user_role_id==3){
                        $individualplan[] = array('id'=>$plans->id,'user_role_id'=>$plans->user_role_id,'plan_name'=>$plans->plan_name,'plan_type'=>$plans->plan_type,'activation_period'=>$plans->activation_period,'amount'=>$plans->amount,'payment_type'=>$plans->payment_type,'description'=>$plans->description);
                    }else if($plans->user_role_id==4){
                        $enterprise[] = array('id'=>$plans->id,'user_role_id'=>$plans->user_role_id,'plan_name'=>$plans->plan_name,'plan_type'=>$plans->plan_type,'activation_period'=>$plans->activation_period,'amount'=>$plans->amount,'payment_type'=>$plans->payment_type,'description'=>$plans->description);
                    }
                }

                $individualplan  = array_merge($individualplan, $teamplan);   
            }
        ?>
        
        <div class="alert alert-warning" role="alert">
            <?php if($user->user_role_id == 4){
                 $plan_det = 'Enterpriser';
            }else{
                $plan_det = ucfirst($user['planDetail']['plan_name']);
            }
            if($planval == 'upgrade'){?>
                You are in a '{{$plan_det}}' plan now. If you want to change the plan, select another suitable plan.
            <?php }else{?>
                Your '{{$plan_det}}' plan has been expired. Please renewal or upgrade your plan.
            <?php }?>
        </div>
        
        <div class="inner-wrapper-g">
            <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                <div class="container-fluid p-0 mt-4">
                    <div class="row cards-p mx-auto">
                        <input type="hidden" id="base_url" value="{{url('/')}}">
                        <input type="hidden" id="user_plan_id" value="{{Auth::user()->plan_id}}">
                        <input type="hidden" id="user_role_id" value="{{Auth::user()->user_role_id}}">
                        <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                        <input type="hidden" id="user_status" value="{{Auth::user()->status}}">
                        <input type="hidden" id="db_user_role_id" value="{{Auth::user()->user_role_id}}">
                        <input type="hidden" id="db_user_plan_id" value="{{Auth::user()->plan_id}}">
                        <input type="hidden" id="user_plan_type" value="{{$planval}}">  
                        @if(count($individualplan)>0)
                            @foreach($individualplan as $tp)
                                <?php $planClass='';
                                    if(Auth::user()->plan_id==$tp['id'] && $planval == 'register'){
                                        $planClass='mb-ic';
                                    }else if(Auth::user()->plan_id==$tp['id'] && $planval != 'register'){
                                        $planClass='mb-ic card-gray';
                                    }
                                ?>
                                <div class="cs col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-5">
                                    <div class="card mr-4 plandet " style="width:200px">
                                        <input type="hidden" id="role_{{$tp['id']}}" value="{{$tp['user_role_id']}}">
                                        <input type="hidden" id="planname_{{$tp['id']}}" value="{{ucwords($tp['plan_name'])}}">
                                        <div class="card-body card-c {{$planClass}}" data-id="{{$tp['id']}}">
                                            <div class="iv">
                                                <h5 class="card-title text-center">{{ucwords($tp['plan_name'])}}</h5>
                                                <p id="st">start at</p>
                                            </div>
                                            <p class="card-title dollar text-center">CAD {{$tp['amount']}}</p>
                                            <p class="card-text text-center">Per @if($tp['payment_type']=='monthly') Month @else Year @endif</p> 
                                            <div class="disc-card">     
                                            <input type="hidden" id="plan_desc_{{$tp['id']}}" value="{{$tp['description']}}">            
                                                  <?php  $stringCut = substr($tp['description'], 0, 90); ?>
                                                    <p class="desc_plan">{{$stringCut}}</p>
                                                    @if(strlen($tp['description'] ) > 90)
                                                     <a  title="See Description" onclick='plan_desc("{{$tp['id']}}")' href="#see_more" data-toggle="modal" class="see-more-plan"> ... See More </a>
                                                    @endif 
                                            </div>
                                            <div class="ig">
                                                @if(Auth::user()->plan_id != $tp['id'])
                                                <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                                @endif
                                                <img class="tick" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <?php $enterpriseClass='';
                            if(Auth::user()->user_role_id==4 && $planval == 'register'){
                                $enterpriseClass='mb-ic';
                            }else if(Auth::user()->user_role_id==4 && $planval != 'register'){
                                $enterpriseClass='mb-ic card-gray';
                            }
                        ?>
                        <div class="cs col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-5">
                            <div class="card mr-4 plandet" style="width:200px">
                                <input type="hidden" id="role_0" value="4">
                                <input type="hidden" id="planname_0" value="Enterpriser">
                                <div class="card-body card-c card-enterp {{$enterpriseClass}}" data-id="0">
                                    <div class="iv-2">
                                        <h5 class="card-title text-center">Enterprise</h5>
                                    </div>
                                    <h4 class="card-text gq text-center">GET A QUOTE</h4>
                                    <div class="ig">
                                        @if(Auth::user()->user_role_id!=4)
                                        <img class="plus" src="{{asset('images/front_images/plus-1.png')}}" alt="plus">
                                        @endif
                                        <img class="tick disable-gray" src="{{asset('images/front_images/tick-1.png')}}" alt="tick">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- @if(count($enterprise)>0)
                            @foreach($enterprise as $epuser)
                                <div class="cs col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-5">
                                    <div class="card mr-4" style="width:200px">
                                        <input type="hidden" id="role_{{$epuser['id']}}" value="{{$epuser['user_role_id']}}">
                                        <input type="hidden" id="planname_{{$epuser['id']}}" value="{{ucwords($epuser['plan_name'])}}">
                                        <div class="card-body card-c @if(Auth::user()->plan_id==$epuser['id']) mb-ic @endif" data-id="{{$epuser['id']}}">
                                            <div class="iv-2">
                                                <h5 class="card-title text-center">{{ucwords($epuser['plan_name'])}}</h5>
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
                    
                    @if($planval == 'register')
                        <div class="button_section-c"> 
                            <a href="{{ url('/plan-setting') }}" style="float: right;"><button class="btn cancel_registration ">Cancel</button></a>
                            <button class="btn blue_btn submit_plan">Continue with the {{$plan_det}} plan</button>
                        </div>
                    @else
                        <div class="button_section-c" style="display: none;">
                            <a href="{{ url('/plan-setting') }}" style="float: right;"><button class="btn cancel_registration ">Cancel</button></a> 
                            <button class="btn blue_btn submit_plan">Continue with the plan</button>
                        </div>
                    @endif
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
                      <input type="text" onkeypress="return isNumberKey(event)" autocomplete="off" id="team_count" value="{{!empty($user->id) ? $user->team_count : '0'}}" class="form-input" placeholder="Enter no. of teams">  
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

<!-- see more  -->
    
<div id="see_more" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title  mt-0">Plan Description</h4>  
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
@endsection
 