

<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
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
          <h1>Reset Password</h1>
          
          <form class="form-group" action="{{route('reset-PasswordPost')}}" method="POST">
            @csrf
            <input type="hidden" name="email" id="email" value="{{$admin['email']}}">
            <input type="hidden" name="otp" id="otp" value="{{$admin['email_otp']}}">


         <!--    <div class="login-bx">
              <span class="pw"><img src="images/password.png" alt="user"></span> 
              <input id="password-field" type="text"   placeholder="Enter new password" class="form-control" name="password" required/>
              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>

            <div class="login-bx">
              <span class="pw"><img src="images/password.png" alt="user"></span> 
              <input id="password-confm-field" type="text" id="password_confirmation"  name="password_confirmation"  placeholder="Enter Confirm password" class="form-control" required />
              @error('password_confirmation')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div> -->
             <div class="login-bx">
                         <span class="pw"> <img src="images/password.png"></span>
                        <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Enter Password">
                         <span class="eye_pw"> <img src="" class="eye_icon fa fa-eye-slash field-icon toggle-password" toggle="#password-field" onclick="myPass()"></span>
                         @error('password')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                         @enderror
                    </div>
                    <div class="login-bx">
                         <span class="pw"> <img src="images/password.png"></span>
                        <input type="password" name="password_confirmation" class="form-control  @error('password_confirmation') is-invalid @enderror" id="exampleInputPassword2" placeholder="Enter Confirm Password">
                         <span class="eye_pw"> <img src="" class="eye_icon far fa-eye-slash field-icon toggle-password" toggle="#password-field" onclick="myPassword()"></span>
                         @error('password_confirmation')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                    </div>

             
            <button class="for-btn" style="color: #fff; text-align: center;  font-style: normal;  font-weight: 700;  font-size: 18px; line-height: 22px;"> Submit </button>
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
    function myPass() {
        var x = document.getElementById("exampleInputPassword1");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }

    function myPassword() {
        var x = document.getElementById("exampleInputPassword2");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }
</script>
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
