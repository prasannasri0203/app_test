<nav id="sidebar" class="sidebar-wrapper">
    <div class="dash-left">
        <img src="{{asset('images/kaizen_logo_new.png')}}" alt="dashboard_logo">
    </div>
    <a href="12B_Manage_Cooperative(1).html">
  
    </a>
    <div class="db-lit-sc">
        <ul class="categories">
            <li class="submenu @if($Module['module']=='Dashboard') active @endif">
                <a href="{{url('/super-dashboard')}}">
                <img src="{{asset('images/Dashboard.png')}}" alt=""> DashBoard</a>
            </li>
            <li class="submenu @if($Module['module']=='subscription') active @endif">
                <a href="{{url('/super-subscription-plan')}}">
                    <img src="{{asset('images/Manage Subscription Plans.png')}}" alt="">
                    Manage Subscriptions
                </a> 
            </li>
            <li class="submenu  @if($Module['module']=='coupon') active @endif">
                <a href="{{URL('coupon-view')}}">
                    <img src="{{asset('images/Manage promo Code.png')}}" alt=""> 
                    Manage Coupon 
                </a>                        
            </li>
            <!-- <li class="submenu">
                <a href="#">
                    <img src="{{asset('images/Manage Users.png')}}" alt="">
                    Manage Users
                </a>
            </li>  -->
            <li class="submenu  @if($Module['module']=='trial-users') active @endif">    
                <a href="{{route('trial-users')}}">  
                    <img src="{{asset('images/Manage Users.png')}}" alt="">
                    Manage Trial Users
                </a>                        
            </li> 
            <li class="submenu  @if($Module['module']=='Individualuser') active @endif">
                <a href="{{URL('individualuser')}}"> 
                    <img src="{{asset('images/Manage Users.png')}}" alt="">
                    Manage Individual Users
                </a>                        
            </li>
            <li class="submenu  @if($Module['module']=='team-user') active @endif">
                <a href="{{route('team-users')}}">
                    <img src="{{asset('images/Manage Team Users.png')}}" alt="">
                    Manage Team Users
                </a> 
            </li>
            <li class="submenu  @if($Module['module']=='Enterpriseuser') active @endif">
                    <a href="{{URL('enterpriseuser')}}"> 
                    <img src="{{asset('images/Manage Enterprises.png')}}" alt="">
                    Manage Enterprise Users
                </a>
            </li>
            <li class="submenu  @if($Module['module']=='Enterpriseuserrequest') active @endif"> 
                <a href="{{URL('enterprise-request')}}">
                    <img src="{{asset('images/Mange user request.png')}}" alt="">
                    Manage Enterprise User request
                </a>
            </li>
            <li class="submenu  @if($Module['module']=='Tax') active @endif">
                <a href="{{URL('tax')}}">
                    <img src="{{asset('images/Manage Reports.png')}}" alt="">
                    Manage Tax 
                </a>
            </li>
            <li class="submenu  @if($Module['module']=='themes') active @endif">
                    <a href="{{URL('themes')}}"> 
                    <img src="{{asset('images/Manage Enterprises.png')}}" alt="">
                    Theme Setup
                </a>
            </li>              
            <li class="submenu @if($Module['module']=='TemplateCategory') active @endif" >
                <a href="{{URL('tcategory')}}">
                    <img src="{{asset('images/Manage Reports.png')}}" alt="">
                    Manage Template Category
                </a>
            </li>

            <li class="submenu @if($Module['module']=='Template') active @endif" >
                <a href="{{URL('template')}}">
                    <img src="{{asset('images/Manage Reports.png')}}" alt="">
                    Manage Template 
                </a>
            </li>
            <!-- submenu report -->
             <li   class="report-submenu submenu sidebar-dropdown flow_chart_part @if($Module['module']=='login report' || $Module['module']=='login not report' || $Module['module']=='report-payment' ) active font-active submenu-active @endif"> 
                <a class="flow" data-bs-toggle="collapse" href="#collapseExamples" role="button" aria-expanded="false" aria-controls="collapseExamples">
                     <img src="{{asset('images/Manage Reports.png')}}" alt="">
                    Manage  Report 
                        <i class="fa fa-caret-down" style="margin-left:15px;"></i>
                     <!-- <span class="text-rightright-icon"><i class="fa fa-chevron-down"></i></span> -->
                </a> 
                <div class="collapse" id="collapseExamples">
                    <div>
                        <ul class="navbra-nav pl-3">                          
                            <li  class="@if($Module['module']=='login report') subactive  @endif li-cls">
                                <a class="pl-2 @if($Module['module']=='login report')  sub-active-class @endif" href="{{route('login-report')}}" >Login Report</a>
                            </li> 
                            <li  class="@if($Module['module']=='report-payment') subactive @endif li-cls">
                                <a class="pl-2 @if($Module['module']=='report-payment') sub-active-class  @endif" href="{{url('report-payment')}}" >Payment Report</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <!-- end -->
            <li class="submenu">
                <a href="{{url('logout')}}">
                    <img src="{{asset('images/Manage Reports.png')}}" alt="">
                   Logout
                </a>
            </li>

        </ul>
    </div>
</nav>
<script>
     $(document).ready(function(){
         $(".sidebar-dropdown ").on({ 
          click: function(){ 
              $('.sidebar-dropdown').addClass('active');
         }  
        });
      });
</script>
    
