// Constantes para completar las rutas de la API de PRODUCTO.
const PRODUCTO_API = 'services/admin/admin_maestro_productos.php';
const LABORATORIO_API = 'services/admin/admin_maestro_laboratorios.php';


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
    ID_PRODUCTO = document.getElementById('idProducto'),
    NOMBRE_PRODUCTO = document.getElementById('nombreProducto'),
    DESCRIPCION_PRODUCTO = document.getElementById('descripcionProducto'),
    CODIGO_PRODUCTO = document.getElementById('codigoProducto'),
    FECHA_VENCIMIENTO = document.getElementById('fechaVencimiento');
    
/*
*Elementos para la tabla DETALLE_PRODUCTO
*/

// Constantes para establecer el contenido de la tabla.
const TABLE_BODY_DETALLE = document.getElementById('tableBodyDetalle'),
    ROWS_FOUND_DETALLE = document.getElementById('rowsFoundDetalle');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL_DETALLE = new bootstrap.Modal('#saveModalDetalle'),
    MODAL_TITLE_DETALLE = document.getElementById('modalTitleDetalle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM_DETALLE = document.getElementById('saveFormDetalle'),
    ID_DETALLE = document.getElementById('idDetalle'),
    ID_PRODUCTO_DETALLE = document.getElementById('idProductoDetalle');
    PRESENTACION_DETALLE = document.getElementById('presentacionDetalle'),
    UBICACION_DETALLE = document.getElementById('ubicacionDetalle'),
    EXISTENCIAS_DETALLE = document.getElementById('existenciaDetalle'),
    MINIMO_DETALLE = document.getElementById('minimoDetalle'),
    MAXIMO_DETALLE = document.getElementById('maximoDetalle'),
    MARCA_DETALLE = document.getElementById('marcaDetalle'),
    FECHA_INGRESO_DETALLE = document.getElementById('fechaIngresoDetalle'),
    PERIODO_EXISTENCIA_DETALLE = document.getElementById('periodoEistenciaDetalle'),
    PRECIO_SIN_IVA = document.getElementById('precioSinIVA'),
    PRECIO_CON_IVA = document.getElementById('precioVentaConIva'),
    PRECIO_UNITARIO = document.getElementById('precioUnitario');
    DESCUENTO_DETALLE = document.getElementById('descuentoDetalle'),
    PRECIO_DESCUENTO_DETALLE = document.getElementById('precioDescDetalle'),
    PRECIO_OPCIONAL1_DETALLE = document.getElementById('precioOpc1Detalle'),
    PRECIO_OPCIONAL2_DETALLE = document.getElementById('precioOpc2Detalle'),
    PRECIO_OPCIONAL3_DETALLE = document.getElementById('precioOpc3Detalle'),
    PRECIO_OPCIONAL4_DETALLE = document.getElementById('precioOpc4Detalle');



// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {

    const today = new Date().toISOString().split('T')[0];
    
    // Asigna la fecha actual al atributo 'min' del input de fecha
    const FECHA_VENCIMIENTO = document.getElementById('fechaVencimiento');
    FECHA_VENCIMIENTO.setAttribute('min', today);
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar productos';
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
    (ID_PRODUCTO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PRODUCTO_API, action, FORM);
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
    const DATA = await fetchData(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td><img src="${SERVER_URL}images/productos/${row.imagen}" height="50"></td>
                    <td>${row.codigo}</td>
                    <td>${row.nombre}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.fecha_vencimiento}</td>
                    <td>${row.presentacion}</td>
                    <td>
                        <button type="button" class="btn btn-success" onclick="openDetails(${row.id_producto})">
                        <i class="fa-regular fa-square-plus"></i>
                        </button>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_producto})">
                        <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_producto})">
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
    MODAL_TITLE.textContent = 'Crear producto';
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
    FORM.append('idProducto', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar producto';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_PRODUCTO.value = ROW.id_producto;
        NOMBRE_PRODUCTO.value = ROW.nombre;
        DESCRIPCION_PRODUCTO.value = ROW.descripcion;
        CODIGO_PRODUCTO.value = ROW.codigo;
        FECHA_VENCIMIENTO.value = ROW.fecha_vencimiento;
        PRESENTACION_DETALLE.value = ROW.presentacion;
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
    const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idProducto', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PRODUCTO_API, 'deleteRow', FORM);
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
*   Funciones para la tabla DETALLE_PRODUCTO
*/

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM_DETALLE.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_DETALLE.value) ? action = 'updateRowDetalleProducto' : action = 'createRowDetalleProducto';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_DETALLE);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PRODUCTO_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        //SAVE_MODAL_DETALLE.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTableDetails(ID_PRODUCTO_DETALLE.value);
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTableDetails = async (id) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND_DETALLE.textContent = '';
    TABLE_BODY_DETALLE.innerHTML = '';
    const FORM = new FormData();
    FORM.append('idProducto', id);
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTO_API, 'readAllDetalle', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY_DETALLE.innerHTML += `
                <tr>
                    <td>${row.marca}</td>
                    <td>${row.ubicacion}</td>
                    <td>${row.minimo}</td>
                    <td>${row.maximo}</td>
                    <td>${row.fecha}</td>
                    <td>${row.existencia}</td>
                    <td>${row.periodo_existencia}</td>
                    <td>${row.nombre_laboratorio}</td>
                    <td>${row.precio_con_iva}</td>
                    <td>${row.costo_unitario}</td>
                    <td>${row.precio_con_descuento}</td>   
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdateDetails(${row.id_detalle_producto})">
                        <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDeleteDetails(${row.id_detalle_producto},${id})">
                        <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND_DETALLE.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openDetails = (id_producto) => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_DETALLE.show();
    MODAL_TITLE_DETALLE.textContent = 'Detalle producto';
    // Se prepara el formulario.
    SAVE_FORM_DETALLE.reset();

    ID_PRODUCTO_DETALLE.value = id_producto;
    fillSelect(LABORATORIO_API, 'readAll', 'laboratorioDetalle');

    fillTableDetails(id_producto);
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdateDetails = async (id1) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM_DETALLE = new FormData();
    FORM_DETALLE.append('idDetalle', id1);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(PRODUCTO_API, 'readOneDetalleProducto', FORM_DETALLE);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL_DETALLE.show();
        MODAL_TITLE_DETALLE.textContent = 'Actualizar detalle producto';
        // Se prepara el formulario.
        SAVE_FORM_DETALLE.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_DETALLE.value = ROW.id_detalle_producto;
        ID_PRODUCTO_DETALLE.value = ROW.id_producto;
        UBICACION_DETALLE.value = ROW.ubicacion;
        MINIMO_DETALLE.value = ROW.minimo;
        MAXIMO_DETALLE.value = ROW.maximo;
        MARCA_DETALLE.value = ROW.marca;
        PERIODO_EXISTENCIA_DETALLE.value = ROW.periodo_existencia;
        EXISTENCIAS_DETALLE.value = ROW.existencia;
        FECHA_INGRESO_DETALLE.value = ROW.fecha;
        PRECIO_SIN_IVA.value = ROW.precio_sin_iva;
        PRECIO_CON_IVA.value = ROW.precio_con_iva;
        PRECIO_UNITARIO.value = ROW.costo_unitario;
        DESCUENTO_DETALLE.value = ROW.descuento;
        PRECIO_DESCUENTO_DETALLE.value = ROW.precio_con_descuento;
        PRECIO_OPCIONAL1_DETALLE.value = ROW.precio_opcional1;
        PRECIO_OPCIONAL2_DETALLE.value = ROW.precio_opcional2;
        PRECIO_OPCIONAL3_DETALLE.value = ROW.precio_opcional3;
        PRECIO_OPCIONAL4_DETALLE.value = ROW.precio_opcional4;

        
        fillSelect(LABORATORIO_API, 'readAll', 'laboratorioDetalle', ROW.id_laboratorio); 

    } else {
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDeleteDetails = async (idDetalle, idProducto) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idDetalle', idDetalle);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PRODUCTO_API, 'deleteRowDetalleProducto', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTableDetails(idProducto);
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}


/*
*   Función para abrir un reporte automático de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/admin/productos.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}
const openproveedorchart = async () => {
    // Petición para obtener los datos de los puntos de venta
    const DATA = await fetchData(PRODUCTO_API, 'getProductosConMasExistencias', null);

    
    if (DATA.status) {
        // Muestra la caja de diálogo con su título
        const CHART_MODAL = new bootstrap.Modal(document.getElementById('chartModal'));
        CHART_MODAL.show();

        // Declara arreglos para guardar los datos a graficar
        let producto = [];
        let existencia1 = [];

        // Recorre el conjunto de registros fila por fila
        DATA.dataset.forEach(row => {
            producto.push(row.nombre);
            existencia1.push(row.existencia);

        });

        // Agrega la etiqueta canvas al contenedor del modal
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;

        // Llama a la función para generar y mostrar el gráfico de barras
        pieGraph('chart', producto, existencia1,'Top 5 Productos con más existencias');
    } else {
        sweetAlert(4, DATA.error, true);
    }
};