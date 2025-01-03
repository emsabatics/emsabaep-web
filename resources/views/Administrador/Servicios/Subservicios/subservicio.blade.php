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

<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('assets/administrador/css/ionicons/css/ionicons.min.css')}}">
<style>
  .spanlabel {
    padding-left: 22px;
    font-size: 12.5px;
    font-weight: bold;
  }
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  @foreach($subservicio as $b)
  <div class="col-sm-12">
    <h1>Servicio - {{$b['nameservicio']}}</h1>
  </div>
  @endforeach
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
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Lista de Subservicios</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarsubservice()"><i
                              class="far fa-plus-square mr-2"></i> Agregar Subservicio</button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="urlbacktoservice()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divSubService">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>{{$totalC}}</h3>
                            @if($totalC==0)
                            <p>Sin Subservicios</p>
                            @elseif($totalC==1)
                            <p>Subservicio</p>
                            @else
                            <p>Subservicios</p>
                            @endif
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      @foreach($subservicio as $sb)
                      @if(count($sb['subservicio'])>0)
                      @foreach($sb['subservicio'] as $b)
                      <div class="col-lg-6 col-12">
                        <div class="card card-info" id="CardCat-{{$loop->index}}">
                          <div class="card-header">
                            <h3 class="card-title" id="tituloSubservicionro{{$loop->index}}">{{$b['namesubservice']}}</h3>
                            <div class="card-tools">
                              @if($b['estadosubservice']=='0')
                              <span class="float-right badge bg-secondary" id="spanHeaderSubServiceStatus-{{$loop->index}}">Inactivo</span>
                              @elseif($b['estadosubservice']=='1')
                              <span class="float-right badge bg-secondary" id="spanHeaderSubServiceStatus-{{$loop->index}}">Activo</span>
                              @endif
                            </div>
                          </div>
                          <div class="card-body p-0 table-responsive">
                            <!-- /TABLE CONFIGURACION GENERAL -->
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Configuración</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Editar Subservicio</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="editarSubService({{$b['idsubservice']}}, {{$loop->index}})" class="btn btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Eliminar Subservicio</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="eliminarSubService({{$b['idsubservice']}})" class="btn btn-danger" title="Eliminar"><i class="fas fa-trash"></i></a>
                                    </div>
                                  </td>
                                </tr>
                                @if($b['tiposervice']=='informativo')
                                <tr>
                                  <td>Config. Detalle Informativo</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="viewInfoSubService({{$b['idsubservice']}})" class="btn btn-primary" title="Ajustes"><i class="fas fa-cogs"></i></a>
                                      <!--<a href="javascript:void(0)" class="btn btn-info" title="Registrar" onclick="goInfoSubService({{$b['idsubservice']}})"><i class="fas fa-file-signature"></i></a>-->
                                    </div>
                                  </td>
                                </tr>
                                @elseif($b['tiposervice']=='lista' || $b['tiposervice']=='lista_tramite')
                                <tr>
                                  <td>Config. Lista Desplegable</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="viewListItemsSubService({{$b['idsubservice']}})" class="btn btn-primary" title="Ajustes"><i class="fas fa-cogs"></i></a>
                                      <!--<a href="javascript:void(0)" class="btn btn-info" title="Registrar" onclick="goListItemsSubService({{$b['idsubservice']}})"><i class="fas fa-file-signature"></i></a>-->
                                    </div>
                                  </td>
                                </tr>
                                @elseif($b['tiposervice']=='archivo')
                                <tr>
                                  <td>Config. Texto y Archivo</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="viewFileSubService({{$b['idsubservice']}})" class="btn btn-primary" title="Ajustes"><i class="fas fa-cogs"></i></a>
                                      <!--<a href="javascript:void(0)" class="btn btn-info" title="Registrar" onclick="goFileSubService({{$b['idsubservice']}})"><i class="fas fa-file-signature"></i></a>-->
                                    </div>
                                  </td>
                                </tr>
                                @endif
                                <tr>
                                  <th></th>
                                  <th></th>
                                </tr>
                              </tbody>
                            </table>
                            <!-- /TABLE CONFIGURACION GENERAL -->
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      @endforeach
                      @endif
                      @endforeach
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
      </div>
  </div>
</section>

<!--MODAL UPDATE SUBSERVICE-->
<div class="modal fade" id="modalUpdateSubService" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Subservicio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                        <input type="hidden" name="indexselection" id="indexselection">
                        <input type="hidden" name="idservicio" id="idservicio">
                        <input type="hidden" name="idsubservicio" id="idsubservicio">
                        <label for="inputUpSubservicio">Nombre: <span class="spanlabel">270 caracteres
                            máximo</span></label>
                        <textarea name="inputUpSubservicio" class="form-control text-justify" id="inputUpSubservicio" cols="30" rows="5" placeholder="Categoría" 
                          autocomplete="off" maxlength="270"></textarea>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="customSwitchSubService" value="inactivo">
                          <label class="custom-control-label" for="customSwitchSubService"><span id="estadoSubServicio">Inactivo</span></label>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 updatesubservicio" onclick="actualizarSubservicio()">Guardar</button>
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

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/subservicios.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
  var idservicio= {{Illuminate\Support\Js::from($idservicio)}};
  //console.log(idservicio);
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 1500);
  });
</script>
@endsection