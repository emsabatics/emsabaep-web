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

  .info-box .info-box-content .info-box-text {
    white-space: normal;      /* Permite saltos de línea */
    word-wrap: break-word;    /* Rompe palabras largas */
    overflow-wrap: break-word;/* Soporte moderno */
  }
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Reportes Contador Descargas</h1>
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
            <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Documentación Administrativa</h3>
            <div class="card-tools" id="card-tools"></div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-4" id="divDocVirtual">
              <div class="row">
                <div class="col-lg-3 col-6">
                  <!-- small card -->
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3 id="h3ContSubCat">{{$totalGeneral}}</h3>
                      <p>Total de Descargas</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                @foreach ($resultado as $r)
                  <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-download"></i></span>

                      <div class="info-box-content">
                        <span class="info-box-text">{{$r['tabla']}}</span>
                        <span class="info-box-number">{{$r['total']}}</span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                  </div>
                @endforeach
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex">
              <!-- Contenedor del gráfico -->
              <div id="graficoDescargasDocAdmin" style="width:100%; height:400px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
              <h3 class="card-title">Detalles</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover">
              <tbody id="tabla-datos">
                @foreach ($resultado as $res)
                <tr data-widget="expandable-table" aria-expanded="false">
                  @isset($res['archivos'])
                      @if(count($res['archivos']) > 0)
                      <td>
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        {{$res['tabla']}}
                      </td>
                      @endif
                  @endisset
                </tr>
                <tr class="expandable-body">
                  <td>
                    <div class="p-0">
                      <table class="table table-hover">
                        <tbody>
                          @if (!empty($res['archivos']))
                            <tr>
                              <td><b>Título</b></td>
                              <td><b>Descargas</b></td>
                            </tr>
                            @foreach ($res['archivos'] as $ar)
                              <tr>
                                <td>
                                  {{$ar->titulo}}
                                   @if (!empty($ar->observacion))
                                   - {{$ar->observacion}}
                                   @endif
                                </td>
                                <td>{{$ar->contador_descargas}}</td>
                                @if (!empty($ar->resolucion))
                                <td><b>Resolución: </b>{{$ar->resolucion}}</td>
                                <td><b>Descargas: </b>{{$ar->contador_descargas_resol}}</td>
                                @endif
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
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
  const datos = @json($resultado);
  // Extraer nombres y totales
  const categorias = datos.map(item => item.tabla);
  const valores = datos.map(item => item.total);

  $(document).ready(function () {
    configChart();
    $('#modalCargando').modal('show');
    setTimeout(() => {
      loadDataDocAdmin(categorias,valores);
      $('#modalCargando').modal('hide');
    }, 3000);
  });
</script>
@endsection