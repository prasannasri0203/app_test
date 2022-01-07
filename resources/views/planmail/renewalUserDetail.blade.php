<!doctype html>
<html lang="en">
  <head>
    <title>Plan Renewal Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <h3>Renewal Details </h3>
                <p> Hi {{$userdetail['name']}},</p>                
                @if($userdetail['new'] == 1)
                <p>Your enterprise account request needs to be approved by our team, after the account gets approved, you can able to log in.</p>
                @else
                    <p>Your upgrade has been done successfully. Please check the plan details below.</p>
                    <p>Current Plan Name : {{ucwords($userdetail['plan'])}}</p>
                    <p>Amount : {{$userdetail['amt']}}</p>
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