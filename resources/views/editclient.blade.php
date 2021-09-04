<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Edit Client</title>

  @include('layouts.css-link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Edit Client"])

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                  @if (session('msg') && session('status'))
                  <div id="alert1" class="alert alert-{{ session('status') }}" role="alert">
                      {{ session('msg') }}
                  </div>
              @endif
              <form id="quickForm" action="{{ url('/editClient') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" value="{{ $client->name }}" placeholder="Enter Name">
                    <input type="hidden" name="client_id" value="{{ Request::segment(2) }}">
                    {{ csrf_field() }}
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $client->email }}" id="exampleInputEmail1" placeholder="Enter Email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Company Name</label>
                    <input type="text" name="company_name" value="{{ $client->company_name }}" class="form-control" id="exampleInputEmail1" placeholder="Enter Company Name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Company Logo</label>
                    <input type="file" name="logo" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">GST No.</label>
                    <input type="text" name="gst_no" class="form-control" value="{{ $client->gst_no }}" id="exampleInputEmail1" placeholder="Enter GST no">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Website Link .</label>
                    <input type="text" name="website" class="form-control" id="exampleInputEmail1" placeholder="Enter Website Link" value="{{ $client->website }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mobile No.</label>
                    <input type="text" name="mobile_no" class="form-control" id="exampleInputEmail1" placeholder="Enter Mobile No" value="{{ $client->mobile_no }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Address</label>
                    <textarea name="address" placeholder="Enter Address......" id="" class="form-control" cols="20" rows="10">{{ $client->address }}</textarea>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                  
                </div>
                <!-- /.card-body -->
              </form>
                </div>
                <div class="col-md-2"></div>
            </div>
            
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
<script>
  $(document).ready(()=>{
    setTimeout(() => {
      $('#alert1').hide();
    }, 2000);
  });
</script>
</body>
</html>
