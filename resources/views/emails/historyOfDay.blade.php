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
            <div style="margin-left:5%;margin-right:5%;overflow-x:scroll;"> 
                <p style="margin-top: 10px;"><i>Hi <b>Sumit</b></i></p>
                <p>Your today <b>{{ date('d-m-y') }}</b> order history:-</p>
                <table style="width: 70%;border: 1px solid black;">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Customer Name</th>
                            <th>Mobile No</th>
                            <th>Products</th>
                            <th>Amount</th>
                        </tr>
                    </thead>    
                    <tbody>
                        <tr style="text-align: center">
                            <td>0019</td>
                            <td>sumit</td>
                            <td>8998888</td>
                            <td>shoe,</td>
                            <td>4000</td>
                        </tr>
                    </tbody>
                </table>
                <h4><i>Online Web Care </i></h4>
            </div>
        </div>
</body>
</html>

