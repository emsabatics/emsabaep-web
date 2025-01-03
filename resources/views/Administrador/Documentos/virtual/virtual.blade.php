@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Bib. Virtual {{getNameInstitucion()}}
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
    <h1>Biblioteca Virtual</h1>
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
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Biblioteca Virtual</h3>
                      <div class="card-tools" id="card-tools">
                          <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarcate()"><i
                              class="far fa-plus-square mr-2"></i> Agregar Categoría</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divDocVirtual">
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
                      @foreach($biblioteca as $b)
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
                                      <a href="javascript:void(0)" onclick="openmodalSubCat({{$b['idcat']}},{{$loop->index}})" class="btn btn-info" title="Subcategoría"><i class="fas fa-plus"></i></a>
                                      <a href="javascript:void(0)" onclick="registerFileCat({{$b['idcat']}})" class="btn btn-success" title="Documentos"><i class="fas fa-folder-plus"></i></a>
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
                              <tbody>
                                @if(count($b['subcategoria'])>0)
                                @foreach($b['subcategoria'] as $sc)
                                <tr id="TrSub{{$sc['idsubcat']}}Cat{{$loop->index}}">
                                  <td>{{$sc['descripcionsubcat']}}</td>
                                  <td>
                                    @if(count($sc['archivossubcat'])==0)
                                    Sin Archivos
                                    @elseif(count($sc['archivossubcat'])==1)
                                    {{count($sc['archivossubcat'])}} Archivo
                                    @elseif(count($sc['archivossubcat'])>1)
                                    {{count($sc['archivossubcat'])}} Archivos
                                    @endif
                                  </td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      @if($sc['estadosubcat']=='1')
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar Subcategoría" onclick="inactivarSubCat({{$sc['idsubcat']}}, {{$b['idcat']}}, {{$loop->index}})"><i class="fas fa-eye-slash"></i></a>
                                      @else
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Activar Subcategoría" onclick="activarSubCat({{$sc['idsubcat']}}, {{$b['idcat']}}, {{$loop->index}})"><i class="fas fa-eye"></i></a>
                                      @endif
                                      <a href="javascript:void(0)" onclick="registerFileSubCat({{$b['idcat']}}, {{$sc['idsubcat']}})" class="btn btn-success" title="Agregar Documentos"><i class="fas fa-folder-plus"></i></a>
                                      <a href="javascript:void(0)" class="btn btn-primary" title="Editar Subcategoría" onclick="editSubCat({{$sc['idsubcat']}}, {{$loop->index}})"><i class="fas fa-edit"></i></a>
                                      <a href="javascript:void(0)" class="btn btn-info" title="Editar Documentos SubCategoría" onclick="viewListFilesSubCat({{$b['idcat']}}, {{$sc['idsubcat']}})"><i class="fas fa-file-signature"></i></a>
                                    </div>
                                  </td>
                                </tr>
                                @endforeach
                                @else
                                  <tr id="nodatacat{{$b['idcat']}}">
                                    <td style="text-align: center;" colspan="2">Sin Datos</td>
                                  </tr>
                                @endif
                              </tbody>
                            </table>
                            <!-- /TABLE SUBCATEGORIA -->
                            
                            <!-- /TABLE ARCHIVOS -->
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Archivos sin Subcategoría</th>
                                  <th><span class="float-right badge bg-success">{{count($b['archivos'])}}</span></th>
                                </tr>
                              </thead>
                              <tbody>
                                @if(count($b['archivos'])>0)
                                @foreach($b['archivos'] as $f)
                                <tr id="TrFile{{$loop->index}}OnCat{{$b['idcat']}}">
                                  <td>{{$f['archivo']}}</td>
                                  <td class="text-right py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                      @if($f['estado']=='1')
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileOnlyCat({{$f['idfile']}}, {{$b['idcat']}}, {{$loop->index}}, 'nosc')"><i class="fas fa-eye-slash"></i></a>
                                      @else
                                        <a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileOnlyCat({{$f['idfile']}}, {{$b['idcat']}}, {{$loop->index}}, 'nosc')"><i class="fas fa-eye"></i></a>
                                      @endif
                                      <a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileOnlyCat({{$f['idfile']}}, 'nosc')"><i class="fas fa-edit"></i></a>
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileOnlyCat({{$f['idfile']}})"><i class="fas fa-folder"></i></a>
                                      <a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileOnlyCat({{$f['idfile']}})"><i class="fas fas fa-download"></i></a>
                                      <a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileOnlyCat({{$f['idfile']}}, {{$loop->index}}, 'nosc')"><i class="fas fa-trash"></i></a>
                                    </div>
                                  </td>
                                </tr>
                                @endforeach
                                @else
                                  <tr>
                                    <td style="text-align: center;" colspan="2">Sin Archivos</td>
                                  </tr>
                                @endif
                              </tbody>
                            </table>
                            <!-- /TABLE ARCHIVOS -->
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

<!--MODAL AGG CATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalAggCatBiV" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
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
                <button type="button" class="btn btn-primary mb-2 savecatbv" onclick="guardarCategoriaBiV()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL UPDATE CATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalUpdateCatBiV" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
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
                <button type="button" class="btn btn-primary mb-2 updatecatbv" onclick="actualizarCategoriaBiV()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL AGG SUBCATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalAggSubCatBiV" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
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
                        <textarea name="inputSubcategoria" class="form-control text-justify" id="inputSubcategoria" cols="30" rows="5" placeholder="Categoría" 
                        autocomplete="off" maxlength="270"></textarea>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2 savesubcatbv" onclick="guardarSubCategoriaBiV()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL EDIT SUBCATEGORIA BIBLIOTECA VIRTUAL-->
<div class="modal fade" id="modalUpdateSubCatBiV" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
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
                <button type="button" class="btn btn-primary mb-2 updatesubcatbv" onclick="actualizarSubCategoriaBiV()">Guardar</button>
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
<script src="{{asset('assets/administrador/js/biblioteca_virtual.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
  
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 1500);
  });
</script>
@endsection