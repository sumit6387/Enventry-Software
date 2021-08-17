<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Product</title>

  @include('layouts.css-link')
  <script>
    function showCategory(id){
      if(id != ''){
        $.get(`{{ url('/getCategory/') }}`+"/"+id,(data,status)=>{
          if(data.status){
            st = ``;
            (data.data).forEach(element => {
                st+= `<option  value="${element.id}">${element.category}</option>`;
            });
            $('#category').html(st);
          }else{
            alert(data.msg);
          }
        
        });
      }
  }
  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Product"])

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
            <span><button class="btn btn-primary" style="margin-left: 90%;" data-toggle="modal" data-target="#exampleModal">Add Product</button></span>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Sr No.</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>GST(%)</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($products as $key=>$product)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $product->product_id }}</td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->brand_name }}</td>
                  <td>{{ $product->category_name }}</td>
                  <td>₹ {{ $product->price }}</td>
                  <td>{{ $product->quantity }}</td>
                  <td>{{ $product->gst }}</td>
                  <td><a href="{{ url('/editproduct/'.$product->product_id) }}" class="btn btn-primary" style="margin-left: 10%;margin-right:5%;">Edit</a><span><a href="{{ url('/deleteproduct/'.$product->product_id) }}" class="btn btn-danger">Delete</a></span></td>
                </tr>
                @endforeach
                
              
              </tbody>
              
            </table>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="alert">
            
        </div>
        <form id="quickForm" action="{{ url('/product') }}" method="POST" class="database_operation">
          <div class="card-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Name</label>
              <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Product Name">
              {{ csrf_field() }}
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Brand Name</label>
              <select class="form-control select2" onchange="showCategory(this.value)" name="brand" style="width: 100%;">
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
                <option  value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Category</label>
              <select class="form-control select2" name="category" id="category" style="width: 100%;">
                    <option  value="">Select Category</option>
              </select>
            </div>
            <div class="form-group">
              <label for="price">Price </label>
              <input type="text" name="price" class="form-control" id="exampleInputEmail1" placeholder="Enter Price">
            </div>
            <div class="form-group">
              <label for="quantity">Quantity </label>
              <input type="text" name="quantity" class="form-control" id="exampleInputEmail1" placeholder="Enter Quantity">
            </div>
            <div class="form-group">
              <label for="gst">GST (%) </label>
              <input type="text" name="gst" class="form-control" id="exampleInputEmail1" placeholder="Enter GST(%)">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            
          </div>
          <!-- /.card-body -->
        </form>
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
  <script>
    $(document).ready(()=>{
      $('.dataTables_filter input[type="search"]').css(
    {'width':'400px','display':'inline-block',"margin-left":"15%"}
  );
  setTimeout(() => {
    $('#alert1').hide();
  }, 2000);

  $('.database_operation').submit(()=>{
        var data = $('.database_operation').serialize();
        var url = $('.database_operation').attr('action');
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
  
    });
  </script>
</body>
</html>
