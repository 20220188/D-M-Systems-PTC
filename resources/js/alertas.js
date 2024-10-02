
//Alerta para botones de eliminar 
document.querySelectorAll('#btnEliminar').forEach(button => {
    button.addEventListener('click', function () {
        // Muestra la alerta de SweetAlert2
        Swal.fire({
            title: "Estás seguro?",
            text: "No podras revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, continuar!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Eliminado!",
                    text: "Se ha eliminado exitosamente.",
                    icon: "success"
                });
            }
        });
    });
});

//Alerta para botones de editar
document.querySelectorAll('#btnEditar').forEach(button => {
    button.addEventListener('click', function () {
        // Muestra la alerta de SweetAlert2
        Swal.fire({
            title: "¿Deseas conservar los cambios?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            denyButtonText: `No guardar`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("Los cambios han sido guardados!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Los cambios no han sido guardados", "", "info");
            }
        });
    });
});

//Alerta para botones de agregado
document.querySelectorAll('#btnAgregar').forEach(button => {
    button.addEventListener('click', function () {
        // Muestra la alerta de SweetAlert2
        Swal.fire({
            title: "Agregado!",
            text: "Se ha agregado exitosamente",
            icon: "success"
        });
    });
});

//Alerta para botones de cancelado/descartado
document.querySelectorAll('#btnCancelar').forEach(button => {
    button.addEventListener('click', function () {
        // Muestra la alerta de SweetAlert2
        Swal.fire({
            title: "Cancelado!",
            text: "Se ha cancelado la acción",
            icon: "info"
        });
    });
});