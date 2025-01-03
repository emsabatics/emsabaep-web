@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/estructura-jerarquica.png')}}">
@endsection

@section('title-page')
Admin | Departamentos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

<style>
  #myProgress {
    width: 100%;
    background-color: #ddd;
  }
  
  #myBar {
    width: 1%;
    height: 30px;
    background-color: #04AA6D;
  }
  </style>
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
    <h1>Departamentos de la Institución</h1>
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
                        <!--<h3 class="card-title p-2"><i class="fas fa-pencil-alt mr-3"></i> Registrar Departamentos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-secondary btn-block" onclick="backInterfaceDep()"><i
                            class="fas fa-arrow-circle-left mr-2"></i> Regresar</button>
                            <button type="button" class="btn btn-primary btn-block" onclick="openModalAggDep()"><i
                            class="far fa-plus-square mr-2"></i> Agregar</button>
                        </div>-->
                        <div class="cardsRowTitle">
                          <div class="cardsection">
                            <h3 class="card-title p-2"><i class="fas fa-pencil-alt mr-3"></i> Registrar Departamentos</h3>
                          </div>
                          <div class="cardsection">
                            <button type="button" class="btn btn-secondary btn-block" onclick="backInterfaceDep()"><i
                              class="fas fa-arrow-circle-left mr-2"></i> Regresar</button>
                          </div>
                          <div class="cardsection">
                            <button type="button" class="btn btn-primary btn-block" onclick="openModalAggDep()"><i
                              class="far fa-plus-square mr-2"></i> Agregar</button>
                          </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-4" id="divtablaDep">
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<!--MODAL AGG DEPARTAMENTO-->
<div class="modal fade" id="modalAggDep" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Departamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form id="formDept" action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                          <label>Tipo de Registro:</label>
                          <select class="form-control select2" id="selDepartamento" onchange="getval(this, 1);">
                          <optgroup label="Seleccione una Opción">
                              <option value="0">-Seleccione una Opción-</option>
                              <option value="gerencia">Gerencia</option>
                              <option value="direccion">Dirección</option>
                              <option value="coordinacion">Coordinación</option>
                          </optgroup>
                          </select>
                      </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                          <label for="inputNombreDep">Nombre:</label>
                          <input type="text" id="inputNombreDep" name="inputNombreDep" class="form-control" placeholder="Departamento" autocomplete="off">
                      </div>
                    </div>
                </div>
                <div class="row" id="rowDependencia">
                  <div class="col-lg-12">
                    <div class="form-group mb-3">
                      <label>Dependencia:</label>
                      <select class="form-control" id="selDependencia" onchange="getvalDep(this);">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row mb-3" id="divupimggerdir">
                  <div class="col-md-12">
                      <label>Seleccionar Imagen <span class="spanlabel">1 imagen máximo</span></label>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                      <div class="container">
                          <input type="file" name="file[]" id="file" accept="image/*" onchange="preview()" multiple>
                          <label for="file">
                          <i class="fas fa-cloud-upload-alt mr-2"></i>&nbsp; Elija una imagen
                          </label>
                          <p id="num-of-files">- Ningún archivo seleccionado -</p>
                      </div>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div id="images"></div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 btn-add-dep" onclick="guardarRegistroDep()">Guardar</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL EDIT DEPARTAMENTO-->
<div class="modal fade" id="modalEditDep" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Departamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form id="formeditDept" action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3 form-sel-dep">
                        <input type="hidden" name="iddepartamentoedit" id="iddepartamentoedit">
                        <input type="hidden" name="tipo_seleccion_edit" id="tipo_seleccion_edit">
                          <label>Tipo de Registro:</label>
                          <select class="form-control select2" id="selDepartamentoEdit" onchange="getvalEdit(this, 1);">
                          <optgroup label="Seleccione una Opción">
                              <option value="0">-Seleccione una Opción-</option>
                              <option value="gerencia">Gerencia</option>
                              <option value="direccion">Dirección</option>
                              <option value="coordinacion">Coordinación</option>
                          </optgroup>
                          </select>
                          <span class="spanNotiGer" id="spanInfoModal">No se puede cambiar el tipo de Registro a <span id="nameSelSpanModal">Gerencia<span></span>
                      </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                          <label for="inputNombreEditDep">Nombre:</label>
                          <input type="text" id="inputNombreEditDep" name="inputNombreEditDep" class="form-control" placeholder="Departamento" autocomplete="off">
                      </div>
                    </div>
                </div>
                <div class="row" id='divDependencia'>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>Dependencia:</label>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-row align-items-center">
                      <div class="col-auto col-md-9">
                        <label class="sr-only" for="inputNameDependencia">Name</label>
                        <input type="text" class="form-control mb-2" id="inputNameDependencia" placeholder="Dependencia" readonly>
                        <input type="hidden" name="inputIdDependencia" id='inputIdDependencia'>
                      </div>
                      <div class="col-auto col-md-3">
                        <button type="submit" class="btn btn-primary mb-2" onclick="changeDependencia()">Editar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" id="rowDependenciaEdit">
                  <div class="col-lg-12">
                    <div class="form-row align-items-center">
                      <div class="col-auto col-md-9">
                        <div class="form-group mb-3">
                          <label>Dependencia:</label>
                          <select class="form-control select2" id="selDependenciaEdit" onchange="getvalDepEdit(this);">
                          </select>
                        </div>
                      </div>
                      <div class="col-auto col-md-3">
                        <button type="submit" class="btn btn-danger mt-3" onclick="cancelDependencia()">Cancelar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" id="div_progressbar">
                  <div class="col-lg-12">
                    <div id="myProgress">
                      <div id="myBar"></div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row mb-3" id="divImgToGerDir">
                  <div class="col-md-12" id="divcontainerUp">
                    <div class="form-group">
                      <div class="container">
                        <input type="file" name="fileedit[]" id="fileedit" accept="image/*"
                          onchange="previewpicsEdit()" multiple>
                        <label for="fileedit">
                          <i class="fe fe-upload-cloud fe-24"></i>&nbsp; Elija una imagen
                        </label>
                        <p id="num-of-files-edit">- Ningún archivo seleccionado -</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <span style="font-size: 17px;font-weight: bolder;padding: 4px;">Imagen:</span>
                    <div id="rowPicsInd"></div>
                    <div id="images-edit"></div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 btn-edit-dep" onclick="guardarRegistroEditDep()">Actualizar</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>


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
<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/departamento.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    var gerencia= {{Illuminate\Support\JS::from($gerencia)}};
    var direccion= {{Illuminate\Support\JS::from($direccion)}};
    var coordinacion= {{Illuminate\Support\JS::from($coordinacion)}};

    var isImage= false;

    var elementRowDep= document.getElementById('rowDependencia');
    elementRowDep.style.display='none';
    $('#selDependencia').html("");

    var elementSpanModal= document.getElementById('spanInfoModal');
    elementSpanModal.style.display='none';

    document.getElementById('rowDependenciaEdit').style.display='none';

    var divNameDep= document.getElementById('divDependencia');
    divNameDep.style.display='none';

    var divProgess = document.getElementById('div_progressbar');
    divProgess.style.display= 'none';

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "1800",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    $('#modalCargando').modal('show');
    setTimeout(() => {
      cargar_departamento(gerencia, direccion, coordinacion);
    }, 500);

    var isActiveDep= false;
    var tipoChangeDep='';

    var intervalP = 0;
    function move() {
      if (intervalP == 0) {
        intervalP = 1;
        var elem = document.getElementById("myBar");
        var width = 1;
        var id = setInterval(frame, 10);
        function frame() {
          if (width >= 100) {
            clearInterval(id);
            intervalP = 0;
          } else {
            width++;
            elem.style.width = width + "%";
          }
        }
      }
    }
</script>
@endsection