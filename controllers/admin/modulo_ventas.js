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
    FECHA_VENTA.value = new Date().toISOString().split('T')[0];
    fillSelect(BODEGA_API, 'readAll', 'BodegaVenta');
    fillSelect(VENDEDOR_API, 'readAll', 'Vendedor');
    fillSelect(CLIENTE_API, 'readAll', 'ClienteVenta');
}






SAVE_FORM_DETALLE.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    // Guardamos el ID de la venta antes del reset
    const currentVentaId = ID_VENTA_DETALLE.value;
    
    const action = ID_DETALLE.value ? 'updateRowDetalle' : 'createDetalleVenta';
    
    const FORM = new FormData(SAVE_FORM_DETALLE);
    // Aseguramos que el ID de la venta está en el FormData
    FORM.append('idVentaDetalle', currentVentaId);
    
    const DATA = await fetchData(MODULO_VENTAS_API, action, FORM);
    if (DATA.status) {
        sweetAlert(1, DATA.message, true);
        await fillTableDetalle(currentVentaId);
        
        if (action === 'createDetalleVenta') {
            // Guardamos el ID actual
            const ventaId = currentVentaId;
            // Reseteamos el formulario
            SAVE_FORM_DETALLE.reset();
            // Restauramos el ID después del reset
            ID_VENTA_DETALLE.value = ventaId;
            document.getElementById('idVentaDetalle').value = ventaId;
        }
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

const fillTableDetalle = async (id) => {
    try {
        let subtotal = 0;
        let total = 0;
        const FORM = new FormData();
        FORM.append('idVentaDetalle', id);
        const DATA = await fetchData(MODULO_VENTAS_API, 'readDetalleVenta', FORM);
        
        TABLE_BODY_DETALLE.innerHTML = '';
        
        if (DATA.status && DATA.dataset && DATA.dataset.length > 0) {
            // Crear un objeto para agrupar productos por código
            const productosAgrupados = DATA.dataset.reduce((acc, row) => {
                const key = row.codigo;
                if (!acc[key]) {
                    acc[key] = {
                        codigo: row.codigo,
                        nombre: row.nombre,
                        cantidad: 0,
                        precio_con_iva: parseFloat(row.precio_con_iva),
                        id_detalles: [], // Array para guardar todos los id_detalle_venta
                    };
                }
                acc[key].cantidad += parseInt(row.cantidad);
                acc[key].id_detalles.push(row.id_detalle_venta);
                return acc;
            }, {});

            // Convertir el objeto agrupado en array y generar las filas
            Object.values(productosAgrupados).forEach(producto => {
                subtotal = producto.precio_con_iva * producto.cantidad;
                total += subtotal;

                TABLE_BODY_DETALLE.innerHTML += `
                    <tr>
                        <td>${producto.codigo}</td>
                        <td>${producto.nombre}</td>
                        <td>${producto.cantidad}</td>
                        <td>${producto.precio_con_iva.toFixed(2)}</td>
                        <td>${subtotal.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-info" onclick="openUpdateDetailGroup('${producto.codigo}', ${id})">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="openDeleteDetailGroup('${producto.codigo}', ${id})">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            document.getElementById('pago').textContent = total.toFixed(2);
            ROWS_FOUND_DETALLE.textContent = 'Productos agrupados: ' + Object.keys(productosAgrupados).length;
        } else {
            TABLE_BODY_DETALLE.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No hay productos en el detalle</td>
                </tr>
            `;
            document.getElementById('pago').textContent = '0.00';
            ROWS_FOUND_DETALLE.textContent = 'No hay productos registrados';
        }
    } catch (error) {
        console.error('Error al cargar detalles:', error);
        sweetAlert(2, 'Error al cargar los detalles de la venta', false);
    }
};

// Nueva función para actualizar grupo de productos
const openUpdateDetailGroup = async (codigo, idVenta) => {
    try {
        const FORM = new FormData();
        FORM.append('codigo', codigo);
        FORM.append('idVenta', idVenta);
        const DATA = await fetchData(MODULO_VENTAS_API, 'readDetallesByCode', FORM);

        if (DATA.status) {
            SAVE_MODAL_DETALLE.show();
            MODAL_TITLE_DETALLE.textContent = 'Actualizar cantidad del producto';
            SAVE_FORM_DETALLE.reset();
            
            const producto = DATA.dataset[0]; // Tomamos el primer registro para los datos básicos
            const cantidadTotal = DATA.dataset.reduce((sum, item) => sum + parseInt(item.cantidad), 0);
            
            // Guardamos todos los IDs de detalle en un campo oculto
            const detalleIds = DATA.dataset.map(item => item.id_detalle_venta).join(',');
            ID_DETALLE.value = detalleIds;
            
            ID_VENTA_DETALLE.value = idVenta;
            document.getElementById('codigoDetalle').value = codigo;
            NOMBRE_DETALLE.value = producto.nombre;
            PRESENTACION_DETALLE.value = producto.presentacion;
            PRECIO_UNITARIO_DETALLE.value = producto.precio_con_iva;
            document.getElementById('cantidadDetalle').value = cantidadTotal;
        }
    } catch (error) {
        console.error('Error:', error);
        sweetAlert(2, 'Error al cargar los detalles del producto', false);
    }
};

// Nueva función para eliminar grupo de productos
const openDeleteDetailGroup = async (codigo, idVenta) => {
    try {
        const RESPONSE = await confirmAction('¿Desea eliminar todos los registros de este producto?');
        if (RESPONSE) {
            const FORM = new FormData();
            FORM.append('codigo', codigo);
            FORM.append('idVenta', idVenta);
            
            const DATA = await fetchData(MODULO_VENTAS_API, 'deleteDetallesByCode', FORM);
            if (DATA.status) {
                await sweetAlert(1, DATA.message, true);
                await fillTableDetalle(idVenta);
            } else {
                sweetAlert(2, DATA.error || DATA.exception, false);
            }
        }
    } catch (error) {
        console.error('Error al eliminar:', error);
        sweetAlert(2, 'Ocurrió un error al procesar la solicitud', false);
    }
};




const openDetail = (idVenta) => {
    // Guardamos el ID de la venta actual
    ID_VENTA_DETALLE.value = idVenta;

    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_DETALLE.show();
    MODAL_TITLE_DETALLE.textContent = 'Añadir productos a la venta';

    // Se prepara el formulario pero manteniendo el ID de la venta
    const currentVentaId = ID_VENTA_DETALLE.value; // Guardamos el ID actual
    SAVE_FORM_DETALLE.reset();
    ID_VENTA_DETALLE.value = currentVentaId; // Restauramos el ID después del reset
    
    // Asignamos el ID de la venta al campo oculto
    document.getElementById('idVentaDetalle').value = idVenta;

    // Evento para autocompletar la información del producto a partir del código
    document.getElementById('codigoDetalle').addEventListener('keypress', async function(event) {
        const codigo = this.value;
        if (event.key === 'Enter') {
            const FORM = new FormData();
            FORM.append('codigo', codigo);
            const DATA = await fetchData(MODULO_VENTAS_API, 'searchByCode', FORM);
            if (DATA.status) {
                const producto = DATA.dataset;
                document.getElementById('NombreDetalle').value = producto.nombre;
                document.getElementById('PresentacionDetalle').value = producto.presentacion;
                document.getElementById('precioUnitarioDetalle').value = producto.precio_con_iva;
            } else {
                sweetAlert(2, DATA.error, false);
            }
        }
    });

    // Llamamos a fillTableDetalle para cargar los detalles existentes
    fillTableDetalle(idVenta);
}

const openUpdateDetail = async (idDetalle) => {
    const FORM = new FormData();
    FORM.append('idDetalle', idDetalle);
    const responseData = await fetchData(MODULO_VENTAS_API, 'readOneDetalle', FORM);

    if (responseData.status) {
        SAVE_MODAL_DETALLE.show();
        MODAL_TITLE_DETALLE.textContent = 'Actualizar producto de la venta';
        SAVE_FORM_DETALLE.reset();
        
        const ROW = responseData.dataset;
        ID_DETALLE.value = ROW.id_detalle_venta;
        ID_VENTA_DETALLE.value = ROW.id_venta; // Asegúrate de guardar el ID de la venta
        NOMBRE_DETALLE.value = ROW.nombre;
        document.getElementById('codigoDetalle').value = ROW.codigo;
        PRESENTACION_DETALLE.value = ROW.presentacion;
        PRECIO_UNITARIO_DETALLE.value = ROW.precio_con_iva;
        document.getElementById('cantidadDetalle').value = ROW.cantidad;    
    } else {
        sweetAlert(2, responseData.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDeleteDetail = async (idDetalle, idVenta) => {
    try {
        const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
        if (RESPONSE) {
            const FORM = new FormData();
            FORM.append('idDetalle', idDetalle);
            FORM.append('idVenta', idVenta);
            
            const DATA = await fetchData(MODULO_VENTAS_API, 'deleteRowDetalle', FORM);
            if (DATA.status) {
                await sweetAlert(1, DATA.message, true);
                // Forzamos una actualización inmediata de la tabla
                await fillTableDetalle(idVenta);
            } else {
                sweetAlert(2, DATA.error || DATA.exception, false);
            }
        }
    } catch (error) {
        console.error('Error al eliminar:', error);
        sweetAlert(2, 'Ocurrió un error al procesar la solicitud', false);
    }
};