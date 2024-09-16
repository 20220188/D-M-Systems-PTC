// Constantes para completar las rutas de la API de USUARIO.
const USUARIO_API = 'services/admin/admin_usuarios.php';

/*
*Elementos para la tabla USUARIOS
*/
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');

// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

const SAVE_FORM_REPORT = document.getElementById('saveFormReport')
//Constantes para establecer los elementos del componente Modal para reporte parametrizado.
const SAVE_MODAL_REPORT = new bootstrap.Modal('#saveModalReport'),
    MODAL_TITLE_REPORT = document.getElementById('modalTitleReport');

// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');

// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_USUARIO = document.getElementById('idUsuario'),
    USUARIO = document.getElementById('Usuario'),
    CLAVE = document.getElementById('Clave'),
    CONFIRMAR_CLAVE = document.getElementById('confirmarClave'),
    CORREO = document.getElementById('correoUsuario'),
    NOMBRE_USUARIO = document.getElementById('nombreUsuario'),
    DUI = document.getElementById('DUIUsuario'),
    TELEFONO = document.getElementById('telefonoUsuario'),
    ID_NIVEL_USUARIO = document.getElementById('idNivelUsuario');

// Constantes para los mensajes de error
const PASSWORD_ERROR = document.getElementById('passwordError');
const CONFIRM_PASSWORD_ERROR = document.getElementById('confirmPasswordError');

const TOGGLE_PASSWORD = document.getElementById('togglePassword');
const TOGGLE_CONFIRM_PASSWORD = document.getElementById('toggleConfirmPassword');

vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefonoUsuario'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});
// Llamada a la función para establecer la mascara del campo DUI.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('DUIUsuario'),
    mask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/]
});

// Función para alternar la visibilidad de la contraseña
const togglePasswordVisibility = (inputField, toggleButton) => {
    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
    inputField.setAttribute('type', type);
    toggleButton.querySelector('i').classList.toggle('fa-eye');
    toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
};

// Evento para mostrar/ocultar la contraseña
TOGGLE_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CLAVE, TOGGLE_PASSWORD));

TOGGLE_CONFIRM_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CONFIRMAR_CLAVE, TOGGLE_CONFIRM_PASSWORD));

// Función para verificar la fortaleza de la contraseña
const isPasswordStrong = (password) => {
    if (password.length < 8) {
        return 'La contraseña debe tener al menos 8 caracteres.';
    }
    
    const hasLowerCase = /[a-z]/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password);
    
    if (!(hasLowerCase && hasUpperCase && hasNumber && hasSpecialChar)) {
        return 'La contraseña debe incluir mayúsculas, minúsculas, números y caracteres especiales.';
    }

    return ''; // Contraseña válida
}

// Función para mostrar mensajes de error
const showError = (element, message) => {
    element.textContent = message;
    element.style.display = 'block';
}

// Función para ocultar mensajes de error
const hideError = (element) => {
    element.textContent = '';
    element.style.display = 'none';
}

// Evento para validar la contraseña mientras se escribe
CLAVE.addEventListener('input', () => {
    const errorMessage = isPasswordStrong(CLAVE.value);
    if (errorMessage) {
        showError(PASSWORD_ERROR, errorMessage);
    } else {
        hideError(PASSWORD_ERROR);
    }
});

// Evento para validar la confirmación de contraseña mientras se escribe
CONFIRMAR_CLAVE.addEventListener('input', () => {
    if (CLAVE.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
    } else {
        hideError(CONFIRM_PASSWORD_ERROR);
    }
});

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);

    CLAVE.setAttribute('type', 'password');
    CONFIRMAR_CLAVE.setAttribute('type', 'password');
    TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();

    const passwordError = isPasswordStrong(CLAVE.value);
    if (passwordError) {
        showError(PASSWORD_ERROR, passwordError);
        return;
    }
    
    if (CLAVE.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
        return;
    }

    (ID_USUARIO.value) ? action = 'updateRow' : action = 'createRow';
    const FORM = new FormData(SAVE_FORM);
    const DATA = await fetchData(USUARIO_API, action, FORM);

    if (DATA.status) {
        SAVE_MODAL.hide();
        sweetAlert(1, DATA.message, true);
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';

    (form) ? action = 'searchRows' : action = 'readAll';
    const DATA = await fetchData(USUARIO_API, action, form);

    if (DATA.status) {
        DATA.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.usuario}</td>
                    <td>${row.nombre}</td>
                    <td>${row.telefono}</td>
                    <td>${row.correo}</td>
                    <td>${row.DUI}</td>
                    <td>${row.tipo_usuario}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_usuario})">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_usuario})">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*/
const openCreate = () => {
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear usuario';
    SAVE_FORM.reset();
    CLAVE.disabled = false;
    fillSelect(USUARIO_API, 'readAllNiveles', 'idNivelUsuario');
    fillTable();

    CLAVE.setAttribute('type', 'password');
    CONFIRMAR_CLAVE.setAttribute('type', 'password');
    TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*/
const openUpdate = async (id) => {
    const FORM = new FormData();
    FORM.append('idUsuario', id);

    try {
        const DATA = await fetchData(USUARIO_API, 'readOne', FORM);

        if (DATA.status) {
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar usuario';
            SAVE_FORM.reset();

            const ROW = DATA.dataset;
            ID_USUARIO.value = ROW.id_usuario;
            USUARIO.value = ROW.usuario;
            CORREO.value = ROW.correo;
            NOMBRE_USUARIO.value = ROW.nombre;
            DUI.value = ROW.DUI;
            TELEFONO.value = ROW.telefono;
            fillSelect(USUARIO_API, 'readAllNiveles', 'idNivelUsuario', ROW.id_nivel_usuario);

            // Se bloquea el campo de contraseña
            CLAVE.disabled = true;
            hideError(PASSWORD_ERROR);
            hideError(CONFIRM_PASSWORD_ERROR);

            CLAVE.setAttribute('type', 'password');
            CONFIRMAR_CLAVE.setAttribute('type', 'password');
            TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
            TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
            TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
            TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');

        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (error) {
        console.error(error);
    }
}

const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el usuario de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idUsuario', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(USUARIO_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    } 
}


const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/usuarios.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

/*
*   Función para abrir un reporte parametrizado de productos de una categoría.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
SAVE_FORM_REPORT.addEventListener('submit', (event) => {
    event.preventDefault();
    openReporte();
});


const openCreateR = () => {
    SAVE_MODAL_REPORT.show();
    MODAL_TITLE_REPORT.textContent = 'Generar reporte';
    SAVE_FORM_REPORT.reset();
    document.getElementById('nivelUsuario').value = nivelUsuario;
}

const openReporte = () => {
    const nivelUsuario = document.getElementById('nivelUsuario').value;
    if (!nivelUsuario) {
        alert('Nivel no disponible.');
        return;
    }
    const PATH = new URL(`${SERVER_URL}reports/admin/usuario_por_nivel.php`);
    PATH.searchParams.append('nivelUsuario', nivelUsuario);
    window.open(PATH.href);
}
