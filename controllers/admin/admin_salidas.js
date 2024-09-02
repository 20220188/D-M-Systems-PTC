const SALIDAS_API = 'services/admin/admin_salida.php';
const CLIENTE_API = 'services/admin/admin_maestro_cliente.php';
const DEPENDIENTE_API = 'services/admin/admin_maestro_dependientes.php';

// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');

const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');

const SAVE_FORM_REPORT = document.getElementById('saveFormReport')

const SAVE_MODAL_REPORT = new bootstrap.Modal('#saveModalReport'),
    MODAL_TITLE_REPORT = document.getElementById('modalTitleReport');



const SAVE_FORM = document.getElementById('saveForm'),
    ID_SALIDA = document.getElementById('idSalida'),
    NUMERO_SALIDA = document.getElementById('numeroSalida'),
    TIPO_SALIDA = document.getElementById('tipoSalida'),
    CANTIDAD = document.getElementById('cantidadSalida'),
    ENTREGA_SALIDA = document.getElementById('entregaSalida'),
    FECHA_SALIDA = document.getElementById('fechaSalida'),
    ID_CLIENTE = document.getElementById('cliente'),
    ID_DEPENDIENTE = document.getElementById('Dependiente'),
    NOTA_SALIDA = document.getElementById('notaSalida');

    CB_FILTRO = document.getElementById('filtro');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar salidas';
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
    (ID_SALIDA.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(SALIDAS_API, action, FORM);
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
    const DATA = await fetchData(SALIDAS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.numero_salida}</td>
                    <td>${row.tipo_salida}</td>
                    <td>${row.cantidad_salida}</td>
                    <td>${row.fecha}</td>
                    <td>${row.nombre}</td>
                    <td>${row.nombre_dependiente}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_salida})">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_salida})">
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
    MODAL_TITLE.textContent = 'Crear salida';
    // Se prepara el formulario.
    SAVE_FORM.reset();


    fillSelect(CLIENTE_API, 'readAll', 'cliente');
    fillSelect(DEPENDIENTE_API, 'readAll', 'Dependiente');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idSalida', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(SALIDAS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar salida';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_SALIDA.value = ROW.id_salida;
        NUMERO_SALIDA.value = ROW.numero_salida;
        TIPO_SALIDA.value = ROW.tipo_salida;
        CANTIDAD.value = ROW.cantidad_salida;
        ENTREGA_SALIDA.value = ROW.entrega;
        FECHA_SALIDA.value = ROW.fecha;
        ID_CLIENTE.value = ROW.id_cliente;
        ID_DEPENDIENTE.value = ROW.id_dependiente;
        NOTA_SALIDA.value = ROW.nota;

        fillSelect(CLIENTE_API, 'readAll', 'cliente', ROW.id_cliente);
        fillSelect(DEPENDIENTE_API, 'readAll', 'Dependiente', ROW.id_dependiente);
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
    const RESPONSE = await confirmAction('¿Desea eliminar la salida de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idSalida', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(SALIDAS_API, 'deleteRow', FORM);
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
*   Función para abrir un reporte parametrizado de productos de una categoría.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
SAVE_FORM_REPORT.addEventListener('submit', (event) => {
    event.preventDefault();
    openReport();
});


const openCreateR = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_REPORT.show();
    MODAL_TITLE_REPORT.textContent = 'Generar reporte';
    // Se prepara el formulario.
    SAVE_FORM_REPORT.reset();

// Establece el número de salida en el campo oculto.
document.getElementById('numeroSalidaReport').value = numeroSalida;

}

const openReport = () => {
    // Obtén el número de salida del campo oculto
    const numeroSalida = document.getElementById('numeroSalidaReport').value;
    // Asegúrate de que el valor esté disponible
    if (!numeroSalida) {
        alert('Número de salida no disponible.');
        return;
    }
    // Declara la constante tipo objeto con la ruta específica del reporte en el servidor
    const PATH = new URL(`${SERVER_URL}reports/admin/salidas_numerosalida.php`);
    // Agrega un parámetro a la ruta con el valor del número de salida
    PATH.searchParams.append('numeroSalida', numeroSalida);
    // Abre el reporte en una nueva pestaña
    window.open(PATH.href);
}