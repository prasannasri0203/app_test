<!DOCTYPE html>
<html>
<head>
	<title>{{$Module['module']}}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Css link -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}"> 
    <link rel="stylesheet" type="text/css" href="{{asset('js/bootstrap.min.css')}}">
  	<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
  	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>



   <!-- sortable link -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<!-- responsive link -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}">
	<link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}">

  <link rel="stylesheet" href="/css/font-awesome.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
         

</head>
<body>
    <div class="page-wrapper">
        <!-- nav section -->
        @include('layouts/sidebar')
        
        <!-- main section -->
        <main class="page-content">
            <div class=" dash-right">
                <div class="login-hd-details">
                    <div class="login-hd-left d-flex align-items-center">
                            <div class="menu-ico-g mb-ic">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                    </div>
                    <div class="nav_right">
                      <button type="button" class=" bell_button_icon btn btn-primary bg-transparent border-0 mb-lg-0 mb-1 rounded-0" onclick="myFunction()">
                                        <img src="{{asset('images/front_images/user_module/bell-white.png')}}">
                                        <span class="badge notifi_badge"></span>

                                        <div class="notification_part admin-notify pb-2" id="notificationshow">
                                            <div class="notify_text py-2">
                                                Notifications
                                            </div>
                                            <div id="notification_data"></div>
                                            
                                        </div>
                                    </button>
                        <div class="profile_nav">
                         <input type="hidden" id="base_url" value="{{url('/')}}">

                            <div class="profile_nav_img" >
                                <img src="{{asset('images/adminuser.png')}}">
                            </div>
                            <div class="profile_nav_text" id="XA">
                                <h1>{{ucfirst(Auth::guard('superadmin')->user()->name) }}<span><img src="{{asset('images/Polygon.png')}}"></span></h1>
                            </div>

                            <div id="logout_btn">
                                        <a href="{{url('admin-profile')}}"><button class="btn" type="button"><img class="mr-3" width="18px" src="{{asset('images/Manage Users.png')}}" alt="">Profile</button></a>

                                        <a href="{{url('logout')}}"><button class="btn" type="button"><img class="mr-2" src="{{asset('images/front_images/user_module/logout_btn.png')}}">Logout</button></a>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </main>
    </div>

   <!-- Bootstrap link -->	
   <script  src="{{asset('js/bootstrap.min.js')}}"></script>
   <script  src="{{asset('js/popper.min.js')}}"></script>
   <script  src="{{asset('js/slim.min.js')}}"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <!-- phone number -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
   <!-- sub_addmenu -->
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
 

   <!-- js link -->
   <script  src="{{asset('js/custom.js')}}" type="text/javascript"></script>
   <script  src="{{asset('js/Superadmin/subscription.js')}}" type="text/javascript"></script> 
   <script  src="{{asset('js/Superadmin/coupon.js')}}" type="text/javascript"></script> 
   
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
      
   <script>
   $('.menu-ico-g').click(function(){
    $('.page-wrapper').toggleClass('hide')
    })</script>  
       <script>
       var e = document.getElementById("logout_btn")
        var toggleButton = document.getElementById("XA")
        toggleButton.onclick = function() {
            e.classList.toggle("toggled")
        }

         $(document).ready(function(){
            $('input').attr('autocomplete','off'); 
            getNotification();

            $(".profile_nav_text").click(function(){
            $('.notification_part').removeClass("mystyle");
        });

        $(".bell_button_icon").click(function(){
            $('#logout_btn').removeClass("toggled");
        }); 

         });

         $(document).on('click', function (e) {
        if ($(e.target).closest(".card").length === 0) {
            $(".card").removeClass('main_add');
        }   
        if ($(e.target).closest(".inner-wrapper-g").length != 0  || $(e.target).closest(".table-footer").length != 0 || $(e.target).closest("#side-wrapper").length!=0) { 
         $(".notification_part").removeClass('mystyle');
         $("#logout_btn").removeClass('toggled');
     }
     if ($(e.target).closest(".main_part").length != 0) { 
         $("#logout_btn").removeClass('toggled');
     }

 });
    </script>
    <script>
       function myFunction() {
        var element = document.getElementById("notificationshow");
        element.classList.toggle("mystyle");
    }

        var t = document.getElementById("notificationshow")
    var toggleButton = document.getElementById("Notifica")

      $(".textarea_stl")
    .bind("dragover", false)
    .bind("dragenter", false)
    .bind("drop", function(e) {
        this.value = e.originalEvent.dataTransfer.getData("text") ||
            e.originalEvent.dataTransfer.getData("text/plain");
        
        $("span").append("dropped!");

    return false;
});

     function removeNotification(){
        let URL =  '{{ route("update-notification-admin") }}';
        $.get(URL,
          function(response) {
            if(response == '1'){
              getNotification();
            }else{
                // alert('w');
            }
          }).fail(function(jqXHR, textStatus, errorThrown) {
          console.log('failure');
      });
    }
    function getNotification(){
      var baseurl = $('#base_url').val();  
      var notificationData=''; 
      let URL =  '{{ route("get-notification-admin") }}';
      $.get(URL,
          function(response) { 
            if(response['cnt'] != 0){
                $('.badge').html(response['cnt']);
                notificationData+='<div class="pt-2 notify_content" style="border-bottom: 1px solid #0000002e;">';
                
                if(response['notifications'].length > 0){ 
                    // notificationData+='<div class="pt-2 notify_content" style="border-bottom: 1px solid #0000002e;">';
                    var userdatas = response['userdetail'];
                    $.each(response['notifications'], function (key, value2) { 
                        var named=userdatas[key]['name'];                        
                        if(value2['type'] == 9){
                            var furl = baseurl+'/trial-users?user_name='+named+'&nid='+value2['id'];
                        }else if(value2['type'] == 8){
                               var furl = baseurl+'/enterprise-request?user_name='+named+'&nid='+value2['id'];
                        }else if(value2['type'] == 7){
                               var furl = baseurl+'/report-payment?user_name='+named+'&nid='+value2['id'];
                        }    
                        var from_id =value2['from_id'];
                        notificationData+='<div class="notify_part d-flex"><div class="notify_img"><img src="{{asset('images/front_images/user_module/user.png')}}" alt=""></div><div class="notify_para"><h3><a href="'+furl+'">'+value2['message']+'</a></h3></div></div>';
                    });
                }
                notificationData+='</div><div class="notify_footer"><a href="#" onclick="removeNotification();">Clear All</a></div>';
                $('#notification_data').html('');
                $('#notification_data').append(notificationData);
            }else{        
                $('.badge').html('0');
                $('#notification_data').html('');
                $('#notification_data').append('<p>You did not receive notification</p>');    
                console.log('no-notification');
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
          console.log('failure');
      });
        
    }
    
    </script>
</body>
</html> 
