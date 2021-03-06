<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

    <title>Invoice - Inventry Software</title>
  </head>
  <body id="pdf">
        @php
            if ($_GET['text'] == 'sales') {
                $buttontxt = "Sales Invoice";
            }else{
                $buttontxt = "Tax Invoice";
            }
        @endphp
        <button class="btn btn-primary" style="margin-left: 45%;margin-top:5%;" id="printSlip">{{ $buttontxt }}</button>
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
                        @php
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
                        <p>Website : <b>{{ $user->website }}</b> </p>
                        {{-- <p>PAN NO : <b>AGCPT6740J</b> </p> --}}
                        <p>GST NO : <b>{{ $user->gst_no }}</b></p>
                        <p>Mobile NO : <b>@if (isset($user->mobile_no))
                            {{ $user->mobile_no }}
                        @endif</b></p>
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
                        <p><b>GST No : @if ($customer->gst_no)
                            {{ $customer->gst_no }}
                        @endif</b> </p>
                    </div>
                    <div class="col-md-4"><h6><b> Ship To :</b></h6>
                    <p>N/A</p></div>
                    <div class="col-md-4">
                        <p><b>Invoice Number: </b>&nbsp;&nbsp;&nbsp;&nbsp;{{ $order->order_serial_id }}</p>
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
                                    <td> <input type="text" style="width: 30px;" value="{{ $product['product_discount'] }}" data-id="{{ $product['product_id'] }}" data-orderid="{{ $order->order_id }}" class="product_discount">%</td>
                                    <td> ??? {{ $product['price']}}</td>
                                    @php
                                        $price = $product['price'] - (($product['price']*$order->discount)/100);
                                        $price = $price - ($price*$product['product_discount']/100)
                                    @endphp
                                    <td>??? {{ $price  }} * {{ $product['quantity'] }} + ??? {{ $price*$product['quantity']*$product['gst']/100 }} =??? {{ $price*$product['quantity'] +($price*$product['quantity']*$product['gst']/100) }} </td>
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
                    <hr>
                    <div class="col-md-7"></div>
                    <div class="col-md-5">
                        <hr>
                        <p><b>Sub Total </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ??? {{ $amountwithoutgst }}</p>
                        <hr>
                        <p><b>Discount  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span><input type="text" id="discount" style="width: 30px;" value="{{ $order->discount }}"> %</span></p>
                        <hr>
                        <p><b>Total   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ??? <span id="total">{{ number_format($totalAmount,2)  }}</span></p>
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
                    <div class="col-md-6">
                        @if (isset($user->website))
                            <p>For online purchases please visit our website <b>{{ $user->website }}.</b><br> And get free home delivery.</p>
                            
                        @endif
                    </div>
                    <div class="col-md-6" style="padding-left: 9%;">
                        <br>
                        &nbsp;&nbsp;&nbsp;_____________________
                    </div>
                </div>
        </div>
    @include('layouts.js-link')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script>
        $(document).ready(()=>{
            $.get(`{{ url('/balance/'.$totalAmount) }}`,(data,status)=>{
                console.log(data);
            });
        });
            $('#printSlip').click(()=>{
                $.get(`{{ url('/changeStatusOfOrder/') }}`,(data,status)=>{
                        console.log(data);
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
                //  alert(order_id);
                 
                $.get(`{{ url('/updateTotalBalance/') }}/${order_id}/${finaltotal}/${disc}`,(data,status)=>{
                    $('#total').html(finaltotal.toFixed(2));
                    $('#amount_in_words').html(convertNumberToWords(finaltotal))
                    window.location.href=window.location.href;
                });
            });

            $('.product_discount').blur((e)=>{
                var discount = $(e.target).val();
                var product_id = $(e.target).attr('data-id');
                var order_id = $(e.target).attr('data-orderid');
                $.get(`{{ url('/discountOnProduct/') }}/${order_id}/${product_id}/${discount}`,(data,status)=>{
                    if(data.status){
                        window.location.href = window.location.href;
                    }else{
                        alert(data.msg)
                    }
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