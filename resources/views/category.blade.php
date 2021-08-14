<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Category</title>

  @include('layouts.css-link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Category"])

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
            <span><button class="btn btn-primary" style="margin-left: 90%;" data-toggle="modal" data-target="#exampleModal">Add Category</button></span>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Sr No.</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($categories as $key=>$category)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $category->category }}</td>
                  <td>{{ $category->brand_name }}</td>
                  <td><a href="{{ url('/editcategory/'.$category->id) }}" class="btn btn-primary" style="margin-left: 30%;margin-right:5%;">Edit</a><span><a href="{{ url('/deletecategory/'.$category->id) }}" class="btn btn-danger">Delete</a></span></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="alert">
            
        </div>
        <form id="quickForm" action="{{ url('/category') }}" method="POST" class="database_operation">
          <div class="card-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Category</label>
              <input type="text" name="category" class="form-control" id="exampleInputEmail1" placeholder="Enter Category">
              {{ csrf_field() }}
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Brand Name</label>
              <select class="form-control select2" name="brand" style="width: 100%;">
                @foreach ($brands as $brand)
                    <option  value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
              </select>
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
    <strong>Copyright &copy; 2021 <a href="#">Sumit Kumar</a>.</strong>
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
