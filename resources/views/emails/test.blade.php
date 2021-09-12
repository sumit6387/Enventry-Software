<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> --}}

    <title>Order History</title>
    <style>
        body{
            background: antiquewhite;
        }
        table, th, td {
            border: 1px solid black;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
    </style>
  </head>
  <body>
    <h2 align="center" class="mt-3">Order History</h2>
    <div class="container">
        <table class="table table-bordered table-striped table-hovered">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Customer Name</th>
                    <th>Customer Mobile No.</th>
                    <th>Discount</th>
                    <th colspan="5">ProductDetail</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Product</td>
                    <td>Quantity</td>
                    <td>GST</td>
                    <td>Price</td>
                    <td>Total Price</td>
                    <td></td>
                </tr>
                @php
                    $gstamount = 0;
                @endphp
                @foreach ($data as $value)
                @php
                // dd($value['products']);
                    $row = count($value['products']);
                    $first = 0;
                @endphp
                    <tr>
                        <td rowspan="{{ $row }}">{{ $value['invoice_no'] }}</td>
                        <td rowspan="{{ $row }}">{{ $value['customer_name'] }}</td>
                        <td rowspan="{{ $row }}">{{ $value['customer_no'] }}</td>
                        <td rowspan="{{ $row }}">{{ $value['discount'] }} %</td>
                        <td>{{ $value['products'][0]['name'] }}</td>
                        <td>{{ $value['products'][0]['quantity'] }}</td>
                        <td>{{ $value['products'][0]['gst'] }} %</td>
                        <td>Rs {{ $value['products'][0]['price'] }}</td>
                        @php
                            $amount = ($value['products'][0]['price']*$value['products'][0]['quantity']) + ($value['products'][0]['price']*$value['products'][0]['quantity']*$value['products'][0]['gst'])/100;
                            $amount += ($amount*$value['discount'])/100;
                            $gstamount+= ($value['products'][0]['price']*$value['products'][0]['quantity']*$value['products'][0]['gst'])/100;
                            $totalhistoryamount += $value['total_amount'];

                        @endphp
                        <td>Rs {{ $amount }}</td>
                        <td rowspan="{{ $row }}">Rs {{ $value['total_amount'] }}</td>
                    </tr>
                    @foreach ($value['products'] as $item)
                        <?php
                        $amount1 = 0;
                            if($first==0){
                                $first += 1;
                                continue;
                            }else{
                                ?>
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ $item['gst'] }} %</td>
                                    <td>Rs {{ $item['price'] }}</td>
                                    @php
                                        $amount1 = ($item['price']*$item['quantity']) + ($item['price']*$item['quantity']*$item['gst'])/100;
                                        $amount1 += ($amount1*$value['discount'])/100;
                                        $gstamount+= ($item['price']*$item['quantity']*$item['gst'])/100;
                                    @endphp
                                    <td>Rs {{ $amount1 }}</td>
                                    </tr>
                                    <?php
                            }
                            ?>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="3">Total GST(in rupees)</td>
                    <td colspan="3">Rs {{ $gstamount }}</td>
                    <td colspan="3">Total Amount Of History</td>
                    <td>Rs {{ $totalhistoryamount }}</td>
                </tr>
                
            </tbody>
        </table>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script> --}}
  </body>
</html>