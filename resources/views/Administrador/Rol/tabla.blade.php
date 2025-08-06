@if($permisos->isEmpty() && $modulos->isEmpty())
    <p class="pnodata">No hay resultados.</p>
@else
    <ol>
        @foreach($modulos as $modulo)
            <li>
                <label>
                    <input type="checkbox"
                        class="permiso-checkbox"
                        data-rol="{{ $idRol }}"
                        data-modulo="{{ $modulo->id }}"
                        data-submodulo=""
                        {{ $permisos->contains(function($p) use ($modulo) {
                                return $p['modulo'] == $modulo->id && $p['submodulo'] === null;
                            }) ? 'checked' : '' }}>
                        {{ $modulo->nombre }}
                </label>

                @if(count($modulo->submodulos ?? []) > 0)
                    <ol type="a">
                        @foreach($modulo->submodulos as $sub)
                            <li>
                                <label>
                                    <input type="checkbox"
                                        class="permiso-checkbox"
                                        data-rol="{{ $idRol }}"
                                        data-modulo="{{ $modulo->id }}"
                                        data-submodulo="{{ $sub->id }}"
                                    {{ $permisos->contains(function($p) use ($modulo, $sub) {
                                            return $p['modulo'] == $modulo->id && $p['submodulo'] == $sub->id;
                                        }) ? 'checked' : '' }}>
                                    {{ $sub->submodulo }}
                                </label>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
    </ol>
@endif