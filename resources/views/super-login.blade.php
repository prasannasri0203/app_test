

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Css link -->
  <link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}">
	<link rel="stylesheet" type="text/css" href="js/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <!-- responsive link -->
  <link rel="stylesheet" type="text/css" href="css/responsive.css">
  
</head>
<body>
  <div class="page-wrapper"> 
    <!-- main section --> 
    <main class="page-cont">
      <div class="main">
        <div class="login-sec">       	
          <img class="logo-set" src="images/kaizen-logo 1.png" alt="login img"> 
          <h1>Login to Kaizen Hub</h1>  
          
          @if(Session::get('success'))
          <div class="alert alert-success" role="alert">
            {{Session::get('success')}}
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
          </div>
          @endif
          <form class="form-group" action="{{route('super_login')}}" method="POST">
            @csrf

            <div class="login-bx">
              <span class="us"><img src="images/user.png" alt="user"></span>      
              <input type="email" placeholder="Email Address" class="form-control   @error('email') is-invalid @enderror" name="email"
               value="{{old('email')}}" >   
                 @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="login-bx">
              <span class="pw"><img src="images/password.png" alt="user"></span> 
              <input id="password-field" type="password" name="password"  placeholder="Enter password" class="form-control   @error('password') is-invalid @enderror" name="password">
             <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
               @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="new">
                            <!--  <div class="cus-chckbox">
                               <input type="checkbox" id="html">
                               <label for="html">Remember me</label>
                               
                             </div> -->
                             <label class="login_float_right"><a href="{{route('forgotpassword')}}">Forgot Password?</a> </label>
                           </div>
                           <button class="for-btn" style="color: #fff; text-align: center;  font-style: normal;  font-weight: 700;  font-size: 18px; line-height: 22px;"> LOGIN </button>
                           <!-- <p>you don't have an account?<span><a href="#">SIGN UP</a></span></p> -->
                           
                         </form>
                       </div>
                     </div>
                   </main>
                 </div>

                 <!-- Bootstrap link -->	
                 <script  src="js/bootstrap.min.js"></script>
                 <script  src="js/popper.min.js"></script>
                 <script  src="js/slim.min.js"></script>
                 <!-- js link -->
                 <script  src="js/custom.js" type="text/javascript"></script>
                 <script>$('.menu-ico-g').click(function(){
                  $('.page-wrapper').toggleClass('hide')
                })</script>

                <script>
                  $(".toggle-password").click(function() {

                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggle"));
                    if (input.attr("type") == "password") {
                      input.attr("type", "text");
                    } else {
                      input.attr("type", "password");
                    }
                  });
                </script>
              </body>
              </html>

