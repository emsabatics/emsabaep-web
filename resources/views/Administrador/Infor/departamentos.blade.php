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

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
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

<!--<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                  <i class="fas fa-sitemap mr-3"></i>
                  Departamentos de la Institución
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-block" onclick="openModalSM()"><i
                        class="far fa-plus-square mr-2"></i> Registrar</button>
                </div>
            </div>
            <div class="card-body">
                <div class="buttonStructure">
                    <input type="hidden" id="idEstructura" name="idEstructura">
                    <button id="edit" class="btn btn-primary" onclick="edit()" type="button"> <i class="fa fa-edit mr-2"></i> Editar</button>
                    <button id="save" class="btn btn-primary" onclick="save()" type="button"><i class="fa fa-save mr-2"></i>Guardar</button>
                </div>
                
                <div class="mt-2" id="divEstructura"></div>
            </div>
        </div>
    </div>
</div>-->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="cardsRowTitle">
                          <div class="cardsection">
                            <h3 class="card-title p-2"><i class="fas fa-sitemap mr-3"></i> Departamentos de la Institución</h3>
                          </div>
                          <div class="cardsection">
                            <button type="button" class="btn btn-primary btn-block" onclick="openUrlInfoDep()"><i
                              class="far fa-plus-square mr-2"></i> Registrar</button>
                          </div>
                          <div class="cardsection">
                            <button type="button" class="btn btn-warning btn-block" onclick="addDepartamento()"><i
                              class="fa fa-cog mr-2"></i> Ajustes</button>
                          </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-4">
                      <table class="table datatables" id="tableDepList">
                        <thead class="thead-dark">
                          <tr style="pointer-events:none;">
                            <th>N°</th>
                            <th>Responsable</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($resultado as $item)
                              <tr id='Tr{{$loop->index}}'>
                                <td style="text-align: center;">{{$loop->iteration}}</td>
                                <td>{{$item['responsable']}}</td>
                                <td>{{$item['email']}}</td>
                                <td>
                                  <ul>
                                    <li>{{$item['telefono']}}</li>
                                    <li>Ext: {{$item['extension']}}</li>
                                  </ul>
                                </td>
                                <td>{{$item['nombre_dep']}}</td>
                                @if($item['estado']=='1')
                                  <td>Visible</td>
                                @else
                                  <td>No Visible</td>
                                @endif
                                <td style="display: flex;">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" title="Editar" onclick="editarInforDep({{$item['id']}})">
                                      <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    @if($item['estado']=='1')
                                    <button type="button" class="btn btn-secondary btn-sm mr-3" title="Inactivar" onclick="removerInforDep({{$item['id']}}, {{$loop->index}})">
                                      <i class="fas fa-eye-slash"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-info btn-sm mr-3" title="Activar" onclick="activarInforDep({{$item['id']}}, {{$loop->index}})">
                                      <i class="fas fa-eye"></i>
                                    </button>
                                    @endif
                                </td>
                              </tr>
                          @endforeach
                        </tbody>
                      </table>
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
<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/departamento.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    //var resultadoArray = {Illuminate\Support\Js::from($resultado)};
    $('#modalCargando').modal('show');
    setTimeout(() => {
      //cargar_estructura(inforEstructura);
      $("#tableDepList")
        .removeAttr("width")
        .DataTable({
          autoWidth: true,
          lengthMenu: [
            [8, 16, 32, 64, -1],
            [8, 16, 32, 64, "Todo"],
          ],
          //para cambiar el lenguaje a español
          language: {
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:
              "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
              sFirst: "Primero",
              sLast: "Último",
              sNext: "Siguiente",
              sPrevious: "Anterior",
            },
            sProcessing: "Procesando...",
          },
          columnDefs: [
            { width: 40, targets: 0, className: "text-center" },
            { className: "dt-head-center", targets: [1, 2, 3, 4, 5, 6] },
          ],
      });

      $('#modalCargando').modal('hide');
    }, 1000);
</script>
@endsection