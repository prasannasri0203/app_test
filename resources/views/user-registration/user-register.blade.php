<!DOCTYPE html>
<html>

<head>
    <title>Kaizen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css link -->
    <link rel="icon" type="image/png" href="{{asset('images/fevi.png')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_responsive.css')}}">

</head>

<body>
    <div class="col-12 d-flex form_section p-0">
        <div class="col-lg-6 col-12 left_bg d-lg-flex align-items-center justify-content-center" style="background: #F3FCFF;">
            <img src="{{asset('images/front_images/login_imgs/Frame.png')}}">
        </div>
        <div class="col-lg-6 col-12 right_part mt-5">
            <div class="logo logo-1 text-center">
                <img src="{{asset('images/kaizen-logo 1.png')}}">
            </div>
            <div class="right_section px-md-5 px-2 mx-md-3">
                <h2>Create An Account</h2>
               
                <form action="{{ route('submit-register') }}" method="POST">
                    @csrf
                    <div class="form_sroll">
                        <div class="form-group @error('user_name') name_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/loginuser.png')}}">
                            <input type="text" class="form-control" name="user_name" id="name" placeholder="Full Name" value="{{old('user_name') }}">
                            @error('user_name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="form-group @error('email') name_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/mail.png')}}">
                            <input type="text" name="email" value="{{old('email') }}" class="form-control" id="exampleInputEmail1" placeholder="Email Address" autocomplete="off">
                            @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="form-group @error('contact_no') name_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/phone.png')}}">
                            <input type="text" name="contact_no" value="{{old('contact_no') }}" class="form-control" id="ContactNumber" data-mask="999-999-9999" placeholder="Contact Number">
                            @error('contact_no')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="form-group @error('organization_name') name_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/org.png')}}">
                            <input type="text" name="organization_name" value="{{old('organization_name') }}" class="form-control" id="orgname" placeholder="Organization Name">
                            @error('organization_name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="form-group @error('password') pwd_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/pass_icon.png')}}">
                            <!-- <input type="text" name="password" class="form-control" id="orgname" placeholder="Password"> -->
                            <input id="password-field" type="password" value="{{old('password') }}" name="password"  placeholder="Enter password" class="form-control">
                            <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                            @error('password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 login_btn ">SIGN UP</button>
                    <div class="text-center signup_text py-4 mt-3">Already have an account? <span>&nbsp;<a href="{{ url('/') }}">LOGIN</a></span></div>
                </form>
            </div>

        </div>
<!-- Bootstrap link -->
<script src="{{asset('js/front_js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/front_js/popper.min.js')}}"></script>
<script src="{{asset('js/front_js/slim.min.js')}}"></script>
<!-- js link -->
<script src="{{asset('js/front_js/custom.js')}}" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script>
  $(document).ready(function(){
        $('input').attr('autocomplete','off');
    });
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