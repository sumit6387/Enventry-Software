<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enventory Software | Dashboard</title>

  @include('layouts.css-link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Dashboard"])

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $orders }}</h3>

                <p>Total Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $productNO }}</h3>

                <p>Total Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $customers }}</h3>

                <p>Total Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $brands }}</h3>

                <p>Total Brands</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
              <th>Sr No.</th>
              <th>Product ID</th>
              <th>Name</th>
              <th>Brand</th>
              <th>Category</th>
              <th>Price</th>
              <th>Qunatity</th>
          </tr>
      </thead>
          <tbody>
            @foreach ($products as $key=> $product)
            @php
                      if($product->quantity > 5){
                        $cls = '';
                      }else{
                        $cls = "table-danger";
                      }
                @endphp
                <tr class="{{ $cls }}">
                  <td>{{ $key+1 }}</td>
                  <td>{{ $product->product_id }}</td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->brand_name }}</td>
                  <td>{{ $product->category_name }}</td>
                  <td>₹ {{ $product->price }}</td>
                  
                  <td>{{ $product->quantity }}</td>
                </tr>
                
            @endforeach
          </tbody>
      </table>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="#">Sumit Kumar</a>.</strong>
    All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('layouts.js-link')
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>
