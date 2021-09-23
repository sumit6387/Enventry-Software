<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
    <title>View Invoice</title>
    <style>
        body{
            font-size: 23px
        }
        .container{
            margin: 4% 0% 4% 4%;
        }

        .img{
            height:82%;
            width:38%;
        }
        .row{
            column-count: 2;
        }
        .table{
            border-collapse: collapse;
            width: 85%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
        @php
            if ($order->invoice_type == 'sales') {
                $buttontxt = "Sale Invoice";
            }else{
                $buttontxt = "Tax Invoice";
            }
        @endphp
        <h3 style="margin-left: 40%;margin-top:5%;" id="printSlip">{{ $buttontxt }}</h3>
    <div class="container">
        <div class="row">
            @php
            $user = \App\Models\User::where('email',session('email'))->get()->first();
        @endphp
            <div class="first">
                <img src="{{ $user->logo }}" class="img" alt="">
                <p>&nbsp;</p>
            </div>
            <div class="second">
                <p><b>{{ $user->company_name }}</b></p>
                <p>@php
                    $add = '';
                        if(isset($user->address)){
                            $arr = explode(',',$user->address);
                            foreach ($arr as $key => $value) {
                                $add = "<p>".$add.$value.",</p>";
                            }
                        }
                    @endphp
                    @php
                        echo $add;
                    @endphp
                </p>
                            <p>Website : <b>{{ $user->website }}</b> </p>
                            {{-- <p>PAN NO : <b>AGCPT6740J</b> </p> --}}
                            <p>GST NO : <b>{{ $user->gst_no }}</b></p>
                            <p>Mobile NO : <b>@if (isset($user->mobile_no))
                                {{ $user->mobile_no }}
                            @endif</b></p>
            </div>
        </div>
        <h1 style="margin-top: 4%;">INVOICE</h1>
        <div style="column-count: 2;">
            <div>
                <p><b>Name</b> : <span>{{ $customer->name }}</span></p>
                <p><b>Mobile No</b> : <span>{{ $customer->mobile_no }}</span></p>
                <p><b>Email</b> : <span>{{ $customer->email }}</span></p>
                <p><b>Address</b> : <span>{{ $customer->address }}, {{ $customer->pincode }}</span></p>
                <p> <b>GST No</b> : <span>{{ $customer->gst_no }}</span> </p>
            </div>
            <div>
                <p><b>Invoice Number: </b> &nbsp;&nbsp;&nbsp;&nbsp;<span>{{ $order->order_serial_id }}</span> </p>
                <p><b>Invoice Date:  </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>{{ date('d M, y',strtotime($order->updated_at)) }}</span> </p>
                <p><b>Order Number:  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>{{ $ordercount+1 }}</span> </p>
                <p><b>Order Date:  </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>{{ date('d M, y',strtotime($order->created_at)) }}</span> </p>
                <p><b>Payment Mode:  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>Cash</span> </p>
            </div>
        </div>
        <div>
            <table class="table">
                <thead style="background-color:rgb(245, 245, 245);color:black;">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>GST</th>
                        <th>Discount</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $amount = 0;
                        $amountwithoutgst = 0;
                    @endphp
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product['name'] }} </td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>@if ($product['gst'])
                                {{ $product['gst'] }}
                                @else
                                0
                            @endif% </td>
                            <td> {{ $product['product_discount']}} %</td>
                            <td> ₹ {{ $product['price']}}</td>
                            @php
                                $price = $product['price'] - ($product['price']*$order->discount)/100;
                                $price = $price - ($price*$product['product_discount']/100);
                            @endphp
                            <td>₹ {{ $price  }} * {{ $product['quantity'] }} + ₹ {{ number_format($price*$product['quantity']*$product['gst']/100,2)  }} =₹ {{ number_format($price*$product['quantity'] +($price*$product['quantity']*$product['gst']/100),2)  }} </td>
                            @php
                                $amount += $price*$product['quantity'] +($price*$product['quantity']*$product['gst']/100) ;
                                $amountwithoutgst += $price*$product['quantity'];
                            @endphp
                        </tr>
                    @endforeach
                    @php
                    $gs = 0;
                        $gst = ($amount * $gs)/100;
                        $totalAmount = $amount+$gst;
                        // dd($totalAmount);
                        // if($order->discount > 0){
                        //     $totalAmount = $totalAmount - ($totalAmount*$order->discount) /100;
                        // }
                    @endphp
                </tbody>
            </table>
        </div>
        <div style="column-count: 3;width:80%;">
            <div>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>
            <div>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>
            <div>
                <p><b>Sub Total </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rs {{ $amountwithoutgst }}</p>
                <hr>
                <p><b>Discount  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span>{{ $order->discount }} %</span></p>
                <hr>
                <p><b>Total   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rs <span id="total">{{ number_format($totalAmount,2)  }}</span></p>
            </div>
        </div>
        <div style="column-count: 2;">
            <div>
                <p><b>Total Amount(in words) : </b> <span id="amount_in_words"> @php
                    $number = intval($totalAmount);
                    $locale = 'en_US';
                    $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
                    $in_words = numfmt_format($fmt, $number);

                    echo($in_words);
                @endphp</span> rupees</p>
            </div>
            <div>
                <p>&nbsp;</p>
            </div>
        </div>
        <div style="column-count: 2;">
            <div>
                <p><b>Goods once sold will not be taken back .Only exchanged within 07 days.</b></p>
                <p>For online purchases please visit our website <b>{{ $user->website }}</b> .
                    And get free home delivery.</p>
            </div>
            <div>
                <p>For <b>  &nbsp;&nbsp;{{ $user->company_name }}</b></p>
                <p>______________________</p>
            </div>
        </div>
        <div>
            
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(()=>{
            function getPdf(){
                var order_id = `{{ Request::segment(2) }}`;
                var pdf = new jsPDF('p', 'mm', 'a4');
                pdf.addHTML(document.body, function() {
                    pdf.save(`invoice-${order_id}.pdf`);
                    window.location.href=`{{ url('/orderHistory') }}`
                });

            }
            setTimeout(() => {
            getPdf();
            }, 2000);

        });
    </script>
</body>
</html>