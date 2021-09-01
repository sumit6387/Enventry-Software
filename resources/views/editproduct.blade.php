<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventry Software | Edit Product</title>

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

  @include('layouts.sidebar',["title"=>"Edit Product"])

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
                <form id="quickForm" action="{{ url('/updateproduct') }}" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Product Name" value="{{ $product->name }}">
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        {{ csrf_field() }}
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Brand Name</label>
                        <select class="form-control select2" onchange="showCategory(this.value)" name="brand" style="width: 100%;">
                          <option value="">Select Brand</option>
                          @foreach ($brands as $brand)
                          <option @if ($brand->id == $product->brand)
                                selected
                          @endif value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Category</label>
                        <select class="form-control select2" name="category" id="category" style="width: 100%;">
                                <option  value="">Select Category</option>
                                @foreach ($category as $item)
                                    <option @if ($item->id == $product->category)
                                        selected
                                    @endif value="{{ $item->id }}">{{ $item->category }}</option>
                                @endforeach   
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="price">Price </label>
                        <input type="text" name="price" class="form-control" id="exampleInputEmail1" placeholder="Enter Price" value="{{ $product->price }}">
                      </div>
                      
                      <div class="form-group">
                        <label for="quantity">Quantity </label>
                        <input type="text" name="quantity" class="form-control" id="exampleInputEmail1" placeholder="Enter Quantity" value="{{ $product->quantity }}">
                      </div>
                      <div class="form-group">
                        <label for="gst">GST (%) </label>
                        <input type="text" name="gst" class="form-control" id="exampleInputEmail1" placeholder="Enter GST in %" value="{{ $product->gst }}">
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
