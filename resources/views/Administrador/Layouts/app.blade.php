<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate">
  @yield('icon-app')
  <title>@yield('title-page', 'Admin | Inicio')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free/css/all.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/administrador/css/adminlte.min.css')}}">

  @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('home')}}" class="nav-link">Inicio</a>
      </li>
    </ul>
  
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="num-noti-span"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="contain-noti">
  
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Ajustes">
          <i class="fas fa-cogs"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Ajustes</span>
          <div class="dropdown-divider"></div>
          <a href="{{route('perfil')}}" class="dropdown-item">
          <i class="fas fa-user-cog mr-2"></i> Perfil
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{route('logout')}}" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
          </a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('home')}}" class="brand-link">
      <img src="{{asset('assets/administrador/img/logoGota.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin {{getNameInstitucion()}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('assets/administrador/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Session::get('nombre_usuario')}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">

        @php
            $porModulo = $permisosMenu->groupBy('idmodulo');
        @endphp
        
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            @foreach($porModulo as $idmodulo => $items)
              @php
                  $first = $items->first();
                  // submodulos reales (idsubmodulo != null)
                  $submodulos = $items
                    ->filter(function($i) {
                        return !is_null($i->idsubmodulo) && !empty($i->ruta_submodulo);
                    })
                    ->unique(function($item) {
                        return $item->idsubmodulo;
                    });
              @endphp

              <li class="nav-item">
                @if ($first->mod_visible === 1 || $first->mod_visible === '1')
                @if($submodulos->isEmpty())
                <a href="{{ url(isset($first->ruta_modulo) ? $first->ruta_modulo : '#') }}" 
                    class="nav-link {{ isset($first->ruta_modulo) ? setActive($first->ruta_modulo) : '' }}">
                  <i class="nav-icon {{$first->icono}}"></i>
                    <p>{{ $first->modulo }}</p>
                </a>
                @else
                <a href="#" class="nav-link">
                    <i class="nav-icon {{$first->icono}}"></i>
                    <p>
                        {{ $first->modulo }}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                @endif
                  

                @if($submodulos->isNotEmpty())
                <ul class="nav nav-treeview">
                  @foreach($submodulos as $sub)
                   <li class="nav-item">
                        <a href="{{ url($sub->ruta_submodulo) }}" class="nav-link {{ setActive($sub->ruta_submodulo) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ $sub->submodulo }}</p>
                        </a>
                    </li>
                  @endforeach
                </ul>
                @endif
                @endif
              </li>
            @endforeach

            @if (Session::get('tipo_usuario')=='administrador')
              @include('Administrador.Layouts.optionsonlyadmin');
              <!--resources/views/some/directory/structure/foo.blade.php-->
            @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('container-header')
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('contenido-body')
        <!--<div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Title</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                Start creating your amazing application!
              </div>
              <div class="card-footer">
                Footer
              </div>
            </div>
          </div>
        </div>-->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    @php
    use Carbon\Carbon;
    @endphp
    <strong>Copyright &copy; {{ Carbon::now()->year }} <a href="{{url('home')}}">EMSABA</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/administrador/plugins/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="../../dist/js/demo.js"></script>-->
<script src="{{asset('assets/administrador/js/getnotificacion.js')}}"></script>
<script>
  window.Permisos = @json($permisosJS);

  $(document).ready(function(){
    setTimeout(() => {
      getCountNoti();
    //getNotificaciones();
    }, 500);
  });
</script>
@yield('js')
</body>
</html>