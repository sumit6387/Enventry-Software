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
            
            <span><button class="btn btn-primary" style="margin-left: 90%;" data-toggle="modal" data-target="#exampleModal">Add Client</button></span>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                      @foreach ($clients as $key =>$client)
                          <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td><a href="{{ url('/edit-client/'.$client->id) }}" class="btn btn-primary mx-2">Edit</a><span><a href="{{ url('/delete-client/'.$client->id) }}" class="btn btn-danger">Delete</a></span></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="alert">
            
          </div>
        
        <form id="quickForm" action="{{ url('/client') }}" method="POST" enctype="multipart/form-data">
          <div class="card-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Name</label>
              <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
              {{ csrf_field() }}
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Company Name</label>
              <input type="text" name="company_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Company Name">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Company Logo</label>
              <input type="file" name="logo" class="form-control" id="exampleInputEmail1" placeholder="">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">GST No.</label>
              <input type="text" name="gst_no" class="form-control" id="exampleInputEmail1" placeholder="Enter GST no">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            
          </div>
          <!-- /.card-body -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
