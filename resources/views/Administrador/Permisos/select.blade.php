@if($pmodulos->isEmpty())
    <label>Módulos:</label>
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">No hay resultados.</h4>
        <p>Por favor, verifique que el rol de usuario disponga de los permisos necesarios.</p>
    </div>
@else
    <label>Módulos:</label>
    <select class="form-control select2" id="selModulo">
        <optgroup label="Seleccione una Opción">
            <option value="0">-Seleccione una Opción-</option>
            @foreach ($pmodulos as $m)
                <option value="{{$m->id}}">{{$m->nombre}}</option>
            @endforeach
        </optgroup>
    </select>
@endif