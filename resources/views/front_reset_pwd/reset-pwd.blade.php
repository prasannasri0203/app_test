<!DOCTYPE html>
<html>

<head>
    <title>Kaizen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css link -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/bootstrap.min.css')}}">
    <!--  <link rel="stylesheet" type="text/css" href="css/style.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- responsive link -->
    <!-- <link rel="stylesheet" type="text/css" href="css/responsive.css"> -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_responsive.css')}}">

</head>

<body>
    <div class="col-12 d-flex form_section p-0">
        <div class="col-lg-6 col-12 left_bg d-lg-flex align-items-center justify-content-center" style="background: #F3FCFF;">
            <img src="{{asset('images/front_images/login_imgs/login-bg.png')}}">
        </div>
        <div class="col-lg-6 col-12 right_part mt-5">
            <div class="logo logo-1 text-center">
                <img src="{{asset('images/kaizen-logo 1.png')}}">
            </div>
            <div class="right_section px-md-5 px-2 mx-md-3">
                <h2>Reset Password</h2>
                <p>Set the new password for your account</p>
                @if(Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        {{Session::get('success')}}
                    </div>
                @endif
                <form action="{{url('resetpassword-submit')}}" method="POST">
                    @csrf
                    <input type="hidden" name="otp" value="{{$user->otp}}">
                    <input type="hidden" name="email" value="{{$user->email}}">
                    <div class="form-group mb-0 {{(count($errors->all()) > 0) ? 'name_err' : ''}}">
                        <img src="{{asset('images/front_images/login_imgs/pass_icon.png')}}">
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Create Password">
                        <img src="" class="eye_icon fa fa-eye-slash field-icon toggle-password" toggle="#password-field" onclick="myPass()">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-0 {{(count($errors->all()) > 0) ? 'name_err' : ''}}">
                        <img src="{{asset('images/front_images/login_imgs/pass_icon.png')}}">
                        <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword2" placeholder="Confirm Password">
                        <img src="" class="eye_icon far fa-eye-slash field-icon toggle-password" toggle="#password-field" onclick="myPassword()">
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn w-100 login_btn">Reset Password</button>
                  
                </form>
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
</body>

</html>

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