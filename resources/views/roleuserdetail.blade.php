<!doctype html>
<html lang="en">
  <head>
    <title>TaxReport</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <h3> User Login Details </h3>
                <p> Hi {{ucwords($roleuserdetail['username'])}},</p>
                @if($roleuserdetail['update'] == 0)
                  @if($roleuserdetail['password'] != '')
                    <p> You have been added as a <b> {{$roleuserdetail['role']}}</b> user under '{{ucfirst($roleuserdetail['parent_name'])}}' user. Please check login details below.</p>
                    <p>Login url : <a href="{{$roleuserdetail['site_link']}}">Click Here</a></p>
                    <p>Email : {{$roleuserdetail['email']}}</p>
                    <p>Password : {{$roleuserdetail['password']}}</p>
                  @else 
                    <p> You have been updated as a <b> {{$roleuserdetail['role']}}</b> user under '{{ucfirst($roleuserdetail['parent_name'])}}' user.</p>
                  @endif               
                @else
                  <p> Your login credential has been updated. Please check login details below.</p>
                  <p>Login url : <a href="{{$roleuserdetail['site_link']}}">Click Here</a></p>
                  <p>Email : {{$roleuserdetail['email']}}</p>
                  <p>Password : {{$roleuserdetail['password']}}</p>
                @endif  
                <br/>
                <p> Best Regards</p>
                <p> Kaizen Hub </p>
            </div>
        </div>
    </div>
  </body>
</html>