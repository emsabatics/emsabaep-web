function puedeGuardarM(modulo) {
    let permiso = window.Permisos.find(p => p.modulo === modulo);
    return permiso.guardar;
    //return permiso ? permiso.guardar : 'no';
}

function puedeActualizarM(modulo) {
    let permiso = window.Permisos.find(p => p.modulo === modulo);
    return permiso.actualizar;
    //return permiso ? permiso.actualizar : 'no';
}

function puedeEliminarM(modulo) {
    let permiso = window.Permisos.find(p => p.modulo === modulo);
    return permiso.eliminar;
    //return permiso ? permiso.eliminar : 'no';

}

function puedeDescargarM(modulo) {
    let permiso = window.Permisos.find(p => p.modulo === modulo);
    return permiso.descargar;
    //return permiso ? permiso.descargar : 'no';
}

function puedeGuardarSM(submodulo) {
    let permiso = window.Permisos.find(p => p.submodulo === submodulo);
    return permiso.guardar;
    //return permiso ? permiso.guardar : 'no';
}

function puedeActualizarSM(submodulo) {
    let permiso = window.Permisos.find(p => p.submodulo === submodulo);
    return permiso.actualizar;
    //return permiso ? permiso.actualizar : 'no';
}

function puedeEliminarSM(submodulo) {
    let permiso = window.Permisos.find(p => p.submodulo === submodulo);
    return permiso.eliminar;
    //return permiso ? permiso.eliminar : 'no';
}

function puedeDescargarSM(modulo) {
    let permiso = window.Permisos.find(p => p.modulo === modulo);
    return permiso.descargar;
    //return permiso ? permiso.descargar : 'no';
}