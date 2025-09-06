@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | LOTAIP {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/collapse.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/inner-list.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<style>
  .btntable{
    padding: 3px;
    font-size: 18px;
  }

  .btntable i{
    color: #fff;
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
    <h1>LOTAIP V2</h1>
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
            <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> LOTAIP V2</h3>
            <div class="card-tools" id="card-tools">
              <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarlotaip()"><i
                class="far fa-plus-square mr-2"></i> Agregar</button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-4" id="divLotaip">
            <div class="row">
              @if(count($lotaip) > 0)
              <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                  @foreach ($lotaip as $i)
                    @if ($loop->index=='0')
                      <a class="nav-link active" id="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab" data-toggle="pill" href="#vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tab" aria-controls="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" aria-selected="true">{{$i['anio']}}</a>
                    @else
                      <a class="nav-link" id="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab" data-toggle="pill" href="#vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tab" aria-controls="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" aria-selected="false">{{$i['anio']}}</a>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">
                  @foreach ($lotaip as $i)
                    @if ($loop->index=='0')
                      <div class="tab-pane text-left fade show active" id="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tabpanel" aria-labelledby="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab">
                        <div id="accordion" class="myaccordion">
                          @foreach ($i['nmes'] as $mes)
                          <div class="card">
                            <div class="card-header" id="headingOne">
                              <h2 class="mb-0">
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$mes['mes']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$mes['mes']}}">
                                  {{$mes['mes']}}
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                              </h2>
                            </div>
                            <div id="collapse-{{$i['anio']}}-{{$mes['mes']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$mes['mes']}}" data-parent="#accordion">
                              <div class="card-body">
                                <ul class="accordionul">
                                  @foreach ($mes['articulos'] as $art)
                                  @if ($art['tipo']=='articulo')
                                  <li>
                                    <p class="nest">{{$art['articulo']}}</p>
                                    <ul class="inner">
                                      @if(count($art['archivos']) > 0)
                                        @foreach ($art['archivos'] as $f)
                                          <li>
                                            @if ($f['archivo']==null || $f['archivo']=='null')
                                              <p class="nest">{{$f['literal']}}.- {{$f['descripcion']}}</p>
                                              <ul class="inner">
                                                <li>
                                                  <p class="nest">Conjunto de Datos</p>
                                                  <ul class="inner">
                                                    <div class="options-list">
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenCD({{$f['id']}})">
                                                        <i class="fas fa-folder mr-3"></i> Ver
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateCD({{$f['id']}})">
                                                        <i class="far fa-edit mr-3"></i> Actualizar
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadCD('{{encriptarNumero($f['id'])}}')">
                                                        <i class="fas fa-download mr-3"></i> Descargar
                                                      </button>
                                                    </div>
                                                  </ul>
                                                </li>
                                                <li>
                                                  <p class="nest">Metadatos</p>
                                                  <ul class="inner">
                                                    <div class="options-list">
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenMD({{$f['id']}})">
                                                        <i class="fas fa-folder mr-3"></i> Ver
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateMD({{$f['id']}})">
                                                        <i class="far fa-edit mr-3"></i> Actualizar
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadMD('{{encriptarNumero($f['id'])}}')">
                                                        <i class="fas fa-download mr-3"></i> Descargar
                                                      </button>
                                                    </div>
                                                  </ul>
                                                </li>
                                                <li>
                                                  <p class="nest">Diccionario de Datos</p>
                                                  <ul class="inner">
                                                    <div class="options-list">
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenDD({{$f['id']}})">
                                                        <i class="fas fa-folder mr-3"></i> Ver
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateDD({{$f['id']}})">
                                                        <i class="far fa-edit mr-3"></i> Actualizar
                                                      </button>
                                                      <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadDD('{{encriptarNumero($f['id'])}}')">
                                                        <i class="fas fa-download mr-3"></i> Descargar
                                                      </button>
                                                    </div>
                                                  </ul>
                                                </li>
                                              </ul>
                                            @else
                                              <p class="nest">{{$f['descripcion']}}</p>
                                              <ul class="inner">
                                                <div class="options-list">
                                                  <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenFile({{$f['id']}})">
                                                    <i class="fas fa-folder mr-3"></i> Ver
                                                  </button>
                                                  <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateFile({{$f['id']}})">
                                                    <i class="far fa-edit mr-3"></i> Actualizar
                                                  </button>
                                                  <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadFile('{{encriptarNumero($f['id'])}}')">
                                                    <i class="fas fa-download mr-3"></i> Descargar
                                                  </button>
                                                </div>
                                              </ul>
                                            @endif
                                            </li>
                                        @endforeach
                                      @else
                                      <li>
                                        <p class="nest">Sin Datos</p>
                                      </li>
                                      @endif
                                    </ul>
                                  </li>  
                                  @else
                                  <li>
                                    <p class="nest">{{$art['opcion']}}</p>
                                    @if(count($art['archivos']) > 0)
                                    <ul class="inner">
                                      @foreach($art['archivos'] as $fl)
                                      <li>
                                        <p class="nest">{{$fl['descripcion']}}</p>
                                        <ul class="inner">
                                          <div class="options-list">
                                            <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenOtherFile({{$fl['id']}})">
                                              <i class="fas fa-folder mr-3"></i> Ver
                                            </button>
                                            <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateOtherFile({{$fl['id']}})">
                                              <i class="far fa-edit mr-3"></i> Actualizar
                                            </button>
                                            <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadOtherFile('{{encriptarNumero($fl['id'])}}')">
                                              <i class="fas fa-download mr-3"></i> Descargar
                                            </button>
                                          </div>
                                        </ul>
                                      </li>
                                      @endforeach
                                    </ul>
                                    @else
                                      <li>
                                        <p class="nest">Sin Datos</p>
                                      </li>
                                      @endif
                                  </li> 
                                  @endif
                                  @endforeach
                                  <!--<li>
                                    <p class="nest">List 1</p>
                                    <ul class="inner">
                                      <li>
                                        <p class="nest">Inner list!</p>
                                        <ul class="inner">
                                          <li>Some content in the first inner list.</li>
                                          <li>Other stuff that's in the first inner list.</li>
                                        </ul>
                                      </li>
                                      <li>
                                        <p class="nest">Another inner list!</p>
                                        <ul class="inner">
                                          <li>Stuff about inner list 2 goes here.</li>
                                        </ul>
                                      </li>
                                    </ul>
                                  </li>
                                  <li>
                                    <p class="nest">List 2</p>
                                    <ul class="inner">
                                      <li>
                                        <p class="nest">Inner list!</p>
                                        <ul class="inner">
                                          <li>Some content in the first inner list.</li>
                                          <li>Other stuff that's in the first inner list.</li>
                                        </ul>
                                      </li>
                                      <li>
                                        <p class="nest">Another inner list!</p>
                                        <ul class="inner">
                                          <li>Stuff about inner list 2 goes here.</li>
                                        </ul>
                                      </li>
                                    </ul>
                                  </li>
                                  <li>
                                    <p class="nest">List 3</p>
                                    <ul class="inner">
                                      <li>Some stuff about list 3</li>
                                      <li>
                                        <p class="nest">List 3 has an inner list too?!</p>
                                        <ul class="inner">
                                          <li>
                                            <p class="nest">WAIT THIS ONE HAS ANOTHER INNER LIST?!</p>
                                            <ul class="inner">
                                              <li>That's it. But still. Cool.</li>
                                            </ul>
                                          </li>
                                        </ul>
                                      </li>
                                    </ul>
                                  </li>-->
                                </ul>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    @else
                    <div class="tab-pane text-left fade show" id="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tabpanel" aria-labelledby="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab">
                      <div id="accordion" class="myaccordion">
                        @foreach ($i['nmes'] as $mes)
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$mes['mes']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$mes['mes']}}">
                                {{$mes['mes']}}
                                <span class="fa-stack fa-sm">
                                  <i class="fas fa-circle fa-stack-2x"></i>
                                  <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                </span>
                              </button>
                            </h2>
                          </div>
                          <div id="collapse-{{$i['anio']}}-{{$mes['mes']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$mes['mes']}}" data-parent="#accordion">
                            <div class="card-body">
                              <ul class="accordionul">
                                @foreach ($mes['articulos'] as $art)
                                @if ($art['tipo']=='articulo')
                                <li>
                                  <p class="nest">{{$art['articulo']}}</p>
                                  <ul class="inner">
                                    @if(count($art['archivos']) > 0)
                                      @foreach ($art['archivos'] as $f)
                                        <li>
                                          @if ($f['archivo']==null || $f['archivo']=='null')
                                            <p class="nest">{{$f['literal']}}.- {{$f['descripcion']}}</p>
                                            <ul class="inner">
                                              <li>
                                                <p class="nest">Conjunto de Datos</p>
                                                <ul class="inner">
                                                  <div class="options-list">
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenCD({{$f['id']}})">
                                                      <i class="fas fa-folder mr-3"></i> Ver
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateCD({{$f['id']}})">
                                                      <i class="far fa-edit mr-3"></i> Actualizar
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadCD('{{encriptarNumero($f['id'])}}')">
                                                      <i class="fas fa-download mr-3"></i> Descargar
                                                    </button>
                                                  </div>
                                                </ul>
                                              </li>
                                              <li>
                                                <p class="nest">Metadatos</p>
                                                <ul class="inner">
                                                  <div class="options-list">
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenMD({{$f['id']}})">
                                                      <i class="fas fa-folder mr-3"></i> Ver
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateMD({{$f['id']}})">
                                                      <i class="far fa-edit mr-3"></i> Actualizar
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadMD('{{encriptarNumero($f['id'])}}')">
                                                      <i class="fas fa-download mr-3"></i> Descargar
                                                    </button>
                                                  </div>
                                                </ul>
                                              </li>
                                              <li>
                                                <p class="nest">Diccionario de Datos</p>
                                                <ul class="inner">
                                                  <div class="options-list">
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenDD({{$f['id']}})">
                                                      <i class="fas fa-folder mr-3"></i> Ver
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateDD({{$f['id']}})">
                                                      <i class="far fa-edit mr-3"></i> Actualizar
                                                    </button>
                                                    <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadDD('{{encriptarNumero($f['id'])}}')">
                                                      <i class="fas fa-download mr-3"></i> Descargar
                                                    </button>
                                                  </div>
                                                </ul>
                                              </li>
                                            </ul>
                                          @else
                                            <p class="nest">{{$f['descripcion']}}</p>
                                            <ul class="inner">
                                              <div class="options-list">
                                                <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenFile({{$f['id']}})">
                                                  <i class="fas fa-folder mr-3"></i> Ver
                                                </button>
                                                <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateFile({{$f['id']}})">
                                                  <i class="far fa-edit mr-3"></i> Actualizar
                                                </button>
                                                <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Documento" onclick="downloadFile('{{encriptarNumero($f['id'])}}')">
                                                  <i class="fas fa-download mr-3"></i> Descargar
                                                </button>
                                              </div>
                                            </ul>
                                          @endif
                                          </li>
                                      @endforeach
                                    @else
                                    <li>
                                      <p class="nest">Sin Datos</p>
                                    </li>
                                    @endif
                                  </ul>
                                </li>  
                                @else
                                <li>
                                  <p class="nest">{{$art['opcion']}}</p>
                                  @if(count($art['archivos']) > 0)
                                  <ul class="inner">
                                    @foreach($art['archivos'] as $fl)
                                    <li>
                                      <p class="nest">{{$fl['descripcion']}}</p>
                                      <ul class="inner">
                                        <div class="options-list">
                                          <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenOtherFile({{$fl['id']}})">
                                            <i class="fas fa-folder mr-3"></i> Ver
                                          </button>
                                          <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateOtherFile({{$fl['id']}})">
                                            <i class="far fa-edit mr-3"></i> Actualizar
                                          </button>
                                          <button type="button" style="color: white;padding: 5px;font-size: 17px;" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadOtherFile('{{encriptarNumero($fl['id'])}}')">
                                            <i class="fas fa-download mr-3"></i> Descargar
                                          </button>
                                        </div>
                                      </ul>
                                    </li>
                                    @endforeach
                                  </ul>
                                  @else
                                    <li>
                                      <p class="nest">Sin Datos</p>
                                    </li>
                                    @endif
                                </li> 
                                @endif
                                @endforeach
                                <!--<li>
                                  <p class="nest">List 1</p>
                                  <ul class="inner">
                                    <li>
                                      <p class="nest">Inner list!</p>
                                      <ul class="inner">
                                        <li>Some content in the first inner list.</li>
                                        <li>Other stuff that's in the first inner list.</li>
                                      </ul>
                                    </li>
                                    <li>
                                      <p class="nest">Another inner list!</p>
                                      <ul class="inner">
                                        <li>Stuff about inner list 2 goes here.</li>
                                      </ul>
                                    </li>
                                  </ul>
                                </li>
                                <li>
                                  <p class="nest">List 2</p>
                                  <ul class="inner">
                                    <li>
                                      <p class="nest">Inner list!</p>
                                      <ul class="inner">
                                        <li>Some content in the first inner list.</li>
                                        <li>Other stuff that's in the first inner list.</li>
                                      </ul>
                                    </li>
                                    <li>
                                      <p class="nest">Another inner list!</p>
                                      <ul class="inner">
                                        <li>Stuff about inner list 2 goes here.</li>
                                      </ul>
                                    </li>
                                  </ul>
                                </li>
                                <li>
                                  <p class="nest">List 3</p>
                                  <ul class="inner">
                                    <li>Some stuff about list 3</li>
                                    <li>
                                      <p class="nest">List 3 has an inner list too?!</p>
                                      <ul class="inner">
                                        <li>
                                          <p class="nest">WAIT THIS ONE HAS ANOTHER INNER LIST?!</p>
                                          <ul class="inner">
                                            <li>That's it. But still. Cool.</li>
                                          </ul>
                                        </li>
                                      </ul>
                                    </li>
                                  </ul>
                                </li>-->
                              </ul>
                            </div>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    @endif
                  @endforeach
                </div>
              </div>
              @else
              <div class="col-12">
                <div class="col-lg-12 no-data">
                  <div class="imgadvice">
                    <img src="assets/administrador/img/icons/no-hay-resultados.png" alt="Construccion">
                  </div>
                  <span class="mensaje-noticia mt-4 mb-4">No hay <strong>datos</strong> disponibles por el momento...</span>
                </div>
              </div>
              @endif
            </div>
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
<script src="{{asset('assets/administrador/js/inner-list.js')}}"></script>
<script src="{{asset('assets/administrador/js/lotaipv2.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  $("#accordion").on("show.bs.collapse hide.bs.collapse", e => {
    $(e.target)
      .prev()
      .find("i:last-child")
      .toggleClass("fa-plus fa-minus");
  });
  const nameInterfaz = "LOTAIP 2.0";
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      //showInfoLotaip();
      $('#modalCargando').modal('hide');
    }, 2500);
  });
</script>
@endsection