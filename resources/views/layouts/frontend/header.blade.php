<!DOCTYPE html>
<html>

<head>
    <title>Kaizen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Css link -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}"> 
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/bootstrap.min.css')}}">
    <!--  <link rel="stylesheet" type="text/css" href="css/style.css"> -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- responsive link -->
    <!-- <link rel="stylesheet" type="text/css" href="css/responsive.css"> -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css"> 

    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/taglist.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  
         
</head>

<body>
    <?php use App\Models\Superadmin\Renewal_details;
    use App\Models\UserTemplate;
    use App\Models\TemplateShare;
    use App\Models\User;
    $upgradeBtn =0;
    $userRole = Auth::user()->user_role_id;
    $parentId = Auth::user()->parent_id;
    $currentDate = date('Y-m-d');
    $renewal_list = Renewal_details::where('user_id',Auth::user()->id)->where('is_activate','1')->where('status',1)->latest()->first();
    if($parentId == 0){
        if($renewal_list){
            if(date('Y-m-d',strtotime($renewal_list['renewal_date'])) <= $currentDate){
                $upgradeBtn =1;
            }
        }else{
            $upgradeBtn =1;
        }
    }elseif($parentId != 0){//team user of enterpriser
        $chkParent = User::find($parentId);
        if($chkParent->user_role_id != 4){
            $upgradeBtn =3;
        }
        $renewal_list = Renewal_details::where('user_id',$parentId)->where('is_activate','1')->where('status',1)->latest()->first();
        if($renewal_list){
            if(date('Y-m-d',strtotime($renewal_list['renewal_date'])) <= $currentDate){
                $upgradeBtn =2;
            }
        }else{
            $upgradeBtn =2;
        }
    }else{
        $upgradeBtn =3;
    }  

    ?>
    <div class="container-fluid default_theme">
        <div class="row">
            <input type="hidden" id="base_url" value="{{url('/')}}">
            <div class="col-12 d-flex p-0" id="wrapper" style="min-height: 100vh;">
                @include('layouts/frontend/sidebar')
                <div class="col p-0">
                    <div id="main-page-wrapper">
                        <nav class="navbar navbar-expand-md fixed-top py-2 px-lg-4 webview">
                            <div class="container-fluid px-1">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-align-left fs-4 me-3" id="menu-bar" style="cursor: pointer;"></i>
                                    <h2 class="m-0 welcome_text">Welcome, {{ucfirst(Auth::user()->name)}}  ({{ optional(Auth::user()->userRole)->role  }})</h2>
                                </div>
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
                                    
                                    <span><i class="fa fa-ellipsis-v"></i></span>
                                </button>
                                <div class="collapse navbar-collapse mt-lg-0 mt-3 p-0 mr-3" id="navbarSupportedContent1">
                                    <button type="button" class=" bell_button_icon btn btn-primary bg-transparent border-0 mb-lg-0 mb-3 rounded-0" onclick="myFunction()">
                                        <img src="{{asset('images/front_images/user_module/bell.png')}}">
                                        <span class="badge"></span>

                                        <div class="notification_part pb-3" id="notificationshow">
                                            <div class="notify_text py-2">
                                                Notifications
                                            </div>
                                            <div id="notification_data"></div>
                                            
                                        </div>
                                    </button> 

                                    <div class="XA_text mr-4 mb-lg-0 mb-3" id="XA">
                                        <a>{{strtoupper(Auth::user()->name[0].Auth::user()->name[1])}}</a>
                                        <div id="logout_btn"> 

                                           <a href="{{url('/account-setting')}}">
                                            <button class="btn" type="button"><svg width="15" height="16" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.8359 12.0001C4.72193 11.9415 4.59818 11.9545 4.47769 11.9383C3.7743 11.8438 3.10347 11.6289 2.47823 11.3C1.47525 10.7692 0.677421 10.0137 0.0717221 9.05306C-0.0129455 8.91629 -0.0194584 8.79254 0.0521834 8.64926C0.511342 7.70814 1.2245 7.05034 2.19818 6.67259C2.45218 6.5749 2.71921 6.51628 2.9895 6.47069C3.15232 6.44464 3.2956 6.48698 3.42912 6.58141C3.78407 6.83542 4.17159 7.01778 4.60144 7.11221C5.54255 7.31737 6.41853 7.16757 7.21962 6.62375C7.41826 6.49023 7.61365 6.4479 7.84486 6.49349C9.14092 6.75075 10.069 7.48019 10.6617 8.65577C10.7236 8.77951 10.717 8.88372 10.6421 9.00421C10.0364 9.98114 9.23862 10.7497 8.22586 11.2902C7.54526 11.6517 6.82233 11.8764 6.05381 11.9611C5.96914 11.9708 5.87796 11.9611 5.79981 12.0067C5.47416 12.0001 5.15503 12.0001 4.8359 12.0001Z"/>
                                                <path d="M5.63033 0C5.80618 0.052103 5.9918 0.0618724 6.1709 0.113975C7.04363 0.367978 7.70143 0.892265 8.11174 1.69986C8.58719 2.63772 8.59044 3.59511 8.11174 4.52971C7.63305 5.45779 6.86127 6.01464 5.82898 6.18724C4.53617 6.40216 3.26941 5.75087 2.63115 4.61763C2.00591 3.5137 2.15896 2.02551 3.00238 1.08114C3.52667 0.494979 4.1747 0.140027 4.95299 0.0260515C4.97579 0.0227951 5.00835 0.0358208 5.02464 0C5.22653 0 5.42843 0 5.63033 0Z"/>
                                            </svg>Profile</button>
                                        </a>
                                        <a href="{{url('/user-logout')}}">
                                            <button class="btn" type="button"><img class="mr-2" src="{{asset('images/front_images/user_module/logout_btn.png')}}">Logout</button>
                                        </a>
                                    </div>
                                </div> 

                                @if(Auth::user()->parent_id == 0)
                                <a href="{{url('/planchange')}}" style="color: white;"><button class="btn Upgrade_btn mb-lg-0 mb-3" type="btn">Upgrade</button></a>
                                @endif                                 
                                <input type="hidden" id="upgradeBtn" value="{{$upgradeBtn}}">
                            </div>
                        </div>
                    </nav>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
 
<!-- phone number -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="{{asset('js/front_js/taglist.jquery.js')}}"></script>
<script src="{{asset('js/front_js/plan.js')}}"></script>
<script src="{{asset('js/front_js/custom.js')}}" type="text/javascript"></script>
<!-- date picker -->
 

<script>
    $(document).ready(function(){
        $('input').attr('autocomplete','off');
        getUserTheme();
        getNotification();
        var upgrade = '<?php echo $upgradeBtn;?>';
        //alert(upgrade); 
        var baseurl             =   $('#base_url').val();
        if(upgrade == 1){
            var currentUrl = window.location.href;
            var urls = currentUrl.split('/');                
            if(!urls.includes("plan-setting")){
                if((urls.includes("planchange")) || (urls.includes("user-plan-preview"))){
                }else{      
                    window.location.href = baseurl+'/plan-setting/2';
                }                
            }               
        }else if(upgrade == 2){
            // alert('Your admin renewal has been expired, please contact admin!');
            window.location.href = baseurl+'/user-logout/1';
        }else if(upgrade == 3){
            // alert('Your admin role is not valid, please contact admin!');
            window.location.href = baseurl+'/user-logout/2';
        }    
    });
    var el = document.getElementById("wrapper")
    var toggleButton = document.getElementById("menu-bar")

    toggleButton.onclick = function() {
        el.classList.toggle("toggled")
    }    
    function removeNotification(){
        var baseurl = $('#base_url').val();
        var template_ids = $('#template_ids').val();
        $.ajax({
            url: baseurl+'/update-notification', 
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { 
                template_ids:template_ids
            },
            success: function(response){ 
                if(response == '1'){
                    getNotification();
                        // alert('r');
                    }else{
                        // alert('w');
                    }
                }
            });
    }
    function getNotification(){
      var baseurl = $('#base_url').val();  
      var notificationData='';
      let URL =  '{{ route("get-notification") }}';
      $.get(URL,
          function(response) {               
            if(response['cnt'] != 0){
                $('.badge').html(response['cnt']);
                notificationData+='<div class="pt-2 notify_content" style="border-bottom: 1px solid #0000002e;">';
                if(response['usertemplate'].length > 0){
                    $.each(response['usertemplate'], function (key, value) {
                        var furl = baseurl+'/flowchart?user='+value['user_template_id']+'&process=received';
                        notificationData+='<div class="notify_part d-flex"><div class="notify_img"><img src="{{asset('images/front_images/user_module/user.png')}}" alt=""></div><div class="notify_para"><h3><a href="'+furl+'">'+value['user_detail']['name']+' shared '+value['user_template']['template_name']+ ' flowchart</a></h3></div></div>';
                    });
                    notificationData+='<input type="hidden" id="template_ids" value="'+response['templates']+'">';
                }
                if(response['notifications'].length > 0){
                    // notificationData+='<div class="pt-2 notify_content" style="border-bottom: 1px solid #0000002e;">';
                 
                    $.each(response['notifications'], function (key, value2) {
                        var userdatas = response['userdetail'];
                        var named=userdatas[key]['name'];  
                        if(value2['type'] == 4){
                              var furl=baseurl+'/view-project/'+value2['template_id'];
                        }else if(value2['type'] == 10){//team
                            var furl = baseurl+'/user-list?user_name='+named+'&nid='+value2['id'];
                        }else if(value2['type'] == 11){//enterpriser
                            var furl = baseurl+'/sub-user-list?user_name='+named+'&nid='+value2['id'];
                        }else{
                              var furl = baseurl+'/flowchart?user='+value2['template_id']+'&notif-'+value2['id'];
                        }

                        var temp_id =value2['template_id'];
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
<script>
    var flag = 1;

    function load() {

        if (flag == 1) {
            $('#load_item').find('ul').css('display', 'block');
            flag = 0;
        } else {
            $('#load_item').find('ul').css('display', 'none');
            flag = 1;

        }
    }
    function getUserTheme(){
      let URL =  '{{ route("set-theme") }}';
      $.get(URL,
          function(response) {
              if(response['success'] == '1'){
                localStorage.setItem("background_color", response['background_color']);
                localStorage.setItem("font_color", response['font_color']);
                $('.container-fluid').removeClass('default_theme');
                setBackgroundColor();
            }else{            
              console.log('no theme');
          }
      }).fail(function(jqXHR, textStatus, errorThrown) {
          console.log('failure');
      });
  }
</script>
<script>
    var e = document.getElementById("logout_btn")
    var toggleButton = document.getElementById("XA")

    toggleButton.onclick = function() {
        e.classList.toggle("toggled")
    }
    var e1 = document.getElementById("popup-submenu")
    var toggleButton = document.getElementById("submenu-popup-clk")

    toggleButton.onclick = function() {
        e1.classList.toggle("toggled")
    }
</script>
<script>
    var t = document.getElementById("notificationshow")
    var toggleButton = document.getElementById("Notifica")

        // toggleButton.onclick = function() {
        //     t.classList.toggle("toggled")
        // }
        
    </script>   
    <script>
     $(document).ready(function(){
        $(".flow").on({ 
          click: function(){ 
          // $(this).css("background-color", "#008cc5");
          $(this).css("color", "#4d4d4ded");
          $(this.svg).css("color", "#4d4d4ded");
         }  
        }); 

        $(".more").click(function(){
            if($(this).closest('.card').hasClass("main_add")){
                $(this).closest('.card').removeClass("main_add");
            }
            else{
                $(".more").closest('.card').removeClass("main_add");
                $(this).closest('.card').addClass("main_add");
            }
        });
        $(".XA_text").click(function(){
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
        if ($(e.target).closest(".main_part").length != 0 || $(e.target).closest("#side-wrapper").length!=0) { 
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
</script>
  
@yield('script')
</body>

</html> 