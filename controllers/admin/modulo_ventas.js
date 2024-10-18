const MODULO_VENTAS_API = 'services/admin/modulo_ventas.php';
const FORMAS_DE_PAGO_API = 'services/admin/formas_pago.php';
const PRODUCTO_API = 'services/admin/admin_maestro_productos.php';

// Constantes para completar las rutas de la API de VENTA.

const VENDEDOR_API = 'services/admin/admin_maestro_dependientes.php';
const CLIENTE_API = 'services/admin/admin_maestro_cliente.php';

const BODEGA_API = 'services/admin/bodega.php';
const DOCUMENTO_API = 'services/admin/documento.php';
const TIPO_DOCUMENTO_API = 'services/admin/tipo_documento.php';

/*
*Elementos para la tabla VENTAS
*/

// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal(document.getElementById('saveModal')),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm');

// Constantes para establecer los elementos del formulario de detalle de ventas.
const TABLE_BODY_DETALLE = document.getElementById('tableBodyDetalle'),
ROWS_FOUND_DETALLE = document.getElementById('rowsFoundDetalle');

const SAVE_MODAL_DETALLE = new bootstrap.Modal(document.getElementById('saveModalDetalle')),
MODAL_TITLE_DETALLE = document.getElementById('modalTitleDetalle');

const SAVE_FORM_DETALLE = document.getElementById('saveFormDetalle'),

ID_VENTA = document.getElementById('idVenta'),
    FECHA_VENTA = document.getElementById('FechaVenta'),
    ID_VENDEDOR = document.getElementById('Vendedor'),
    ID_CLIENTE = document.getElementById('idCliente'),
    FORMA_PAGO_VENTA = document.getElementById('FormaPagoVenta'),
    DOCUMENTO_VENTA = document.getElementById('DocumentoVenta'),
    TIPO_DOCUMENTO_VENTA = document.getElementById('TipoDocumentoVenta'),
    BODEGA_VENTA = document.getElementById('BodegaVenta'),
    NOTAS_VENTA = document.getElementById('Notasventa'),

    //constantes para el funcionamiento de la tabla detalle
    ID_DETALLE = document.getElementById('idDetalle'),
    ID_VENTA_DETALLE = document.getElementById('idVentaDetalle');


    //constantes para el funcionamiento del modal de detalle
    const NOMBRE_DETALLE = document.getElementById('NombreDetalle');
    const PRESENTACION_DETALLE = document.getElementById('PresentacionDetalle');
    const PRECIO_UNITARIO_DETALLE = document.getElementById('precioUnitarioDetalle');

/*
*Elementos para la tabla DETALLE_VENTA
*/


// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date().toISOString().split('T')[0];
    
    // Asigna la fecha actual al atributo 'min' del input de fecha de venta
    FECHA_VENTA.setAttribute('min', today);
    
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    
    // Se establece el título del contenido principal.
    document.getElementById('mainTitle').textContent = 'Gestionar ventas';
    
 fillTable();
    
});


// Método del evento para cuando se envía el formulario de buscar

SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    const action = (ID_VENTA.value) ? 'updateRow' : 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(MODULO_VENTAS_API, action, FORM);
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
    const action = (form) ? 'searchRows' : 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(MODULO_VENTAS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.fecha_venta}</td>
                    <td>${row.vendedor}</td>
                    <td>${row.cliente}</td>
                    <td>${row.forma_pago}</td>
                    <td>${row.documento}</td>
                    <td>${row.tipo_documento}</td>
                    <td>${row.bodega}</td>
                    <td>${row.notas}</td>
                    <td>
                    <button type="button" class="btn btn-success" onclick="openDetail(${row.id_venta})">
                            <i class="fa-regular fa-square-plus"></i>
                        </button>
                        <button type="button" class="btn btn-info" onclick="openDetails(${row.id_venta})">
                            <i class="fa-regular fa-paper-plane"></i>
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
    MODAL_TITLE.textContent = 'Crear venta';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(FORMAS_DE_PAGO_API, 'readAll', 'FormaPagoVenta');
    fillSelect(DOCUMENTO_API, 'readAll', 'DocumentoVenta');
    fillSelect(BODEGA_API, 'readAll', 'BodegaVenta');
    fillSelect(TIPO_DOCUMENTO_API, 'readAll', 'TipoDocumentoVenta');
    fillSelect(VENDEDOR_API, 'readAll', 'Vendedor');
    fillSelect(CLIENTE_API, 'readAll', 'ClienteVenta');

}

SAVE_FORM_DETALLE.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    const action = (ID_DETALLE.value) ? 'updateRow' : 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_DETALLE);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(MODULO_VENTAS_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL_DETALLE.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTables();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});


const fillTables = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND_DETALLE.textContent = '';
    TABLE_BODY_DETALLE.innerHTML = '';
    // Se verifica la acción a realizar.
    const action = (form) ? 'searchRows' : 'readAllWithPrice';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY_DETALLE.innerHTML += `
                <tr>
                    <td>${row.codigo}</td>
                    <td>${row.nombre}</td>
                    <td>${row.presentacion}</td>
                    <td>${row.precio_con_iva}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openDetails(${row.id_venta})">
                            <i class="fa-solid fa-info-circle"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND_DETALLE.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.exception, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un detalle.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openDetail = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_DETALLE.show();
    MODAL_TITLE_DETALLE.textContent = 'Añadir productos a la venta';
    // Se prepara el formulario.
    SAVE_FORM_DETALLE.reset();
    fillTables();
}

//Funcion para autocompletar la infmormacion del producto a partir del codigo
document.getElementById('codigoDetalle').addEventListener('input', async function() {
    const codigo = this.value;
    if (codigo) {
        const FORM = new FormData();
        FORM.append('codigo', codigo);
        const DATA = await fetchData(MODULO_VENTAS_API, 'searchByCode', FORM);
        if (DATA.status) {
            const producto = DATA.dataset[0];
            NOMBRE_DETALLE.value = producto.nombre;
            PRESENTACION_DETALLE.value = producto.presentacion;
            PRECIO_UNITARIO_DETALLE.value = producto.precio_con_iva;
        } else {
            // Limpiar los campos si no se encuentra el producto
            NOMBRE_DETALLE.value = '';
            PRESENTACION_DETALLE.value = '';
            PRECIO_UNITARIO_DETALLE.value = '';
        }
    }
});

