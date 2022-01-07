<!doctype html>
<html lang="en">
  <head>
    <title>Enterprise User Request</title>
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
                @if($usereditmail['status'] ==  1)
                    <h3>Enterprise User Request Approved</h3>
                    <p> Hi {{$usereditmail['username']}},</p>
                    <p>Your enterprise plan "{{ucwords($usereditmail['subscriptionplan'])}}" is approved with "{{$usereditmail['team_count']}}" teams by our team. You can login into your account.</p>
                    <p>Link : <a href="{{$fcLink}}">Login Link</a></p>
                @else
                    <h3>Enterprise User Request Rejected</h3>
                    <p> Hi {{$usereditmail['username']}},</p>
                    <p>Your plan has been rejected for {{$usereditmail['reason']}}. Please contact admin if you have any queries.</p>
                @endif
                <br/>
                <p> Best Regards</p>
                <p> Kaizen Hub </p>
            </div>
        </div>
    </div>
  </body>
</html>