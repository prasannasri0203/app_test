<!doctype html>
<html lang="en">
  <head>
    <title>Welcome To Kaizen Hub</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <?php
                $fcLink='https://apps.kaizenhub.ca';
            ?>
                <h3> Welcome, We’re excited you’ve joined! </h3>
                <p> Hi {{$userdetail['username']}},</p>
                <p>You are successfully added as a {{$userdetail['userrole']}} User in "{{ucwords($userdetail['subscription']['plan_name'])}}" plan. Please check your login details below.</p>
                <p>Link : <a href="{{$fcLink}}">Login Link</a></p>
                <p>Email : {{$userdetail['email']}}</p>
                <p>Password : {{$userdetail['password']}}</p>
                <br/>
                <p> Best Regards</p>
                <p> Kaizen Hub </p>
            </div>
        </div>
    </div>
  </body>
</html>