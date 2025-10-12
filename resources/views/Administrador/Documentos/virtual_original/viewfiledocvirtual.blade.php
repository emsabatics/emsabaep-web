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

<style>
  .container-iframe{
    position: relative;
    overflow: hidden;
    width: 100%;
  }

  .responsive-iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
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
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Vista Documentación Biblioteca Virtual</h3>
                      <div class="card-tools" id="card-tools">
                          <button type="button" class="btn btn-primary btn-block" onclick="urlbacktosubc()"><i
                              class="fas fa-arrow-left mr-2"></i> Regresar</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
              </div>
          </div>
      </div>
      <div class="row">
        <div class="col-2"></div>
        @foreach ($filedocvirtual as $item)
        <div class="col-8">
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">{{$item->titulo}}</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <input type="hidden" name="codecat" id="codecat" value="{{$item->id_bv_categoria}}">
                  <input type="hidden" name="codesubcat" id="codesubcat" value="{{$item->id_bv_subcategoria}}">
                  <div class="container-iframe" style="overflow-x: auto;">
                    <object data="/doc-bibliotecavirtual/{{$item->archivo}}" type="application/pdf" width="800" height="800"></object>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        <div class="col-2"></div>
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
<script src="{{asset('assets/administrador/js/biblioteca_virtual.js')}}"></script>

<script>
  $(document).ready(function(){
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 2000);
  });

  var currIdCat= $('#codecat').val();
  var currIdSubc= $('#codesubcat').val();
  if(currIdSubc==''){
    currIdSubc=0;
  }
</script>
@endsection