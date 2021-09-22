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
            height: 100%;
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
        <div style="column-count: 3;">
            <div><p><b> From : </b> {{ date('d-m-y',strtotime($from)) }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><b> To : </b> {{ date('d-m-y',strtotime($to)) }}</span></p></div>
            <div><h2 align="center">Order History</h2></div>
        </div>
    <div class="container">
        <table class="table table-bordered table-striped table-hovered">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Customer Name</th>
                    <th>Customer Mobile No.</th>
                    <th>GST No</th>
                    <th>Discount</th>
                    <th colspan="6">ProductDetail</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4"></td>
                    <td>Product</td>
                    <td>QTY</td>
                    <td>GST</td>
                    <td>Discount</td>
                    <td>Price(in rs)</td>
                    <td>Total Price</td>
                    <td></td>
                </tr>
                @php
                    $gstamount = 0;
                    $totalhistoryamount=0;
                    $product1 = 0;
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
                        <td>{{ $value['customer_gst_no'] }}</td>
                        <td rowspan="{{ $row }}">{{ $value['discount'] }} %</td>
                        <td>{{ $value['products'][0]['name'] }}</td>
                        <td>{{ $value['products'][0]['quantity'] }}</td>
                        <td>{{ $value['products'][0]['gst'] }} %</td>
                        <td>{{ $value['products'][0]['product_discount'] }} %</td>
                        <td>{{ number_format($value['products'][0]['price'], 1) }}</td>
                        @php
                            $price1 = $value['products'][0]['price'] - ($value['products'][0]['price']*$value['discount'])/100;
                            $price1 = $price1 - ($price1*$value['products'][0]['product_discount']/100);
                            $amount = ($price1*$value['products'][0]['quantity']) + ($price1*$value['products'][0]['quantity']*$value['products'][0]['gst'])/100;
                            $gstamount+= ($price1*$value['products'][0]['quantity']*$value['products'][0]['gst'])/100;
                            $product1 += $value['products'][0]['quantity'];
                            $totalhistoryamount += $value['total_amount'];

                        @endphp
                        <td>{{ number_format($amount,1) }}</td>
                        <td rowspan="{{ $row }}">{{ number_format($value['total_amount'],1) }}</td>
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
                                    <td>{{ $item['product_discount'] }} %</td>
                                    <td>{{ number_format($item['price'],1) }}</td>
                                    @php
                                        $price = $item['price'] - ($item['price']*$item['product_discount'])/100;
                                        $price = $price-($price*$item['product_discount']/100);
                                        $amount1 = ($price*$item['quantity']) + ($price*$item['quantity']*$item['gst'])/100;
                                        $product1 += $item['quantity'];
                                        $gstamount+= ($price*$item['quantity']*$item['gst'])/100;
                                    @endphp
                                    <td>{{ number_format($amount1,1) }}</td>
                                    </tr>
                                    <?php
                            }
                            ?>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="2"><b>Total GST Amount(in rupees)</b></td>
                    <td colspan="1">{{ number_format($gstamount,1) }}</td>
                    <td><b>No Of Product</b></td>
                    <td>{{ $product1 }}</td>
                    <td colspan="5"><b>Total Amount Of History</b></td>
                    <td>{{ number_format($totalhistoryamount,1) }}</td>
                </tr>
                
            </tbody>
        </table>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script> --}}
  </body>
</html>