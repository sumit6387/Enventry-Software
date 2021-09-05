<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <center>
        <h1 style="margin-top: 30px;"><i>Online Web Care</i></h1></center>
        <div class="container" style="background-color:#F3ECEC; font-size:15px;" >
        <br>
            <div style="margin-left:5%;margin-right:5%;"> 
                <p style="margin-top: 10px;"><i>Hi <b><?php echo strtoupper($name);  ?></b></i></p><br>
                <p>You Requested To change the Password For Smart Enventry.
                    Click On Link To Change Password:-</p>
                <p><i>Link :-<b><a href="{{ url('/change-password/'.$email) }}">Change Password</a></b></i></p>
                <h5><i>Online Web Care </i></h5>
            </div>
        </div>
</body>
</html>

