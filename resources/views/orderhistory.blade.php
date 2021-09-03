<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Client</title>

  @include('layouts.css-link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Client"])

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
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Invoice No</th>
                    <th>Customer Name</th>
                    <th>Customer Mobile No</th>
                    <th>Products</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
                <tbody>
                      @foreach ($orders as $key=>$order)
                          <tr>
                            <td>{{ $key+1 }}</td>
                            <td>@if ($order->order_serial_id)
                              {{ $order->order_serial_id }}
                              @else
                              {{ $order->order_id }}
                            @endif</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_mobile_no }}</td>
                            <td>{{ $order->products }}</td>
                            <td>₹ {{ $order->total_amount }}</td>
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

  <script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": true,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  </script>
  <script>
    $(document).ready(()=>{
        setTimeout(() => {
            $('#alert1').hide();
        }, 2000);
      $('.dataTables_filter input[type="search"]').css(
    {'width':'400px','display':'inline-block',"margin-left":"15%"}
  );
    });
  </script>
</body>
</html>
