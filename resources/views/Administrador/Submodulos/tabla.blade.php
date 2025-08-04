@if($submodulos->isEmpty())
    <p class="pnodata">No hay resultados.</p>
@else
    <table class="table datatables" id="tablaSubmodulos">
        <thead class="thead-dark">
            <tr style="pointer-events:none;">
                <th>N°</th>
                <th>Módulo</th>
                <th>Submódulo</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($submodulos as $item)
                <tr id="Tr{{$loop->index}}">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->modulo}}</td>
                    <td>{{$item->submodulo}}</td>
                    <td>
                        @if ($item->estado=='1')
                        <span class='badge badge-success'>Activo</span></td>
                        @else
                        <span class='badge badge-secondary'>Inactivo</span>
                        @endif
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm mt-2 mr-3" title="Editar" href="javascript:void(0)"
                            onclick="editarSubmodulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                            <i class="far fa-edit ml-2 mr-2"></i>
                        </a>
                        @if ($item->estado=='1')
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" title="Inactivar" href="javascript:void(0)" onclick="inactivarSubmodulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                              <i class="fas fa-eye-slash ml-2 mr-2"></i>
                            </a>
                        @else
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" title="Activar" href="javascript:void(0)" onclick="activarSubmodulo('{{encriptarNumero($item->id)}}', {{$loop->index}})">
                              <i class="fas fa-eye ml-2 mr-2"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif