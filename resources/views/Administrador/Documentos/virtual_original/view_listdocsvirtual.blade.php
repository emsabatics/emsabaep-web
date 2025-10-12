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
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

<style>
  .bguardar {
    float: right;
    display: flex;
    flex-direction: column;
    margin-top: 13vh;
  }

  .container .dropify-wrapper {
    height: 295px;
  }

  .spanlabel {
    padding-left: 22px;
    font-size: 12.5px;
    font-weight: bold;
  }

  .loadInfo {
    width: 10rem !important;
    height: 10rem !important;
  }

  .formEdit {
    display: block;
    width: 50%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .noshow{
    display: none;
  }

  .celdaAsignado{
    word-break: break-word;
    white-space: pre-line;
    overflow-wrap: break-word;
    text-align: justify;
  }

  table {
    table-layout: fixed;
    word-wrap: break-word;
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
              <div class="card card-default">
                  <div class="card-header">
                      <h3 class="card-title">Actualizar Documentación Biblioteca Virtual</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group noevent">
                          @foreach($categoria as $c)
                            <div class="form-group noevent">
                              <label>Categoría:</label>
                              <textarea id="txtnamecat" class="form-control text-justify" cols="30" rows="5" placeholder="Categoría" 
                                autocomplete="off" maxlength="270">{{$c->descripcion}}</textarea>
                            </div>
                          @endforeach
                        </div>
                        <div class="form-group noevent">
                          <label>SubCategoría:</label>
                          <select class="form-control select2">
                            <optgroup label="Seleccione una Opción">
                              <option value="0">-Seleccione una Opción-</option>
                              @foreach ($subcategoria as $item)
                                @if($item->id == $idsubcat)
                                  <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                                @else
                                  <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endif
                              @endforeach
                            </optgroup>
                          </select>
                        </div>
                      </div>
                      <div class="col-6">
                        <!-- /TABLE ARCHIVOS -->
                        <table class="table table-responsive">
                          <thead>
                            <tr>
                              <th style="width: 10%;text-align: center;"></th>
                              <th style="width:60%">Archivos</th>
                              <th style="width:40%"><span class="float-right badge bg-success">{{count($archivos)}}</span></th>
                            </tr>
                          </thead>
                          <tbody>
                          @if(count($archivos)>0)
                            @foreach($archivos as $f)
                              <tr id="TrFile{{$loop->index}}">
                                <td>
                                  <div class='avatar' style="text-align: center;height: 25px;width: 25px;">
                                    <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 25px;height: 25px;">
                                  </div>
                                </td>
                                <td>{{$f->archivo}}</td>
                                <td class="text-right py-0 align-middle">
                                  <div class="btn-group btn-group-sm">
                                    @if($f->estado=='1')
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileSubCat({{$f->id}}, {{$loop->index}}, 'withsc')"><i class="fas fa-eye-slash"></i></a>
                                    @else
                                      <a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileSubCat({{$f->id}}, {{$loop->index}}, 'withsc')"><i class="fas fa-eye"></i></a>
                                    @endif
                                    <a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileSubCat({{$f->id}}, 'withsc')"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileSubCat({{$f->id}})"><i class="fas fa-folder"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileSubCat({{$f->id}})"><i class="fas fas fa-download"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileSubCat({{$f->id}}, {{$loop->index}}, 'withsc')"><i class="fas fa-trash"></i></a>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          @else
                            <tr>
                              <td style="text-align: center;" colspan="3">Sin Archivos</td>
                            </tr>
                          @endif
                        </tbody>
                      </table>
                      <!-- /TABLE ARCHIVOS -->
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</section>

<div id="modalFullSend" class="modal fade modal-full" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
        <!--<span aria-hidden="true">×</span>-->
    </button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border loadInfo mr-3 text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <br><br>
                <p style="font-size: 16px;"> Actualizando Documento... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/biblioteca_virtual.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });

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

  var getIdCat = {{ Illuminate\Support\Js::from($code) }};
  var getIdSubc = {{ Illuminate\Support\Js::from($idsubcat) }};
</script>
@endsection