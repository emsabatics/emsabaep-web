@if($solicitudes->isEmpty())
    <p>No hay resultados.</p>
@else
    <table class="table datatables" id="tablaDocAdmin">
        <thead class="thead-dark">
            <tr style="pointer-events:none;">
                <th>N°</th>
                <th>Cuenta</th>
                <th>Nombres</th>
                <th>Contactos</th>
                <th>Fecha de Ingreso</th>
                <th>Estado Mensaje</th>
                <th>Última Modificación</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($solicitudes as $item)
                <tr id="Tr{{$loop->index}}">
                    <td>{{$loop->iteration}}</td>
                    <td>
                        @if(strlen($item['cuenta']) > 0)
                            {{$item['cuenta']}}
                        @else
                            Sin Registro
                        @endif
                    </td>
                    <td>{{$item['nombres']}}</td>
                    <td>
                        {{$item['email']}} <br>
                        {{$item['telefono']}}
                    </td>
                    <td>{{$item['fecha']}}</td>
                    <td>
                        @if(strlen($item['observaciones']) > 0)
                            @if ($item['observaciones'] == 'Finalizado')
                                <span class="badge badge-secondary">{{$item['observaciones']}}</span>
                            @else
                                <span class="badge badge-success">{{$item['observaciones']}}</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Sin Registro</span>
                        @endif
                    </td>
                    <td>
                        @if(strlen($item['nombre_usuario']) > 0)
                            <b>Usuario: </b>{{$item['nombre_usuario']}}
                        @else
                            <b>Usuario: </b>Sin Registro
                        @endif
                        <br>
                        @if(strlen($item['ultima_modificacion']) > 0)
                            <b>Fecha: </b>{{$item['ultima_modificacion']}}
                        @else
                            <b>Fecha: </b>Sin Registro
                        @endif
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm mt-2 mr-3" title="Seguimiento" href="javascript:void(0)"
                            onclick="seguimientosolicitud('{{encriptarNumero($item['id'])}}')">
                            <i class="far fa-edit ml-2 mr-2"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif