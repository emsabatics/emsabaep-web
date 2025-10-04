@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Contador {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('assets/administrador/css/ionicons/css/ionicons.min.css')}}">
<style>
  .spanlabel {
    padding-left: 22px;
    font-size: 12.5px;
    font-weight: bold;
  }

  .gridFilter{
    display: grid;
    grid-template-columns: 1fr 34% auto;
    grid-template-rows: 2fr;
    row-gap: 1em;
    column-gap: 1em;
    /* justify-content: end; */
    /* justify-items: end; */
    width: 100%;
  }
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Reportes Contador</h1>
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
            <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Reportes Contador</h3>
            <div class="card-tools" id="card-tools"></div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-4" id="divDocVirtual">
              <div class="row">
                <div class="col-lg-3 col-6">
                  <!-- small card -->
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3 id="h3ContSubCat">{{$totalValor}}</h3>
                      <p>Total de Visitas General</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header border-0">
                      <div class="d-flex justify-content-between">
                        <div class="gridFilter">
                        <h3 class="card-title">Visitas en la Página de Inicio</h3>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                          </div>
                          <input type="text" class="form-control float-right" id="reservationtime">
                        </div>
                        <div>
                          <button id="btnFiltro" class="btn btn-outline-primary" style="float: right;" type="button" onclick="getfiltroFechas()">
                            <i class="fas fa-search mr-2"></i> Filtrar
                          </button>
                        </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex">
                        <!-- Contenedor del gráfico -->
                        <div id="graficoVisitas" style="width:100%; height:400px;"></div>
                      </div>
                    </div>
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

<!-- Librería Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!--<script src="https://code.highcharts.com/themes/adaptive.js"></script>-->

<!-- date-range-picker -->
<script src="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/administrador/js/reportescontador.js')}}"></script>

<script>
  var fstart= @json($fechaInicio);
  var fend= @json($fechaFin);
  var dataContador = @json($dataTotal);
  var labels = @json($labels);
  var numberArray = dataContador.map(Number);

  $(document).ready(function () {
    configChart();
    loadDatPicker();
    $('#modalCargando').modal('show');
    setTimeout(() => {
      loadData(numberArray);
      $('#modalCargando').modal('hide');
    }, 1500);
  });
</script>
@endsection