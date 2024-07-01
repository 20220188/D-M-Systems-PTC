// Constantes para completar las rutas de la API de PROVEEDOR
const PROVEEDOR_API = 'services/admin/admin_maestros_proveedores.php';

/*
*Elementos para la tabla proveedorS
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
const ID_PROVEEDOR = document.getElementById('idProveedor');
const NOMBRE_PROVEEDOR = document.getElementById('nombreProveedor');
const CODIGO_PROVEEDOR = document.getElementById('codigoProveedor');
const PAIS = document.getElementById('paisProveedor');
const GIRO_NEGOCIO = document.getElementById('giroProveedor');
const DUI = document.getElementById('duiProveedor');
const NOMBRE_COMERCIAL = document.getElementById('nombreComercialProveedor');
const FECHA = document.getElementById('fechaProveedor');
const NIT = document.getElementById('nitProveedor');
const TELEFONO = document.getElementById('telefonoProveedor');
const CONTACTO = document.getElementById('contactoProveedor');
const DIRECCION = document.getElementById('direccionProveedor');
const DEPARTAMENTO = document.getElementById('departamentoProveedor');
const MUNICIPIO = document.getElementById('municipioProveedor');



// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar proveedors';
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
    (ID_proveedor.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PROVEEDOR_API, action, FORM);
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
    const DATA = await fetchData(PROVEEDOR_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.codigo_proveedor}</td>
                    <td>${row.nombre_proveedor}</td>
                    <td>${row.giro_negocio_proveedor}</td>
                    <td>${row.telefono_proveedor}</td>
                    <td>
                    
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_proveedor})">
                        <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_proveedor})">
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
    MODAL_TITLE.textContent = 'Crear proveedor';
    // Se prepara el formulario.
    SAVE_FORM.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idProveedor', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(PROVEEDOR_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar proveedor';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_PROVEEDOR.value = ROW.id_proveedor;
        NOMBRE_PROVEEDOR.value = ROW.nombre_proveedor;
        CODIGO_PROVEEDOR.value = ROW.codigo_proveedor;
        PAIS.value = ROW.pais_proveedor;
        GIRO_NEGOCIO.value = ROW.giro_negocio_proveedor;
        DUI.value = ROW.dui_proveedor;
        NOMBRE_COMERCIAL.value = ROW.nombre_comercial_proveedor;
        NIT.value = ROW.nit_proveedor;
        FECHA.value =ROW.fecha_proveedor;
        TELEFONO.value = ROW.telefono_proveedor;
        CONTACTO.value = ROW.contacto_proveedor;
        DIRECCION.value = ROW.direccion_proveedor;
        DEPARTAMENTO.value = ROW.departamento_proveedor;
        MUNICIPIO.value = ROW.municipio_proveedor;
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
    const RESPONSE = await confirmAction('¿Desea eliminar el proveedor de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idproveedor', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PROVEEDOR_API, 'deleteRow', FORM);
        console.log(DATA);
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





/*
*   Función para abrir un reporte automático de proveedors por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/proveedors.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}