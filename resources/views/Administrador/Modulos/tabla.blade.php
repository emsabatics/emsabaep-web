@if($modulos->isEmpty())
    <p class="pnodata">No hay resultados.</p>
@else
    <table class="table datatables" id="tablaModulos">
        <thead class="thead-dark">
            <tr style="pointer-events:none;">
                <th>N°</th>
                <th>Nombre</th>
                <th>Ícono</th>
                <th>Nivel de Prioridad</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modulos as $item)
                <tr id="Tr{{$loop->index}}">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->nombre}}</td>
                    <td>{{$item->icono}}</td>
                    <td>{{$item->nivel_prioridad}}</td>
                    <td>
                        @if ($item->estado=='1')
                        <span class='badge badge-success'>Activo</span></td>
                        @else
                        <span class='badge badge-secondary'>Inactivo</span>
                        @endif
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm mt-2 mr-3" title="Editar" href="javascript:void(0)"
                            onclick="editarModulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                            <i class="far fa-edit ml-2 mr-2"></i>
                        </a>
                        @if ($item->estado=='1')
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" title="Inactivar" href="javascript:void(0)" onclick="inactivarModulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                              <i class="fas fa-eye-slash ml-2 mr-2"></i>
                            </a>
                        @else
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" title="Activar" href="javascript:void(0)" onclick="activarModulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                              <i class="fas fa-eye ml-2 mr-2"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif