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
                <p style="margin-top: 10px;"><i>Hi <b>{{ $name }},</b></i></p>
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
                        @php
                            $totalproduct = 0;
                            $totalamount = 0;
                            if(count($data) == 0){
                                echo "<tr><td></td><td></td><td>You Have No History Today.<td><td></td><td></td></tr>";
                            }
                        @endphp
                        @foreach ($data as $history)
                        <tr style="text-align: center">
                            <td>{{ $history->order_serial_id }}</td>
                            <td>{{ $history->customer_name }}</td>
                            <td>{{ $history->customer_mobile_no }}</td>
                            <td>{{ $history->products }}</td>
                            <td>₹ {{ $history->total_amount }}</td>
                            @php
                                $totalproduct += $history->noofproduct;
                                $totalamount += $history->total_amount;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table style="width: 70%;margin-top:1%;">
                    <thead>
                        <tr>
                            <th>Total Product</th>
                            <th><b>{{ $totalproduct }}</b></th>
                            <th>Total Amount</th>
                            <th><b>₹ {{ $totalamount }}</b></th>
                        </tr>
                    </thead>
                </table>
                <h4 style="margin-left: 28%;"><i>Online Web Care </i></h4>
            </div>
        </div>
</body>
</html>

