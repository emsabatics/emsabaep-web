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
                          <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarcate()"><i
                              class="far fa-plus-square mr-2"></i> Agregar Categoría</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divDocOperativo">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>{{$totalC}}</h3>
                            @if($totalC==0)
                            <p>Sin Categorías</p>
                            @elseif($totalC==1)
                            <p>Categoría</p>
                            @else
                            <p>Categorías</p>
                            @endif
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="h3ContSubCat">{{$totalSC}}</h3>
                            @if($totalSC==0)
                            <p>Sin Subcategorías</p>
                            @elseif($totalSC==1)
                            <p>Subcategoría</p>
                            @else
                            <p>Subcategorías</p>
                            @endif
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>{{$totalFC}}</h3>
                            @if($totalFC==0)
                            <p>Sin Documentos</p>
                            @elseif($totalFC==1)
                            <p>Documento</p>
                            @else
                            <p>Documentos</p>
                            @endif
                          </div>
                          <div class="icon">
                            <i class="ion ion-folder"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      @foreach($operativo as $b)
                      <div class="col-lg-6 col-12">
                        <div class="card card-info" id="CardCat-{{$loop->index}}">
                          <div class="card-header">
                            <h3 class="card-title" id="tituloCatnro{{$loop->index}}">{{$b['descripcioncat']}}</h3>
                            <div class="card-tools">
                              @if($b['estadocat']=='0')
                              <span class="float-right badge bg-secondary" id="spanHeaderCategoriaStatus-{{$loop->index}}">Inactivo</span>
                              @elseif($b['estadocat']=='1')
                              <span class="float-right badge bg-secondary" id="spanHeaderCategoriaStatus-{{$loop->index}}">Activo</span>
                              @endif
                            </div>
                            <!--<div class="card-tools">
                              <button type="button" class="btn btn-tool" title="Subcategoría" data-widget="chat-pane-toggle">
                                <i class="fas fa-plus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" title="Documentos" data-widget="chat-pane-toggle">
                                <i class="fas fa-folder-plus"></i>
                              </button>
                            </div>-->
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
                                  <td>Opciones Generales</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      <a href="javascript:void(0)" onclick="openmodalSubCat({{$b['idcat']}},{{$loop->index}})" class="btn btn-success" title="Subcategoría"><i class="fas fa-plus"></i></a>
                                      <a href="javascript:void(0)" onclick="editarCat({{$b['idcat']}}, {{$loop->index}})" class="btn btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <th></th>
                                  <th></th>
                                </tr>
                              </tbody>
                            </table>
                            <!-- /TABLE CONFIGURACION GENERAL -->

                            <!-- /TABLE SUBCATEGORIA -->
                            <table class="table" id="TableSubCat{{$loop->index}}">
                              <thead>
                                <tr>
                                  <th>SubCategoría</th>
                                  <th></th>
                                  <th><span class="float-right badge bg-danger" id="spanContSubCat{{$loop->index}}">{{count($b['subcategoria'])}}</span></th>
                                </tr>
                              </thead>
                              <tbody id="BodyTableSubCat{{$loop->index}}">
                                @if(count($b['subcategoria'])>0)
                                @include('Administrador.Documentos.operativo.tabla', ['subcategoria' => $b['subcategoria']])
                                @else
                                  <tr id="nodatacat{{$b['idcat']}}">
                                    <td style="text-align: center;" colspan="3">Sin Datos</td>
                                  </tr>
                                @endif
                              </tbody>
                            </table>
                            <!-- /TABLE SUBCATEGORIA -->
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      @endforeach
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
      </div>
  </div>
</section>

<!--MODAL AGG CATEGORIA DOCUMENTACION OPERATIVA-->
<div class="modal fade" id="modalAggCatDocOp" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Categoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <label for="inputCategoria">Categoría: <span class="spanlabel">270 caracteres
                          máximo</span></label>
                        <textarea name="inputCategoria" class="form-control text-justify" id="inputCategoria" cols="30" rows="5" placeholder="Categoría" 
                        autocomplete="off" maxlength="270"></textarea>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 savecatdo" onclick="guardarCategoriaDocOp()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL UPDATE CATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalUpdateCatDocOp" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Categoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                        <input type="hidden" name="indexselection" id="indexselection">
                        <input type="hidden" name="idgetcategoria" id="idgetcategoria">
                        <label for="inputUpCategoria">Categoría: <span class="spanlabel">270 caracteres
                            máximo</span></label>
                        <textarea name="inputUpCategoria" class="form-control text-justify" id="inputUpCategoria" cols="30" rows="5" placeholder="Categoría" 
                          autocomplete="off" maxlength="270"></textarea>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="customSwitchCat" value="inactivo">
                          <label class="custom-control-label" for="customSwitchCat"><span id="estadoCategoria">Inactivo</span></label>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 updatecatdo" onclick="actualizarCategoriaDocOp()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL AGG SUBCATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalAggSubCatDocOp" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Subcategoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3 noevent">
                        <input type="hidden" name="indexselsubcat" id="indexselsubcat">
                        <input type="hidden" name="idcategoria" id="idcategoria">
                        <label for="inputviewcategoria">Categoría: </label>
                        <textarea name="inputviewcategoria" class="form-control text-justify" id="inputviewcategoria" cols="30" rows="5" placeholder="Categoría" 
                        autocomplete="off" maxlength="270"></textarea>
                      </div>
                      <div class="form-group mb-3">
                        <label for="inputSubcategoria">Subcategoría: <span class="spanlabel">270 caracteres
                          máximo</span></label>
                        <textarea name="inputSubcategoria" class="form-control text-justify" id="inputSubcategoria" cols="30" rows="5" placeholder="Subcategoría" 
                        autocomplete="off" maxlength="270"></textarea>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 savesubcatbv" onclick="guardarSubCategoriaDocOp()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL EDIT SUBCATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalUpdateSubCatDocOp" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Subcategoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group mb-3">
                        <input type="hidden" name="indexselsubcattable" id="indexselsubcattable">
                        <input type="hidden" name="idsubcategoria" id="idsubcategoria">
                        <label for="inputviewsubcategoria">Subcategoría: <span class="spanlabel">270 caracteres
                          máximo</span></label>
                        <textarea name="inputviewsubcategoria" class="form-control text-justify" id="inputviewsubcategoria" cols="30" rows="5" placeholder="Categoría" 
                        autocomplete="off" maxlength="270"></textarea>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 updatesubcatdo" onclick="actualizarSubCategoriaDocOp()">Guardar</button>
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
<script src="{{asset('assets/administrador/js/docoperativo.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  const nameInterfaz = "Biblioteca Virtual";
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 1500);
  });
</script>
@endsection