<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
    <title>Document</title>
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
    <div class="container">
        <div class="row">
            <div class="first">
                <img src="{{ url('/images/invoicelogo.png') }}" class="img" alt="">
                <p>&nbsp;</p>
            </div>
            <div class="second">
                <p><b>Smart Inventry</b></p>
                <p>near chani service station,
                    nighasan lakhimpur kheri,
                    262903
                </p>
                            <p>Website : <b>https://smartenventry.ml</b> </p>
                            {{-- <p>PAN NO : <b>AGCPT6740J</b> </p> --}}
                            <p>GST NO : <b>SDFGHJ34567</b></p>
                            <p>Mobile NO : <b>6387577904</b></p>
            </div>
        </div>
        <h1 style="margin-top: 4%;">INVOICE</h1>
        <div style="column-count: 2;">
            <div>
                <p><b>Name</b> : <span>Sumit Yadav</span></p>
                <p><b>Mobile No</b> : <span>9415455867</span></p>
                <p><b>Email</b> : <span>sumitkumar993618@gmail.com</span></p>
                <p><b>Address</b> : <span>nighasan, 262903</span></p>
                <p> &nbsp; </p>
            </div>
            <div>
                <p><b>Invoice Number: </b> &nbsp;&nbsp;&nbsp;&nbsp;<span>00001</span> </p>
                <p><b>Invoice Date:  </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>10 Sep, 21</span> </p>
                <p><b>Order Number:  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>2</span> </p>
                <p><b>Order Date:  </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>10 Sep, 21</span> </p>
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
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Shoe</td>
                        <td>1</td>
                        <td>10 %</td>
                        <td>Rs 100</td>
                        <td> Rs 130</td>
                    </tr>
                    <tr>
                        <td>Shoe</td>
                        <td>1</td>
                        <td>10 %</td>
                        <td>Rs 100</td>
                        <td> Rs 130</td>
                    </tr>
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
                <hr>
                <p><b>Sub Total </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rs 130</p>
                <hr>
                <p><b>Discount  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span>0 %</span></p>
                <hr>
                <p><b>Total   </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rs <span id="total">130</span></p>
            </div>
        </div>
        <div style="column-count: 2;">
            <div>
                <p><b>Total Amount(in words) : </b> <span> one hundred thirty rupees</span></p>
            </div>
            <div>
                <p>&nbsp;</p>
            </div>
        </div>
        <div style="column-count: 2;">
            <div>
                <p><b>Goods once sold will not be taken back .Only exchanged within 07 days.</b></p>
                <p>For online purchases please visit our website <b>https://smartenventry.ml</b> .
                    And get free home delivery.</p>
            </div>
            <div>
                <p>For <b>  &nbsp;&nbsp;Smart Inventry</b></p>
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
                var order_id = `sumit`;
                var pdf = new jsPDF('p', 'mm', 'a4');
                pdf.addHTML(document.body, function() {
                    pdf.save(`${order_id}.pdf`);
                    window.location.href=`{{ url('/orderHistory') }}`
                });

            }
            getPdf();

        });
    </script>
</body>
</html>