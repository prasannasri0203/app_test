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
                <h3>Welcome, We’re excited you’ve joined!</h3>
                <p> Hi {{$userdetail['name']}},</p>
                @if($userdetail['role'] == '4')
                    <p>Thank you for your request. Our support team will get back to you shortly!</p>
                @else
                    <p>Thank you for joining with us!</p>
                    <?php if($userdetail['role'] == '1'){
                    $role = 'Team';
                    }else if($userdetail['role'] == '2'){
                    $role = 'Trial';
                    }else if($userdetail['role'] == '3'){
                    $role = 'Individual';
                    }
                    ?>

                    <p>You are successfully registered as a {{$role}} User. Please check your plan details below.</p>
                    <p>Current Plan Name : {{ucwords($userdetail['plan'])}}</p>
                    @if($userdetail['role'] != '2')
                        <p>Amount : {{$userdetail['amt']}}</p>
                    @endif
                    <p>Next Renewal Date : {{date('d-m-Y',strtotime($userdetail['date']))}}</p>
                @endif
                <br/>
                <p> Best Regards</p>
                <p> Kaizen Hub </p>
            </div>
        </div>
    </div>
  </body>
</html>