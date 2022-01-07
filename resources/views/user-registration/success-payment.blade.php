<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment Successfull</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css link -->
    <link rel="icon" type="image/png" href="{{asset('images/fevi.png')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/style.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- responsive link -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/front_css/custom_responsive.css')}}">
</head>

<body>
    <div class="page-wrapper">

        <!-- main section -->
        <main class="page-c">
            <div class="dash-r">
                <div class="logo payment_logo">
                    <img src="{{asset('images/kaizen_logo_new.png')}}">
                    <!-- <img src="{{asset('images/front_images/user_module/kaizen-logo.png')}}" alt="kaizen"> -->
                </div>
            </div>
            <input type="hidden" id="user_plan_id" value="{{!empty($user->id) ? $user->plan_id : '0'}}">
            <input type="hidden" id="user_id" value="{{!empty($user->id) ? $user->id : '0'}}">
            <input type="hidden" id="coupon_id" value="0">
            <section class="whole success_payment">
                
                @if($user->user_role_id == '4')
                    <p>Thank you for your request!</p>
                    <!-- <p>You are successfully registered as a Enterpriser User</p> -->
                    <div class="text-center signup_text py-4 mt-3">Our support team will get back to you shortly!</div>
                @else
                <p>Thank you for joining with us!</p>
                <p>You are successfully registered as a {{$user['userRole']['role']}} User.</p>
                <div class="text-center signup_text py-4 mt-3">Login to your account <span>&nbsp;<a href="{{ url('/') }}">Click Here</a></span></div>
                @endif
            </section>
        </main>

    </div>
</body>

</html>