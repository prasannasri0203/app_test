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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
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
                <h2 style="text-align: unset;">Forgot Password</h2>
                <p style="text-align: unset;">Do not worry! We will help you recover your password</p>
                <p class="mt-3" style="text-align: unset;">Enter your email for the verification process. We will send 4 digit code to your email</p>
                @if(Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        {{Session::get('success')}}
                    </div>
                @endif
                <form method="POST" action="{{url('/forgot-pwd-post')}}">
                    @csrf
                    <div class="form-group {{(count($errors->all()) > 0) ? 'name_err' : ''}}">
                        <img src="{{asset('images/front_images/login_imgs/mail.png')}}">
                        <input type="text" name="email" class="form-control  @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn w-100 login_btn ">Submit</button>
                </form>
                <div class="text-center signup_text py-4 mt-3"><a href="{{ url('/') }}">Login</a></span></div>
            </div>
        </div>
    </div>
</body>

</html>