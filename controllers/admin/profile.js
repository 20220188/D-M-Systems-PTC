// Constantes para establecer los elementos del formulario de editar perfil.
const PROFILE_FORM = document.getElementById('profileForm');
const PASSWORD_MODAL = new bootstrap.Modal('#passwordModal');
const PASSWORD_FORM = document.getElementById('passwordForm');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Editar perfil';
    // Carga los datos del perfil
    await loadProfileData();
});

// Función para cargar los datos del perfil
async function loadProfileData() {
    // Petición para obtener los datos del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'readProfile');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se inicializan los campos del formulario con los datos del usuario que ha iniciado sesión.
        const ROW = DATA.dataset;
        document.getElementById('nombreAdministrador').value = ROW.nombre;
        document.getElementById('apellidoAdministrador').value = ROW.telefono;
        document.getElementById('correoAdministrador').value = ROW.correo;
        document.getElementById('aliasAdministrador').value = ROW.usuario;
    } else {
        sweetAlert(2, DATA.error, null);
    }
}

// Método del evento para cuando se envía el formulario de editar perfil.
PROFILE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(PROFILE_FORM);
    // Petición para actualizar los datos personales del usuario.
    const DATA = await fetchData(USER_API, 'editProfile', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true);
        PROFILE_FORM.reset(); // Limpiar el formulario después de enviar
        await loadProfileData(); // Recargar los datos del perfil
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Método del evento para cuando se envía el formulario de cambiar contraseña.
PASSWORD_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(PASSWORD_FORM);
    // Petición para actualizar la contraseña.
    const DATA = await fetchData(USER_API, 'changePassword', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        PASSWORD_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        PASSWORD_FORM.reset(); // Limpiar el formulario después de enviar
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Función para preparar el formulario al momento de cambiar la contraseña.
const openPassword = () => {
    // Se abre la caja de diálogo que contiene el formulario.
    PASSWORD_MODAL.show();
    // Se restauran los elementos del formulario.
    PASSWORD_FORM.reset();
}

// Limpiar los formularios cuando se cierra la ventana o se recarga la página
window.addEventListener('beforeunload', () => {
    PROFILE_FORM.reset();
    PASSWORD_FORM.reset();
});

