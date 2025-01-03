@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/servicio-agua.png')}}">
@endsection

@section('title-page')
Admin | Servicios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Subservicios</h1>
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
                      @foreach ($subservicio as $it)
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Subservicio - {{$it->nombre}}</h3>
                      @endforeach
                     
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlregistrardetaillist({{$idsubservice}})"><i
                          class="far fa-plus-square mr-2"></i> Agregar</button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="urlbackservicio()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                      </div>
                      
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divSubserviceDetailList">
                    <table class="table datatables" id="tablaSubServiceDetailList">
                      <thead class="thead-dark">
                        <tr style="pointer-events:none;">
                          <th>N°</th>
                          <th>Subservicio</th>
                          <th>Descripción</th>
                          <th>Estado</th>
                          <th>Opciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tabsubinfo as $item)
                        <tr id="TrSsList{{$loop->index}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$item->subservicio}}</td>
                          <td>
                            {{$item->titulo}}
                          </td>
                          <td>
                            @if ($item->estado=='0')
                            <span class="badge badge-secondary">No Visible</span>
                            @else
                            <span class="badge badge-success">Visible</span> 
                            @endif
                          </td>
                          <td class="project-actions text-right">
                            <a class="btn btn-info btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="interfaceupdateSubservicedetaillist({{$item->id}})">
                              <i class="far fa-edit mr-2"></i>
                              Actualizar
                            </a>
                            @if ($item->estado=='1')
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="inactivarSubservicedetaillist({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye-slash mr-2"></i>
                              Inactivar
                            </a>
                            @else
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="activarSubservicedetaillist({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye mr-2"></i>
                              Activar
                            </a>
                            @endif
                            <a class="btn btn-danger btn-sm mt-2 mr-3" title="Eliminar" onclick="deleteSubservicedetaillist({{$item->id}})">
                              <i class="fas fa-trash mr-2"></i>
                              Eliminar
                            </a>
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

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/subservicio_lista.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
  var idservice= {{Illuminate\Support\Js::from($idservicio)}};
  
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      showDetailList();
    }, 1500);
  });
</script>
@endsection