@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | LOTAIP {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/collapse.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">

@endsection

@section('navbar')
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
        <a href="#" class="dropdown-item">
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
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>LOTAIP</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> LOTAIP</h3>
                      <div class="card-tools" id="card-tools">
                          <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarlotaip()"><i
                              class="far fa-plus-square mr-2"></i> Agregar</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divLotaip">
                    <div class="row">
                      <div class="col-5 col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                          @foreach ($year as $y)
                              @foreach ($orderby as $l)
                                  @if ($y->id==$l->id_anio)
                                  @if ($loop->index=='0')
                                  <a class="nav-link active" id="vert-tabs-{{strtolower($y->nombre)}}-tab" data-toggle="pill" href="#vert-tabs-{{strtolower($y->nombre)}}" role="tab" aria-controls="vert-tabs-{{strtolower($y->nombre)}}" aria-selected="true">{{$y->nombre}}</a> 
                                  @else
                                  <a class="nav-link" id="vert-tabs-{{strtolower($y->nombre)}}-tab" data-toggle="pill" href="#vert-tabs-{{strtolower($y->nombre)}}" role="tab" aria-controls="vert-tabs-{{strtolower($y->nombre)}}" aria-selected="true">{{$y->nombre}}</a>
                                  @endif
                                  @endif
                              @endforeach
                          @endforeach
                        </div>
                      </div>
                      <div class="col-7 col-sm-9">
                        <div class="tab-content" id="vert-tabs-tabContent">
                          @foreach ($year as $y)
                              @foreach ($orderby as $l)
                                  @if ($y->id==$l->id_anio)
                                    @if ($loop->index=='0')
                                    <div class="tab-pane text-left fade show active" id="vert-tabs-{{strtolower($y->nombre)}}" role="tabpanel" aria-labelledby="vert-tabs-{{strtolower($y->nombre)}}-tab">
                                      <div id="accordion" class="myaccordion">
                                        <div class="card">
                                          <div class="card-header" id="headingOne">
                                            <h2 class="mb-0">
                                              <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Undergraduate Studies
                                                <span class="fa-stack fa-sm">
                                                  <i class="fas fa-circle fa-stack-2x"></i>
                                                  <i class="fas fa-minus fa-stack-1x fa-inverse"></i>
                                                </span>
                                              </button>
                                            </h2>
                                          </div>
                                          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                              <ul>
                                                <li>Computer Science</li>
                                                <li>Economics</li>
                                                <li>Sociology</li>
                                                <li>Nursing</li>
                                                <li>English</li>
                                              </ul>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="card">
                                          <div class="card-header" id="headingTwo">
                                            <h2 class="mb-0">
                                              <button class="d-flex align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Postgraduate Studies
                                                <span class="fa-stack fa-2x">
                                                  <i class="fas fa-circle fa-stack-2x"></i>
                                                  <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                              </button>
                                            </h2>
                                          </div>
                                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                              <ul>
                                                <li>Informatics</li>
                                                <li>Mathematics</li>
                                                <li>Greek</li>
                                                <li>Biostatistics</li>
                                                <li>English</li>
                                                <li>Nursing</li>
                                              </ul>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="card">
                                          <div class="card-header" id="headingThree">
                                            <h2 class="mb-0">
                                              <button class="d-flex align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Research
                                                <span class="fa-stack fa-2x">
                                                  <i class="fas fa-circle fa-stack-2x"></i>
                                                  <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                              </button>
                                            </h2>
                                          </div>
                                          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                            <div class="card-body">
                                              <ul>
                                                <li>Astrophysics</li>
                                                <li>Informatics</li>
                                                <li>Criminology</li>
                                                <li>Economics</li>
                                              </ul>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    @else
                                    <div class="tab-pane text-left fade" id="vert-tabs-{{strtolower($y->nombre)}}" role="tabpanel" aria-labelledby="vert-tabs-{{strtolower($y->nombre)}}-tab">
                                      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.
                                    </div>
                                    @endif
                                  @endif
                              @endforeach
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
      </div>
  </div>
</section>

<!-- Fullscreen modal -->
<div class="modal fade modal-full" id="modalCargando" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/administrador/img/gif/load.gif')}}" alt="">
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/lotaip.js')}}"></script>

<script>
  $("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
    $(e.target)
      .prev()
      .find("i:last-child")
      .toggleClass("fa-minus fa-plus");
  });
  $(document).ready(function () {
    //$('#modalCargando').modal('show');
    setTimeout(() => {
      //showInfoLotaip();
    }, 1500);
  });
</script>
@endsection