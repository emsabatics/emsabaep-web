@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-operativa.png')}}">
@endsection

@section('title-page')
Admin | Doc Operativa {{getNameInstitucion()}}
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
    <h1>Doc. Operativa</h1>
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
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Documentación Operativa</h3>
                      <div class="card-tools" id="card-tools">
                          <button type="button" class="btn btn-primary btn-block" onclick="urlregistrardocoperativo()"><i
                              class="far fa-plus-square mr-2"></i> Agregar</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divDocOper">
                    <table class="table datatables" id="tablaDocOper">
                      <thead class="thead-dark">
                        <tr style="pointer-events:none;">
                          <th>N°</th>
                          <th>Año</th>
                          <th>Descripción</th>
                          <th>Estado</th>
                          <th>Opciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($operativo as $item)
                        <tr id="Tr{{$loop->index}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$item->anio}}</td>
                          <td>
                            @if($item->id_mes!=null || $item->id_mes!='')
                            @foreach ($mes as $m)
                              @if($m->id==$item->id_mes)
                                <strong>{{$m->mes}}</strong><br/>
                                {{$item->titulo}}
                              @endif
                            @endforeach
                            @else
                            {{$item->titulo}}
                            @endif
                          </td>
                          <td>
                            @if ($item->estado=='0')
                            <span class="badge badge-secondary">No Visible</span>
                            @else
                            <span class="badge badge-success">Visible</span> 
                            @endif
                          </td>
                          <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="viewopenDocOper({{$item->id}})">
                              <i class="fas fa-folder mr-2"></i>
                              Ver
                            </a>
                            <a class="btn btn-info btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="interfaceupdateDocOper({{$item->id}})">
                              <i class="far fa-edit mr-2"></i>
                              Actualizar
                            </a>
                            @if ($item->estado=='1')
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="inactivarDocOper({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye-slash mr-2"></i>
                              Inactivar
                            </a>
                            @else
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="activarDocOper({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye mr-2"></i>
                              Activar
                            </a>
                            @endif
                            <a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadDocOper({{$item->id}})">
                              <i class="fas fa-download mr-2"></i>
                              Descargar Documento
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
<script src="{{asset('assets/administrador/js/docoperativo.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
  
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      showInfoOperativo();
    }, 1500);
  });
</script>
@endsection