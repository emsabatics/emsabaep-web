document.querySelectorAll('.permiso-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', async function () {
        // lógica AJAX aquí
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        const idRol = this.dataset.rol;
        const idModulo = this.dataset.modulo;
        const idSubmodulo = this.dataset.submodulo || null;
        const checked = this.checked;

        const csrfToken = $('#token').val();

        try {
            const response = await fetch("/permisos/actualizar", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_rol: idRol,
                    id_modulo: idModulo,
                    id_submodulo: idSubmodulo,
                    asignar: checked
                })
            });

            // Comprobar si la respuesta fue exitosa (código 2xx)
            /*if (!response.resultado) {
                throw new Error(`Error HTTP: ${response.status}`);
            }*/

            const responseText = await response.text();

            try {
                const data = JSON.parse(responseText); // <-- Parseo manual
                if (data.resultado === true) {
                    toastr.success('Permiso actualizado correctamente','',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                } else {
                    toastr.error('No se pudo Guardar!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }
            } catch (e) {
                toastr.error('Respuesta inválida del servidor!','',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
            }
        } catch (error) {
            swal('Ha ocurrido un error en la comunicación con el servidor','','error');
        }

        }else{
            swal('No tiene permiso para guardar','','error');
        }
    });
});

function backInterfaceRol(){
    window.location='/perfil-usuario';
}