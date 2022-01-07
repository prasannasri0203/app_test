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
                <h2>Enter 4 digit code</h2>
                <p>Enter your 4 digit code that you received on your register Email</p>
                <div id="wrapper_otp">
                    <div id="dialog">
                         @if(Session::get('success'))
                            <div class="alert alert-success" role="alert">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        <form class="form-group" action="{{url('otpsubmit')}}" method="POST">
                            @csrf 
                            <input type="hidden" name="email" value="{{$email}}">
                            <div class="mb-2 {{(count($errors->all()) > 0) ? 'name_err' : ''}}" id="form">
                                <input type="text" id="otp1"  maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                <input type="text" id="otp2"   maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                <input type="text"  id="otp3"  maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                                <input type="text"  id="otp4"   maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                                />
                                <input type="hidden" name="otp" value="">
                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <a href="{{url('/resend-otp/'.$email)}}" class="resend_otp">Resend OTP?</a>
                            <button type="submit" class="btn w-100 Continue_btn mt-5">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script  src="{{asset('js/front_js/forgot.js')}}" type="text/javascript"></script>
</body>
</html>