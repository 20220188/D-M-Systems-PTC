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

    const TABLE_BODY_F = document.getElementById('tableBodyForm'),
    ROWS_FOUND_F = document.getElementById('rowsFoundForm');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
ID_VENTA = document.getElementById('idVenta'),
    FECHA_VENTA = document.getElementById('FechaVenta'),
    ID_VENDEDOR = document.getElementById('Vendedor'),
    ID_CLIENTE = document.getElementById('idCliente'),
    FORMA_PAGO_VENTA = document.getElementById('FormaPagoVenta'),
    DOCUMENTO_VENTA = document.getElementById('DocumentoVenta'),
    TIPO_DOCUMENTO_VENTA = document.getElementById('TipoDocumentoVenta'),
    BODEGA_VENTA = document.getElementById('BodegaVenta'),
    NOTAS_VENTA = document.getElementById('Notasventa');

/*
*Elementos para la tabla DETALLE_VENTA
*/
/*
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY_DETALLE_VENTA = document.getElementById('tableBodyDetalleVenta'),
    ROWS_FOUND_DETALLE_VENTA = document.getElementById('rowsFoundDetalleVenta');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL_DETALLE_VENTA = new bootstrap.Modal('#saveModalDetalleVenta'),
    MODAL_TITLE_DETALLE_VENTA = document.getElementById('modalTitleDetalleVenta');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM_DETALLE_VENTA = document.getElementById('saveFormDetalleVenta'),
    ID_DETALLE_VENTA = document.getElementById('idDetalleVenta'),
    ID_PRODUCTO_DETALLE_VENTA = document.getElementById('idProductoDetalleVenta'),
    CANTIDAD_DETALLE_VENTA = document.getElementById('cantidadDetalleVenta'),
    PRECIO_UNITARIO_VENTA = document.getElementById('precioUnitarioVenta'),
    DESCUENTO_DETALLE_VENTA = document.getElementById('descuentoDetalleVenta'),
    PRECIO_TOTAL_VENTA = document.getElementById('precioTotalVenta');
*/




// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date().toISOString().split('T')[0];
    
    // Asigna la fecha actual al atributo 'min' del input de fecha de venta
    FECHA_VENTA.setAttribute('min', today);
    
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar ventas';
    
 fillTable();
    
});

    // Método del evento para cuando se envía el formulario de buscar

SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_VENTA.value) ? action = 'updateRow' : action = 'createRow';
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
    (form) ? action = 'searchRows' : action = 'readAll';
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

const fillTables = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND_F.textContent = '';
    TABLE_BODY_F.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAllWithPrice';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY_F.innerHTML += `
                <tr>
                    <td>${row.codigo}</td>
                    <td>${row.nombre}</td>
                    <td>${row.presentacion}</td>
                    <td>${row.precio_con_iva}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openDetails(${row.id_venta})">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND_F.textContent = DATA.message;
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

// Función para buscar producto por código
async function buscarProducto(codigo) {
    try {
        const FORM = new FormData();
        FORM.append('action', 'getProductByCode');
        FORM.append('codigoProducto', codigo);
        console.log(MODULO_VENTAS_API);
        console.log(codigo);

        const response = await fetch(MODULO_VENTAS_API, {
            method: 'POST',
            body: FORM
            
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Oops, we haven't got JSON!");
        }

        const DATA = await response.json();
        
        if (DATA.status) {
            return DATA.dataset;
        } else {
            sweetAlert(2, DATA.error || 'Producto no encontrado', false);
            return null;
        }
    } catch (error) {
        console.error('Error:', error);
        sweetAlert(2, `Error al buscar el producto: ${error.message}`, false);
        return null;
    }
}