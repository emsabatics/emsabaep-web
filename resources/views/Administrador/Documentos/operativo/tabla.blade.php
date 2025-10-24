@php
    $subcategoria = collect($subcategoria);
@endphp
@if($subcategoria->isEmpty())
    <p>No hay resultados.</p>
@else
    @foreach($subcategoria as $sc)
        <tr id="TrSub{{$loop->iteration}}Cat{{$loop->index}}">
            <td>{{$sc['descripcionsubcat']}}</td>
            <td>
                @if(count($sc['archivossubcat']) == 0)
                    Sin Archivos
                @elseif(count($sc['archivossubcat']) == 1)
                    {{count($sc['archivossubcat'])}} Archivo
                @elseif(count($sc['archivossubcat']) > 1)
                    {{count($sc['archivossubcat'])}} Archivos
                @endif
                <input type="hidden" name="idcat_encriptado_item{{$loop->index}}" id="idcat_encriptado_item{{$loop->index}}"
                    value="'{{encriptarNumero($sc['idcategoria'])}}'">
                <input type="hidden" name="idsubcat_encriptado_item{{$loop->index}}"
                    id="idsubcat_encriptado_item{{$loop->index}}" value="'{{encriptarNumero($sc['idsubcat'])}}'">
            </td>
            <td class="text-right py-0 align-middle">
                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0)" class="btn btn-danger" title="Eliminar SubCategoría"
                        onclick="deleteFileSubCat('{{encriptarNumero($sc['idcategoria'])}}', '{{encriptarNumero($sc['idsubcat'])}}', {{$loop->index}})"><i
                            class="fas fa-trash"></i></a>
                    @if($sc['estadosubcat'] == '1')
                        <a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar Subcategoría"
                            onclick="inactivarSubCat('{{encriptarNumero($sc['idsubcat'])}}', '{{encriptarNumero($sc['idcategoria'])}}', {{$loop->index}})"><i
                                class="fas fa-eye-slash"></i></a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-secondary" title="Activar Subcategoría"
                            onclick="activarSubCat('{{encriptarNumero($sc['idsubcat'])}}', '{{encriptarNumero($sc['idcategoria'])}}', {{$loop->index}})"><i
                                class="fas fa-eye"></i></a>
                    @endif
                    <a href="javascript:void(0)"
                        onclick="registerFileSubCat('{{encriptarNumero($sc['idcategoria'])}}', '{{encriptarNumero($sc['idsubcat'])}}')"
                        class="btn btn-success" title="Agregar Documentos"><i class="fas fa-folder-plus"></i></a>
                    <a href="javascript:void(0)" class="btn btn-primary" title="Editar Subcategoría"
                        onclick="editSubCat('{{encriptarNumero($sc['idsubcat'])}}', {{$loop->index}})"><i
                            class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" class="btn btn-info" title="Editar Documentos SubCategoría"
                        onclick="viewListFilesSubCat('{{encriptarNumero($sc['idcategoria'])}}', '{{encriptarNumero($sc['idsubcat'])}}')"><i
                            class="fas fa-file-signature"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
@endif