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
    loadTemplate(); // Función para cargar el encabezado y pie de página.
    fillTable(); // Llenar la tabla con los registros existentes al cargar.
});

// Método del evento para cuando se envía el formulario de búsqueda.
SEARCH_FORM.addEventListener('submit', (event) => {
    event.preventDefault(); // Evitar el comportamiento por defecto del formulario.
    fillTable(); // Llenar la tabla con los resultados de la búsqueda.
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
        responseData.dataset.forEach(proveedor => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${proveedor.nombre}</td>
                    <td>${proveedor.codigo}</td>
                    <td>${proveedor.giro_negocio}</td>
                    <td>
                        <button class="btn btn-info" onclick="openUpdate(${proveedor.id_proveedor})">Editar</button>
                        <button class="btn btn-danger" onclick="openDelete(${proveedor.id_proveedor})">Eliminar</button>
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
    FORM.append('idLab', id);
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
        NOMBRE_PROVEEDOR.value = ROW.nombre;
        CODIGO_PROVEEDOR.value = ROW.codigo;
        PAIS.value = ROW.pais;
        GIRO_NEGOCIO.value = ROW.giro_negocio;
        DUI.value = ROW.dui;
        NOMBRE_COMERCIAL.value = ROW.nombre_comercial;
        NIT.value = ROW.nit;
        FECHA.value =ROW.fecha_registro;
        TELEFONO.value = ROW.telefono;
        CONTACTO.value = ROW.contacto;
        DIRECCION.value = ROW.direccion;
        DEPARTAMENTO.value = ROW.departamento;
        MUNICIPIO.value = ROW.municipio;
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
          
