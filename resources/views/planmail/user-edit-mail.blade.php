<!doctype html>
<html lang="en">
  <head>
    <title>{{$usereditmail['msg']}}</title>
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
                <h3> {{$usereditmail['msg']}} </h3>
                <p> Hi {{$usereditmail['username']}},</p>
                <?php if($usereditmail['msg'] == 'Password Updation'){?>
                    <p>Your password has been updated. Login by using below credential.</p>
                    <p>Email : {{$usereditmail['email']}}</p>
                    <p>Password : {{$usereditmail['password']}}</p>
                <?php }else if($usereditmail['msg'] == 'Subscription Plan Updation'){?>  
                    <p>Your subscription plan has been changed as "{{$usereditmail['subscriptionplan']}}". If you have any queries contact admin.</p>  
                <?php }else if($usereditmail['msg'] == 'User Detail Updation'){?>
                    <p>Your subscription plan has been changed as "{{$usereditmail['subscriptionplan']}}" and password has been updated. Login by using below credential.</p>
                    <p>Email : {{$usereditmail['email']}}</p>
                    <p>Password : {{$usereditmail['password']}}</p>
                <?php }?>
                
                
                <br/>
                <p> Best Regards</p>
                <p> Kaizen Hub </p>
            </div>
        </div>
    </div>
  </body>
</html>