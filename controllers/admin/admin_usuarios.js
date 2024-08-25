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
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_USUARIO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(USUARIO_API, action, FORM);
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
    const DATA = await fetchData(USUARIO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
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
    MODAL_TITLE.textContent = 'Crear usuario';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    CLAVE.disabled = false;
    fillSelect(USUARIO_API, 'readAllNiveles', 'idNivelUsuario');
    fillTable();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idPuntoVenta', id); // Asegúrate de que el nombre del campo sea correcto.

    try {
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(PUNTO_VENTA_API, 'readOne', FORM);

        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar Punto de Venta';

            // Se prepara el formulario.
            SAVE_FORM.reset();

            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            ID_PUNTO_VENTA.value = ROW.id_punto_venta; // Asegúrate de que estos IDs coincidan con los del HTML.
            NOMBRE_PUNTO_VENTA.value = ROW.punto_venta;
            CLAVE_PUNTO_VENTA.value = ''; // No debes mostrar la clave directamente por razones de seguridad.
            CONFIRMAR_CLAVE.value = ''; // Puedes mantener este campo vacío hasta que el usuario ingrese una nueva clave.

            NOMBRE_PUNTO_VENTA.disabled = false; // Hacer que el campo sea editable.
            CLAVE_PUNTO_VENTA.disabled = false; // Dejar este campo editable para que el usuario ingrese una nueva clave.
            CONFIRMAR_CLAVE.disabled = false; // Igual para confirmar la nueva clave.

        } else {
            sweetAlert(2, DATA.error, false);
        }
    } catch (error) {
        console.error('Error al abrir el modal para actualizar:', error);
        sweetAlert(2, 'Ocurrió un error al cargar los datos', false);
    }
}


/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
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
