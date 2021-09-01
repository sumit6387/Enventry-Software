<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Order</title>

  @include('layouts.css-link')
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Order"])

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            @if (session('msg') && session('status'))
                <div id="alert1" class="alert alert-{{ session('status') }}" role="alert">
                    {{ session('msg') }}
                </div>
            @endif
            {{-- <h5 style="margin-left:90%;"><a href="{{ url('/cart') }}"><i class="fa fa-shopping-cart"></i> See Order</a></h5> --}}
            <div class="row">
              <div class="col-md-5">
                <h4>Order Details</h4>
                <div class="row">
                  <div class="col-md-8">
                    <select id="select-state" onchange="addCustomerToOrder(this)" class="form-control" >
                      <option value="">-- Select Customer --</option>
                      @foreach ($customers as $customer)
                        <option @if ($customer->customer_id == $customer_id)
                          selected
                        @endif value="{{ $customer->customer_id }}">{{ $customer->name }}- {{ $customer->mobile_no }}</option>
                      @endforeach
                    </select>
                    
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-primary" style="padding: 3% 14% 6% 20%;" data-toggle="modal" data-target="#exampleModal">Add Customer</button>
                  </div>
                </div>
                <table class="table table-bordered table-striped my-2" >
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="order_data">
                    {{-- order data is here --}}
                  </tbody>
                </table>
                @php
                    $order_id =  \App\Models\Order::orderby('id','desc')->where('client_id',Session::get('email'))->where('status',0)->get()->first();
                    if($order_id){
                      if($order_id->products != null){
                        $id = $order_id->order_id;
                      }else{
                        $id = "#";
                      }
                    }else{
                      $id = "#";
                      echo "<div id='msg' class='text-danger'>Please Select Customer</div>";
                    }
                @endphp
                <h4>Total Amount : <span id="totalamount"> ₹ 0</span> <span><a id="invoice" href="{{ url('/invoice/'.$id) }}" class="btn btn-primary" style="margin-left: 38%;">Invoice</a></span></h4>
              </div>
              <div class="col-md-7">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($products as $key=>$product)
                    <tr>
                      <td>{{ $product->product_id }}</td>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->category_name }}</td>
                      <td>₹ {{ $product->price }}</td>
                      <td><button class="btn btn-primary addproduct" data-id="{{ $product->product_id }}"><i class="fa fa-plus"></i> Add Item</button></td>
                    </tr>
                    @endforeach
                  </tbody>
                  
                </table>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-10">
            <div id="alert">
              {{-- alert --}}
            </div>
            <form action="{{ url('/addCustomer') }}" method="POST" class="database_operation">
              <div class="form-group">
                <label for="name"> Name : <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control">
              </div>
              <div class="form-group">
                <label for="email"> Email : </label>
                <input type="email" name="email" class="form-control">
              </div>
              <div class="form-group">
                <label for="mobile_no"> Mobile No : </label>
                <input type="text" name="mobile_no" class="form-control">
                {{ csrf_field() }}
              </div>
              <div class="form-group">
                <label for="address"> Address : </label>
                <input type="text" name="address" class="form-control">
              </div>
              <div class="form-group">
                <label for="pincode"> Pin Code : </label>
                <input type="text" name="pincode" class="form-control">
              </div>
              <div class="form-group">
                <button class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
          <div class="col-md-1"></div>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2021 <a href="#">Online Web Care</a>.</strong>
    All rights reserved.
  </footer>

  
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('layouts.js-link')
<script src="{{ url('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('public/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('public/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('public/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('public/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('public/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<script>
  $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
        $('.dataTables_filter input[type="search"]').css(
      {'width':'300px','display':'inline-block',"margin-left":"15%"}
    );
        

    function getOrders(){
      $.get(`{{ url('/getorders') }}`,(data,status)=>{
        console.log(data);
        
        if(data.order_id){
          var order_id = data.order_id;
          if(data.status && data.order.customer){
          var url = `{{ url('/invoice/') }}/${order_id}`;
          }else{
            var url = `{{ url('/order/#') }}`;
          }
        }else{
          var url = `{{ url('/order/#') }}`;
        }
          $('#invoice').attr('href',url);
        if(data.status){
            var st =``;
            var amount = 0;
              if(data.data.length > 0){
                (data.data).forEach(element => {
                amount += (parseInt(element.price) *element.product_quantity)+(parseInt(element.price) *element.product_quantity) *element.gst/100;
                console.log(amount);
                var amount_with_gst = (parseInt(element.price) *element.product_quantity)+(parseInt(element.price) *element.product_quantity) *element.gst/100;
                st += `<tr>
                            <td>${element.name}</td>
                            <td><input type="number" style="width:50px;" class="changequantity" data-id="${element.product_id}" value="${element.product_quantity}"></td>
                            <td>₹ ${amount_with_gst}</td>
                            <td><i class="fa fa-trash deleteItem" style="pointer:cursor;" data-id="${element.product_id}" aria-hidden="true"></i></td>
                          </tr>`;
              });
              $('#order_data').html(st);
              $('#totalamount').html(`₹ ${amount}`);
              $('.deleteItem').click((e)=>{
                var product_id = $(e.target).attr('data-id');
                $.get(`{{ url('/removeItem') }}`+"/"+product_id,(data,status)=>{
                  if(data.status){
                    getOrders();
                  }else{
                    alert(data.msg);
                  }
            });
          });

          $('.changequantity').blur((e)=>{
            var product_id = $(e.target).attr('data-id');
            var quantity = $(e.target).val();
            if(quantity > 0){
              $.post(`{{ url('/changeQuantity') }}`,{"product_id" : product_id,"quantity":quantity},(data,status)=>{
                if(data.status){
                  getOrders();
                }else{
                  alert(data.msg);
                }
              });
            }else{
              alert("Minimum Quantity 1.");
              $(e.target).val(1)
            }

          });
          }else{
            $('#order_data').html("No Product Found");
            $('#invoice').attr('href',`{{ url('/order/#') }}`);
          }
        }else{
          $('#order_data').html(data.msg);
          $('#invoice').attr('href',`{{ url('/order/#') }}`);
        }
      });
    }
    getOrders();

    $('.addproduct').click((e)=>{
      var product_id = $(e.target).attr('data-id');
      $.post(`{{ url('/addItem') }}`,{"product_id":product_id},(data,status)=>{
        if(data.status){
          getOrders();
        }else{
          alert(data.msg)
        }
        
      });
    });
    $('.database_operation').submit((e)=>{
        var data = $(e.target).serialize();
        var url = $(e.target).attr('action');
        $.post(url,data,(data,status)=>{
            if(data.status){
                st =`<div class="alert alert-success" role="alert">
                    ${data.msg}
                </div>`;
                setTimeout(() => {
                window.location.href = window.location.href;
            }, 2000);
            }else{
                st =`<div class="alert alert-danger" role="alert">
                    ${data.msg}
                </div>`;
            }
            $('#alert').html(st);
            
        });
        return false;
  });
    
  setTimeout(() => {
    $('#alert1').hide();
  }, 2000);
</script>
<script>
    function addCustomerToOrder(data){
      var customer_id = data.value;
      $.get(`{{ url('/addCustomerToOrder/') }}`+"/"+customer_id,(data,status)=>{
        if(data.status){
          $('#msg').hide();
          console.log(data);
          getOrders();
        }
      });
    }
</script>

</body>
</html>
