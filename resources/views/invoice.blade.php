<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Invoice - Enventory Software</title>
  </head>
  <body>
        <button class="btn btn-primary" style="margin-left: 45%;margin-top:5%;" id="printSlip">Print Slip</button>
        <div class="container" style="margin: 4% 0% 4% 4%;
        ">

                <div class="row">
                    <div class="col-md-8"><img src="{{ url('/images/invoicelogo.png') }}" style="height: 82%;
                        width: 38%;" alt="Inventry Logo"></div>
                    <div class="col-md-4">
                        <p><b>Dhruve Collection</b></p>
                        <p>15-16, Vishwakarma complex.</p>
                        <p>Shastripuram road Sikandra Agra – 282007</p>
                        <br>
                        <p>PAN NO : <b>AGCPT6740J</b> </p>
                        <p>GST NO : <b>09AGCPT6740J1ZY</b></p>
                    </div>
                </div>
                <br>
                <h1>INVOICE</h1>
                <div class="row">
                    <div class="col-md-4">
                        <p><b>Name : {{ $customer->name }}</b> </p>
                        <p><b>Mobile No : {{ $customer->mobile_no }}</b></p>
                        <p><b>Email : {{ $customer->email }}</b> </p>
                        <p><b>Address : {{ $customer->address }}, {{ $customer->pincode }}</b> </p>
                    </div>
                    <div class="col-md-4"><h6><b> Ship To :</b></h6>
                    <p>N/A</p></div>
                    <div class="col-md-4">
                        <p><b>Invoice Number: </b>&nbsp;&nbsp;&nbsp;&nbsp;{{ $order->order_id }}</p>
                        <p><b>Invoice Date: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ date('d M, y',strtotime($order->updated_at)) }}</p>
                        <p><b>Order Number: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $ordercount+1 }}</p>
                        <p><b>Order Date: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ date('d M, y',strtotime($order->created_at)) }}</p>
                        <p><b>Payment Mode: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>
                            <select name="mode" id="">
                                <option value="Cash">Cash</option>    
                                <option value="Digital">Digital</option>    
                            </select>    
                        </span></p>
                    </div>
                </div>
                <div class="row">
                    <table class="table">
                        <thead style="background-color:rgb(36, 32, 32);color:white;">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $amount = 0;
                            @endphp
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product['name'] }} </td>
                                    <td>{{ $product['quantity'] }}</td>
                                    <td> ₹ {{ $product['price']  }} * {{ $product['quantity']}} =  ₹ {{ $product['price'] * $product['quantity']}}</td>
                                    @php
                                        $amount += $product['price'] * $product['quantity'];
                                    @endphp
                                </tr>
                            @endforeach
                            @php
                                $gst = ($amount * 18)/100;
                                $totalAmount = $amount+$gst;
                            @endphp
                            
                        </tbody>
                    </table>
                    <hr>
                    <div class="col-md-7"></div>
                    <div class="col-md-5">
                        <hr>
                        <p><b>Sub Total </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ₹ {{ $amount }}</p>
                        <hr>
                        <p><b>Discount  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ₹ 0</p>
                        <hr>
                        <p><b>GST (18%)  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₹ {{ $gst }}</p><hr>
                        <p><b>Total   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ₹ {{ $totalAmount }}</p>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <h6>Goods  once sold will not be taken back .Only exchanged within 07 days.</h6>
                    </div>
                    <div class="col-md-3"></div>
                </div>
        </div>
    @include('layouts.js-link')
    <script>
            $('#printSlip').click(()=>{
                $.get(`{{ url('/changeStatusOfOrder') }}`,(data,status)=>{
                    if(data.status){
                        window.print();
                        window.location.href = `{{ url('/order') }}`;
                    }
                });
            });
    </script>

  </body>
</html>