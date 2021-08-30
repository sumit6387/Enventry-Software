<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Invoice - Inventry Software</title>
  </head>
  <body>
        <button class="btn btn-primary" style="margin-left: 45%;margin-top:5%;" id="printSlip">Print Slip</button>
        <div class="container" style="margin: 4% 0% 4% 4%;
        ">
        @php
        $user = \App\Models\User::where('email',session('email'))->get()->first();
    @endphp

                <div class="row">
                    <div class="col-md-8"><img src="{{ $user->logo }}" style="height: 82%;
                        width: 38%;" alt="Inventry Logo"></div>
                    <div class="col-md-4">
                        
                        <p><b>{{ $user->company_name }}</b></p>
                        <p>15-16, Vishwakarma complex.</p>
                        <p>Shastripuram road Sikandra Agra – 282007</p>
                        <br>
                        <p>PAN NO : <b>AGCPT6740J</b> </p>
                        <p>GST NO : <b>{{ $user->gst_no }}</b></p>
                        <p>Mobile NO : <b>{{ $user->gst_no }}</b></p>
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
                                    <td> ₹ {{ $product['price']  }} * {{ $product['quantity']}}   =  ₹ {{ $product['price'] * $product['quantity']}}</td>
                                    @php
                                        $amount += $product['price'] * $product['quantity'] ;
                                    @endphp
                                </tr>
                            @endforeach
                            @php
                            $gs = 0;
                            foreach ($gst as $key => $value) {
                                if($gs == 0 &&$value->amount == null){
                                    $gs = $value->gst;
                                }
                                if($value->amount != null && $value->condition !=null){
                                    if($value->condition =="Greater"){
                                        if($amount > $value->amount){
                                            $gs = $value->gst;
                                        }
                                    }else if($value->condition == "Equal"){
                                        if($amount == $value->amount){
                                            $gs = $value->gst;
                                        }
                                    }else if($value->condition == "Less"){
                                        if($amount < $value->amount){
                                            $gs = $value->gst;
                                        }
                                    }
                                }
                            }
                                $gst = ($amount * $gs)/100;
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
                        <p><b>Discount  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span><input type="text" id="discount" style="width: 30px;"> %</span></p>
                        <hr>
                        <p><b>GST ({{ $gs }}%)  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₹ {{ $gst }}</p><hr>
                        <p><b>Total   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ₹ <span id="total">{{ number_format($totalAmount,2)  }}</span></p>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><b>Total Amount in words </b> :- <span id="amount_in_words"> @php
                            $number = intval($totalAmount);
                            $locale = 'en_US';
                            $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
                            $in_words = numfmt_format($fmt, $number);

                            echo($in_words);
                        @endphp</span> rupees</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Goods  once sold will not be taken back .Only exchanged within 07 days.</h6>
                    </div>
                    <div class="col-md-6" style="padding-left: 9%;">
                        For <b>{{ $user->company_name }}</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="padding-left: 9%;">
                        <br>
                        &nbsp;&nbsp;&nbsp;_____________________
                    </div>
                </div>
        </div>
    @include('layouts.js-link')
    <script>
        $(document).ready(()=>{
            $.get(`{{ url('/balance/'.$totalAmount) }}`,(data,status)=>{
                console.log(data);
                
            });
        });
            $('#printSlip').click(()=>{
                $.get(`{{ url('/changeStatusOfOrder/') }}`,(data,status)=>{
                    if(data.status){
                        window.print();
                        setTimeout(() => {
                            window.location.href = `{{ url('/order') }}`;
                        }, 3000);
                    }
                });
            });
            $('#discount').blur(()=>{
                var disc = $('#discount').val();
                var total = {{ $totalAmount }};
                 finaltotal = total- ((parseInt(total) * disc)/100);
                 var order_id = `{{ Request::segment(2) }}`;
                 $.get(`{{ url('/updateTotalBalance/') }}/${order_id}/${finaltotal}`,(data,status)=>{
                    $('#total').html(finaltotal.toFixed(2));
                    $('#amount_in_words').html(convertNumberToWords(finaltotal))
                });
            });

            function convertNumberToWords(amount) {
                    var words = new Array();
                    words[0] = '';
                    words[1] = 'One';
                    words[2] = 'Two';
                    words[3] = 'Three';
                    words[4] = 'Four';
                    words[5] = 'Five';
                    words[6] = 'Six';
                    words[7] = 'Seven';
                    words[8] = 'Eight';
                    words[9] = 'Nine';
                    words[10] = 'Ten';
                    words[11] = 'Eleven';
                    words[12] = 'Twelve';
                    words[13] = 'Thirteen';
                    words[14] = 'Fourteen';
                    words[15] = 'Fifteen';
                    words[16] = 'Sixteen';
                    words[17] = 'Seventeen';
                    words[18] = 'Eighteen';
                    words[19] = 'Nineteen';
                    words[20] = 'Twenty';
                    words[30] = 'Thirty';
                    words[40] = 'Forty';
                    words[50] = 'Fifty';
                    words[60] = 'Sixty';
                    words[70] = 'Seventy';
                    words[80] = 'Eighty';
                    words[90] = 'Ninety';
                    amount = amount.toString();
                    var atemp = amount.split(".");
                    var number = atemp[0].split(",").join("");
                    var n_length = number.length;
                    var words_string = "";
                    if (n_length <= 9) {
                        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                        var received_n_array = new Array();
                        for (var i = 0; i < n_length; i++) {
                            received_n_array[i] = number.substr(i, 1);
                        }
                        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                            n_array[i] = received_n_array[j];
                        }
                        for (var i = 0, j = 1; i < 9; i++, j++) {
                            if (i == 0 || i == 2 || i == 4 || i == 7) {
                                if (n_array[i] == 1) {
                                    n_array[j] = 10 + parseInt(n_array[j]);
                                    n_array[i] = 0;
                                }
                            }
                        }
                        value = "";
                        for (var i = 0; i < 9; i++) {
                            if (i == 0 || i == 2 || i == 4 || i == 7) {
                                value = n_array[i] * 10;
                            } else {
                                value = n_array[i];
                            }
                            if (value != 0) {
                                words_string += words[value] + " ";
                            }
                            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                                words_string += "Crores ";
                            }
                            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                                words_string += "Lakhs ";
                            }
                            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                                words_string += "Thousand ";
                            }
                            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                                words_string += "Hundred and ";
                            } else if (i == 6 && value != 0) {
                                words_string += "Hundred ";
                            }
                        }
                        words_string = words_string.split("  ").join(" ");
                    }
                    return words_string;
}
    </script>

  </body>
</html>