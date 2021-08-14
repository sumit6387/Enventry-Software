<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <center>
        <h1 style="margin-top: 30px;"><i>Smart Inventory</i></h1></center>
        <div class="container" style="background-color:#F3ECEC; font-size:15px;" >
        <br>
            <div style="margin-left:5%;margin-right:5%;"> 
                <p style="margin-top: 10px;"><i>Hi <b><?php echo strtoupper($name);  ?></b></i></p><br>
                <p>You have been granted access to the Smart Inventory Software.
                    Use the following credentials to login:-</p>
                <p><i>Link :-<b><a href="{{ url('/') }}">Login Here</a></b></i></p>
                <p><i>Email ID :-<b><?php echo $email; ?></b></i></p>
                <p><i>Password :-<b><?php echo $password; ?></b></i></p>
                <p><i>NOTE:- It is important to change the password on first time login.</i></p>
                <h5><i>Smart Inventory </i></h5>
            </div>
        </div>
</body>
</html>

