// Constante para completar la ruta de la API de Proveedores.
const PROVEEDOR_API = 'services/admin/admin_maestros_proveedores.php';

// Elementos para el formulario de búsqueda.
const SEARCH_FORM = document.getElementById('searchForm');

// Elementos para la tabla de proveedores.
const TABLE_BODY = document.getElementById('tableBody');
const ROWS_FOUND = document.getElementById('rowsFound');

// Elementos para el formulario de guardar y el modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal');
const MODAL_TITLE = document.getElementById('modalTitle');
const SAVE_FORM = document.getElementById('saveForm');

// Campos del formulario de guardar.
const ID_PROVEEDOR = document.getElementById('idProveedor');
const NOMBRE_PROVEEDOR = document.getElementById('nombreProveedor');
const CODIGO_PROVEEDOR = document.getElementById('codigoProveedor');
const PAIS = document.getElementById('paisProveedor');
const GIRO_NEGOCIO = document.getElementById('giroNegocioProveedor');
const DUI = document.getElementById('duiProveedor');
const NOMBRE_COMERCIAL = document.getElementById('nombreComercialProveedor');
const FECHA = document.getElementById('fechaProveedor');
const NIT = document.getElementById('nitProveedor');
const TELEFONO = document.getElementById('telefonoProveedor');
const CONTACTO = document.getElementById('contactoProveedor');
const DIRECCION = document.getElementById('direccionProveedor');
const DEPARTAMENTO = document.getElementById('departamentoProveedor');
const MUNICIPIO = document.getElementById('municipioProveedor');
CHART_MODAL = new bootstrap.Modal('#chartModal');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    loadTemplate(); // Función para cargar el encabezado y pie de página.
    fillTable(); // Llenar la tabla con los registros existentes al cargar.
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
    event.preventDefault(); // Evitar el comportamiento por defecto del formulario.

    // Determinar la acción a realizar según si existe el ID del proveedor.
    const action = ID_PROVEEDOR.value ? 'updateRow' : 'createRow';

    // Construir los datos del formulario en formato FormData.
    const formData = new FormData(SAVE_FORM);
    
    // Realizar la solicitud para guardar o actualizar el proveedor.
    const responseData = await fetchData(PROVEEDOR_API, action, formData);
    
    // Mostrar mensajes de éxito o error según la respuesta.
    if (responseData.status) {
        // Cerrar el modal de guardar.
        SAVE_MODAL.hide();
        // Mostrar mensaje de éxito.
        sweetAlert(1, responseData.message, true);
        // Volver a llenar la tabla con los datos actualizados.
        fillTable();
    } else {
        // Mostrar mensaje de error.
        sweetAlert(2, responseData.error, false);
    }
});

// Función para llenar la tabla de proveedores.
const fillTable = async () => {
    // Limpiar contenido anterior de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';

    // Determinar la acción a realizar (buscar o leer todos).
    const action = SEARCH_FORM.search.value.trim() ? 'searchRows' : 'readAll';

    // Construir los datos del formulario de búsqueda.
    const formData = new FormData(SEARCH_FORM);

    // Realizar la solicitud para obtener los datos de los proveedores.
    const responseData = await fetchData(PROVEEDOR_API, action, formData);

    // Mostrar los resultados en la tabla.
    if (responseData.status) {
        ROWS_FOUND.textContent = responseData.message;
        responseData.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_proveedor}</td>
                    <td>${row.codigo_proveedor}</td>
                    <td>${row.giro_negocio_proveedor}</td>
                     <td>${row.telefono_proveedor}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_proveedor})">
                        <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_proveedor})">
                        <i class="fa-regular fa-trash-can"></i>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="openReport1(${row.id_proveedor})">
                            <i class="bi bi-filetype-pdf"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        sweetAlert(4, responseData.error, false);
    }
};

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
    const responseData = await fetchData(PROVEEDOR_API, 'readOne', FORM);

    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (responseData.status)
        
    {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar proveedor';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = responseData.dataset;
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
        DIRECCION.value = ROW.direccion_proveedor;
        DEPARTAMENTO.value = ROW.departamento_proveedor;
        MUNICIPIO.value = ROW.municipio_proveedor;
        CONTACTO.value=ROW.contacto_proveedor;
    } else {
        sweetAlert(2, responseData.error, false);
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
        FORM.append('idProveedor', id);
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
*   Función para abrir un reporte parametrizado de productos de una categoría.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openReport = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/proveedores.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('idCliente', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}


const openReport1 = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/proveedorparametrizado.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('idProveedor', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}


const openproveedorchart = async () => {
    // Petición para obtener los datos de los puntos de venta
    const DATA = await fetchData(PROVEEDOR_API, 'getUltimosProveedor', null);

    
    if (DATA.status) {
        // Muestra la caja de diálogo con su título
        const CHART_MODAL = new bootstrap.Modal(document.getElementById('chartModal'));
        CHART_MODAL.show();

        // Declara arreglos para guardar los datos a graficar
        let proveedor = [];
        let idsProveedor = [];

        // Recorre el conjunto de registros fila por fila
        DATA.dataset.forEach(row => {
            proveedor.push(row.nombre_proveedor);
            idsProveedor.push(row.id_proveedor);  // Puedes incluir los IDs si los necesitas
        });

        // Agrega la etiqueta canvas al contenedor del modal
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;

        // Llama a la función para generar y mostrar el gráfico de barras
        pieGraph('chart', proveedor,idsProveedor, 'Últimos 3 Proveedores');
    } else {
        sweetAlert(4, DATA.error, true);
    }
};