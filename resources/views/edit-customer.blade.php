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
                <form action="{{ url('/updateCustomer') }}" method="POST" class="database_operation">
                    <div class="form-group">
                    <label for="name"> Name : <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                    </div>
                    <div class="form-group">
                    <label for="email"> Email : </label>
                    <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                    </div>
                    <div class="form-group">
                    <label for="mobile_no"> Mobile No : </label>
                    <input type="text" name="mobile_no" class="form-control" value="{{ $customer->mobile_no }}">
                    {{ csrf_field() }}
                    </div>
                    <div class="form-group">
                    <label for="address"> Address : </label>
                    <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
                    </div>
                    <div class="form-group">
                    <label for="pincode"> Pin Code : </label>
                    <input type="text" name="pincode" class="form-control" value="{{ $customer->pincode }}">
                    </div>
                    <div class="form-group">
                    <button class="btn btn-primary">Update</button>
                    </div>
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
