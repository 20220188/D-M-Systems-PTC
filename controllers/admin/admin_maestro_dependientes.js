// Constantes para completar las rutas de la API de PRODUCTO.
const DEPENDIENTE_API = 'services/admin/admin_maestro_dependientes.php';


/*
*Elementos para la tabla PRODUCTOS
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
    ID_DEPENDIENTE = document.getElementById('idDependiente'),
    NOMBRE_DEPENDIENTE = document.getElementById('nombreDependiente'),
    CODIGO_DEPENDIENTE = document.getElementById('codigoDependiente');
    CHART_MODAL = new bootstrap.Modal('#chartModal');


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar dependiente';
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
    (ID_DEPENDIENTE.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(DEPENDIENTE_API, action, FORM);
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
    const DATA = await fetchData(DEPENDIENTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.codigo}</td>
                    <td>${row.nombre_dependiente}</td>                    
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_dependiente})">
                        <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_dependiente})">
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
    MODAL_TITLE.textContent = 'Crear dependiente';
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
    FORM.append('idDependiente', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(DEPENDIENTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar Dependiente';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_DEPENDIENTE.value = ROW.id_dependiente;
        NOMBRE_DEPENDIENTE.value = ROW.nombre_dependiente;
        CODIGO_DEPENDIENTE.value = ROW.codigo;
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
    const RESPONSE = await confirmAction('¿Desea eliminar el dependiente de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idDependiente', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(DEPENDIENTE_API, 'deleteRow', FORM);
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
// Función para abrir un reporte automático de dependientes.
// Parámetros: ninguno.
// Retorno: ninguno.
const openDependientesReporte = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/dependientes.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}



const graficoDependientesRecientes = async () => {
    // Petición para obtener los datos de los puntos de venta
    const DATA = await fetchData(DEPENDIENTE_API, 'getUltimosDependientes1', null);

    
    if (DATA.status) {
        // Muestra la caja de diálogo con su título
        const CHART_MODAL = new bootstrap.Modal(document.getElementById('chartModal'));
        CHART_MODAL.show();

        // Declara arreglos para guardar los datos a graficar
        let nombre_dependiente = [];
        let idsdependiente = [];

        // Recorre el conjunto de registros fila por fila
        DATA.dataset.forEach(row => {
            nombre_dependiente.push(row.nombre_dependiente);
            idsdependiente.push(row.id_dependiente);  // Puedes incluir los IDs si los necesitas
        });

        // Agrega la etiqueta canvas al contenedor del modal
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;

        // Llama a la función para generar y mostrar el gráfico de barras
        barGraph('chart', nombre_dependiente,idsdependiente, 'Últimos 3 dependientes creados');
    } else {
        sweetAlert(4, DATA.error, true);
    }
};

