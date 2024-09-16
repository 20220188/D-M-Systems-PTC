// Constante para completar la ruta de la API.
const PUNTO_VENTA_API = 'services/admin/admin_maestros_punto_de_venta.php'

// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');

// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');

// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_PUNTO_VENTA = document.getElementById('idPuntoVenta'),
    NOMBRE_PUNTO_VENTA = document.getElementById('nombrePuntoVenta'),
    CLAVE_PUNTO_VENTA = document.getElementById('clavePuntoVenta');
CONFIRMAR_CLAVE = document.getElementById('confirmarClave');

// Constantes para los mensajes de error
const PASSWORD_ERROR = document.getElementById('passwordError');
const CONFIRM_PASSWORD_ERROR = document.getElementById('confirmPasswordError');

const TOGGLE_PASSWORD = document.getElementById('togglePassword');
const TOGGLE_CONFIRM_PASSWORD = document.getElementById('toggleConfirmPassword');

// Función para alternar la visibilidad de la contraseña
const togglePasswordVisibility = (inputField, toggleButton) => {
    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
    inputField.setAttribute('type', type);
    toggleButton.querySelector('i').classList.toggle('fa-eye');
    toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
};

// Evento para mostrar/ocultar la contraseña
TOGGLE_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CLAVE_PUNTO_VENTA, TOGGLE_PASSWORD));

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
CLAVE_PUNTO_VENTA.addEventListener('input', () => {
    const errorMessage = isPasswordStrong(CLAVE_PUNTO_VENTA.value);
    if (errorMessage) {
        showError(PASSWORD_ERROR, errorMessage);
    } else {
        hideError(PASSWORD_ERROR);
    }
});

// Evento para validar la confirmación de contraseña mientras se escribe
CONFIRMAR_CLAVE.addEventListener('input', () => {
    if (CLAVE_PUNTO_VENTA.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
    } else {
        hideError(CONFIRM_PASSWORD_ERROR);
    }
});

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar Puntos de Venta';
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

    CLAVE_PUNTO_VENTA.setAttribute('type', 'password');
    CONFIRMAR_CLAVE.setAttribute('type', 'password');
    TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    const passwordError = isPasswordStrong(CLAVE_PUNTO_VENTA.value);
    if (passwordError) {
        showError(PASSWORD_ERROR, passwordError);
        return;
    }

    if (CLAVE_PUNTO_VENTA.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
        return;
    }
    // Se verifica la acción a realizar.
    (ID_PUNTO_VENTA.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PUNTO_VENTA_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
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
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PUNTO_VENTA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.punto_venta}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_punto_venta})">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_punto_venta})">
                            <i class="fa-solid fa-xmark"></i>
                        </button>                        
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear Punto de Venta';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    CLAVE_PUNTO_VENTA.disabled = false;

    CLAVE_PUNTO_VENTA.setAttribute('type', 'password');
    CONFIRMAR_CLAVE.setAttribute('type', 'password');
    TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
    TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    const FORM = new FormData();
    FORM.append('idPuntoVenta', id);
    const DATA = await fetchData(PUNTO_VENTA_API, 'readOne', FORM);
    if (DATA.status) {
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar Punto de Venta';
        SAVE_FORM.reset();

        const ROW = DATA.dataset;
        ID_PUNTO_VENTA.value = ROW.id_punto_venta; // Asegúrate de que este campo esté oculto
        NOMBRE_PUNTO_VENTA.value = ROW.punto_venta;
        CLAVE_PUNTO_VENTA.value = ROW.clave;
        CONFIRMAR_CLAVE.value = ROW.clave; // Este campo también se debe llenar

        // Se bloquea el campo de contraseña
        CLAVE_PUNTO_VENTA.disabled = true;
        hideError(PASSWORD_ERROR);
        hideError(CONFIRM_PASSWORD_ERROR);

        CLAVE_PUNTO_VENTA.setAttribute('type', 'password');
        CONFIRMAR_CLAVE.setAttribute('type', 'password');
        TOGGLE_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
        TOGGLE_PASSWORD.querySelector('i').classList.add('fa-eye');
        TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.remove('fa-eye-slash');
        TOGGLE_CONFIRM_PASSWORD.querySelector('i').classList.add('fa-eye');
    } else {
        sweetAlert(2, DATA.error, false);
    }
}



/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el punto de venta de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idPuntoVenta', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PUNTO_VENTA_API, 'deleteRow', FORM);
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


const openPuntoVentaChart = async () => {
    // Petición para obtener los datos de los puntos de venta
    const DATA = await fetchData(PUNTO_VENTA_API, 'PuntoVentaGrafico', null);


    if (DATA.status) {
        // Muestra la caja de diálogo con su título
        const CHART_MODAL = new bootstrap.Modal(document.getElementById('chartModal'));
        CHART_MODAL.show();

        // Declara arreglos para guardar los datos a graficar
        let puntosVenta = [];
        let idsPuntoVenta = [];

        // Recorre el conjunto de registros fila por fila
        DATA.dataset.forEach(row => {
            puntosVenta.push(row.punto_venta);
            idsPuntoVenta.push(row.id_punto_venta);  // Puedes incluir los IDs si los necesitas
        });

        // Agrega la etiqueta canvas al contenedor del modal
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;

        // Llama a la función para generar y mostrar el gráfico de barras
        barGraph('chart', puntosVenta, idsPuntoVenta, 'Últimos 3 Puntos de Venta');
    } else {
        sweetAlert(4, DATA.error, true);
    }
};


const openReport = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/PuntoVenta.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('idPuntoVenta', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}


