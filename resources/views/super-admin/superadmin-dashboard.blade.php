@extends('layouts.header')
@section('content')
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center flex-wrap">
            <div class="chart_flex_Wrap">
                <div class="main_content width_30 mr_30 mr-2">
                    <div class="main_head">
                        <div class="header_left">
                            <h1 id="blue">{{count($totalusers)}}</h1>
                            <p>Total Users</p>
                        </div>
                        <div class="im">
                            <img src="images/Number Of Users.png" alt="">
                        </div>     
                    </div>            
                </div>
            
                <div class="main_content width_30 mr_30 mr-2" id="usercontent">
                    <a href="{{url('/trial-users')}}"><div class="main_head">
                        <div class="header_left">
                            <h1 id="green">{{count($trialusers)}}</h1>
                            <p>Trial Users</p>
                        </div>
                        <div class="im">
                            <img src="images/No Of Subscribers.png" alt="">
                        </div>                                                                          
                    </div></a>            
                </div>

                <div class="main_content width_30 mr_30 mr-2" id="usercontent">
                    <a href="{{url('/individualuser')}}"><div class="main_head">
                        <div class="header_left">
                            <h1 id="red">{{count($individualusers)}}</h1>
                            <p>Individual Users</p>
                        </div>
                        <div class="im">
                            <img src="images/Individual Users.png" alt="">
                         </div>                               
                    </div></a>            
                </div>

                <div class="main_content width_30 mr_30 mr-2" id="usercontent">
                    <a href="{{url('/team-users')}}"><div class="main_head"> 
                        <div class="header_left">
                            <h1 id="orange">{{count($teamusers)}}</h1>
                            <p>Team Users</p>
                        </div> 
                        <div class="im">
                            <img src="images/Team Users.png" alt="">
                        </div>                                  
                    </div></a>   
                </div>
                <div class="main_content width_30 mr_30 mr-2" id="usercontent">
                    <a href="{{url('/enterpriseuser')}}"><div class="main_head"> 
                        <div class="header_left">
                            <h1 id="red">{{count($enterprisers)}}</h1>
                            <p>EnterPriser Users</p>
                        </div> 
                        <div class="im">
                            <img src="images/Team Users.png" alt="">
                        </div>                                  
                    </div></a> 
                </div>
                <div class="main_content width_30 mr_30 mr-2" id="usercontent">
                    <a href="{{url('/enterprise-request')}}"><div class="main_head"> 
                        <div class="header_left">
                            <h1 id="blue">{{count($enterpriser_request)}}</h1>
                            <p>EnterPriser User Requests</p>
                        </div> 
                        <div class="im">
                            <img src="images/Team Users.png" alt="">
                        </div>                                  
                    </div></a>   
                </div>
            </div>  
        </div> 
    </div>
@endsection