<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Edit Brand</title>

  @include('layouts.css-link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.navbar')

  @include('layouts.sidebar',["title"=>"Edit Brand"])

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
                    <form action="{{ url('/updatebrand') }}" method="POST" class="database_operation">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Brand Name</label>
                                <input type="text" name="brand_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Brand Name" value="{{ $brand->brand_name }}">
                                <input type="hidden" name="brand_id" value="{{ $brand->id }}">
                                {{ csrf_field() }}
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
