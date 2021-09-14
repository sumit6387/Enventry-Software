<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      @php
          $user = \App\Models\User::where('email',session('email'))->get()->first();
      @endphp
      <img src="{{ $user->logo }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $user->company_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('public/images/user.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ session('name') }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ url('/dashboard') }}" class="nav-link @if (Request::segment(1)=='dashboard')
            active
          @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            
            <a href="{{ url('/brands') }}" class="nav-link @if (Request::segment(1)=='brands')
              active
            @endif">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Brands
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/category') }}" class="nav-link @if (Request::segment(1)=='category')
            active
          @endif">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ url('/products') }}" class="nav-link @if (Request::segment(1)=='products')
            active
          @endif">
              <i class="nav-icon far fa-image"></i>
              <p>
                Products
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ url('/gst-on-bill') }}" class="nav-link @if (Request::segment(1)=='gst-on-bill')
            active
          @endif">
              <i class="nav-icon far fa-image"></i> 
              <p>
                GST On Bill
              </p>
            </a>
          </li> --}}
          @if(session('role') == "Admin")
          <li class="nav-item">
            <a href="{{ url('/clients') }}" class="nav-link @if (Request::segment(1)=='clients')
            active
          @endif">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Add Clients
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ url('/customers') }}" class="nav-link @if (Request::segment(1)=='customers')
            active
          @endif">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Customers
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/order') }}" class="nav-link @if (Request::segment(1)=='order')
            active
          @endif">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Order
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ url('/orderHistory') }}" class="nav-link @if (Request::segment(1)=='orderHistory')
            active
          @endif">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Order History
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->