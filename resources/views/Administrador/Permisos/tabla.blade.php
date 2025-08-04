@if($permisos->isEmpty())
    <p class="pnodata">No hay resultados.</p>
@else
    <table class="table datatables" id="tablaPermisos">
        <thead class="thead-dark">
            <tr style="pointer-events:none;">
                <th>N°</th>
                <th>Nombres</th>
                <th>Rol</th>
                <th>Observaciones</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permisos as $item)
                <tr id="Tr{{$loop->index}}">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item['nombres']}}</td>
                    <td>{{$item['rol']}}</td>
                    <td>Tiene {{$item['total_modulo']}} Módulos Asignados</td>
                    <td>
                        @if ($item['estado']=='1')
                        <span class='badge badge-success'>Activo</span></td>
                        @else
                        <span class='badge badge-secondary'>Inactivo</span>
                        @endif
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm mt-2 mr-3" title="Ajustes" href="javascript:void(0)"
                            onclick="editarPermiso('{{encriptarNumero($item['id'])}}', '{{$item['nombres']}}', {{$loop->index}})">
                            <i class="fa fa-cog ml-2 mr-2"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif