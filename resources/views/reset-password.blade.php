<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software - Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('public/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ url('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('public/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{ url('/') }}" class="h1"><b>Inventry</b> Software</a>
    </div>
    <div class="card-body">
        <div id="alert">
            
        </div>
        <h5 align="center"><b> Forgot  Password</b></h5>
      <form action="{{ url('/sendForgotEmail') }}" method="post" class="database_operation">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email">
          {{ csrf_field() }}
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Send Email</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
      <p align="center">-- OR -- </p>
      

      <p class="mb-1">
        <a href="{{ url('/') }}" class="btn btn-primary btn-block">Login</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ url('public/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/dist/js/adminlte.min.js') }}"></script>
<script>
    $(document).ready(()=>{
        $('.database_operation').submit(()=>{
            var data = $('.database_operation').serialize();
            var url = $('.database_operation').attr('action');
            $.post(url,data,(data,status)=>{
                st = `<div class="alert alert-success" role="alert">
                        ${data.msg}
                    </div>`;
                if(data.status){
                    setTimeout(() => {
                            window.location.href = data.url;
                    }, 2000);
                }else{
                    st = `<div class="alert alert-danger" role="alert">
                        ${data.msg}
                    </div>`;
                }
                $('#alert').html(st);
                setTimeout(() => {
                    $('.alert').hide();
                }, 2000);
            });
            return false;
        });
    });
</script>
</body>
</html>
