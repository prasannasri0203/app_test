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
         <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
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
                    @if(Session::get('success'))
                     <div class="col-md-12">
                       <div class="alert alert-success alert-dismissible fade show" role="alert">
                         {{Session::get('success')}}
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                       </div>
                      </div> 
                    @endif
                      @if(Session::get('error'))
                      <div class="col-md-12">
                       <div class="alert alert-danger alert-dismissible fade show" role="alert">
                           {{Session::get('error')}}
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>
                       </div>
                      </div> 
                    @endif
                    @if($sid != '')
                        <div class="col-md-12">
                           <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              {{$sid}}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                        </div> 
                    @endif
                    <form method="POST" action="{{url('/role-user-login')}}">
                        @csrf
                        <div class="form-group mt-2 pt-1 @error('email') name_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/loginuser.png')}}">
                            <input type="text" value="{{ old('email') }}" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email Address">
                            @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="form-group mt-2 pt-1 @error('password') pwd_err @enderror">
                            <img src="{{asset('images/front_images/login_imgs/pass_icon.png')}}">
                            <input type="password" name="password" class="form-control" id="password-field" placeholder="Enter Password">
                            <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                            @error('password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center justify-content-between remember_section">
                            <div class="form-group checkbox mt-3">
                               <!-- <input id="check1" type="checkbox" name="check" value="check1">
                                <label for="check1"></label>-->
                            </div>
                            <a href="{{url('/forgot-password')}}" class="">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn w-100 login_btn">Login</button>
                          </form>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<!-- phone number -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="{{asset('js/front_js/taglist.jquery.js')}}"></script>
<script src="{{asset('js/front_js/plan.js')}}"></script>
<script src="{{asset('js/front_js/custom.js')}}" type="text/javascript"></script>
        </div>
    </body>
</html>

<!--<script>
    function myPass() {
        var x = document.getElementById("exampleInputPassword1");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>-->
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
      $(".alert").alert();
</script>
<style>
    .fa-eye-slash:before {
        font-size: 19px;
    }
    
    .fa-eye:before {
        font-size: 19px;
    }
    /*   .am {
        display: none;
    } */
    /*  input[type=text] .am {
        display: block !important;
        position: absolute;
        right: 0;
    }
    
    input[type=password] .am {
        display: none;
    }
    
    input[type=text] .eye_icon {
        display: none !important;
    } */

</style>