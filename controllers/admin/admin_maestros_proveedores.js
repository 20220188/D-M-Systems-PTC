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
const ID_PROVEEDOR = document.getElementById('inputCodigo');
const NOMBRE_PROVEEDOR = document.getElementById('inputNombre');
const PAIS = document.getElementById('inputPais');
const GIRO_NEGOCIO = document.getElementById('inputnegocio');
const DUI = document.getElementById('inputDUI');
const NOMBRE_COMERCIAL = document.getElementById('inputNomCo');
const FECHA = document.getElementById('inputFecha');
const NIT = document.getElementById('inputNIT');
const TELEFONO = document.getElementById('inputTel');
const CONTACTO = document.getElementById('inputContacto');
const DIRECCION = document.getElementById('inputDireccion');
const DEPARTAMENTO = document.getElementById('inputDep');
const MUNICIPIO = document.getElementById('inputMunicipio');

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
                    <td>${proveedor.codigo}</td>
                    <td>${proveedor.nombre}</td>
                    <td>${proveedor.pais}</td>
                    <td>${proveedor.giro_negocio}</td>
                    <td>${proveedor.dui}</td>
                    <td>${proveedor.nombre_comercial}</td>
                    <td>${proveedor.fecha}</td>
                    <td>${proveedor.nit}</td>
                    <td>${proveedor.telefono}</td>
                    <td>${proveedor.contacto}</td>
                    <td>${proveedor.direccion}</td>
                    <td>${proveedor.departamento}</td>
                    <td>${proveedor.municipio}</td>
                    <td>
                        <button class="btn btn-info" onclick="openUpdate(${proveedor.id})">Editar</button>
                        <button class="btn btn-danger" onclick="openDelete(${proveedor.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
    } else {
        sweetAlert(4, responseData.error, false);
    }
};

// Función para preparar el formulario al momento de insertar un nuevo proveedor.
const openCreate = () => {
    SAVE_MODAL.show(); // Mostrar el modal de guardar.
    MODAL_TITLE.textContent = 'Crear Proveedor'; // Establecer título del modal.
    SAVE_FORM.reset(); // Limpiar el formulario de guardar.
};

// Función para preparar el formulario al momento de editar un proveedor existente.
const openUpdate = async (id) => {
    const formData = new FormData();
    formData.append('idProveedor', id);
    
    // Realizar la solicitud para obtener los datos del proveedor seleccionado.
    const responseData = await fetchData(PROVEEDOR_API, 'readOne', formData);
    
    // Mostrar los datos del proveedor en el formulario de guardar.
    if (responseData.status) {
        SAVE_MODAL.show(); // Mostrar el modal de guardar.
        MODAL_TITLE.textContent = 'Editar Proveedor'; // Establecer título del modal.

        // Llenar los campos del formulario con los datos del proveedor.
        const proveedor = responseData.dataset;
        ID_PROVEEDOR.value = proveedor.codigo;
        NOMBRE_PROVEEDOR.value = proveedor.nombre;
        PAIS.value = proveedor.pais;
        GIRO_NEGOCIO.value = proveedor.giro_negocio;
        DUI.value = proveedor.dui;
        NOMBRE_COMERCIAL.value = proveedor.nombre_comercial;
        FECHA.value = proveedor.fecha;
        NIT.value = proveedor.nit;
        TELEFONO.value = proveedor.telefono;
        CONTACTO.value = proveedor.contacto;
        DIRECCION.value = proveedor.direccion;
        DEPARTAMENTO.value = proveedor.departamento;
        MUNICIPIO.value = proveedor.municipio;
    } else {
        sweetAlert(2, responseData.error, false);
    }
};

// Función para eliminar un proveedor.
const openDelete = async (id) => {
    // Confirmar acción con el usuario.
    const confirmAction = await confirm('¿Estás seguro de eliminar este proveedor?');

    if (confirmAction) {
        // Construir los datos del formulario en formato FormData.
        const formData = new FormData();
        formData.append('idProveedor', id);

        // Realizar la solicitud para eliminar el proveedor.
        const responseData = await fetchData(PROVEEDOR_API, 'deleteRow', formData);

        // Mostrar mensaje de éxito o error según la respuesta.
        if (responseData.status) {
            sweetAlert(1, responseData.message, true);
            fillTable(); // Actualizar la tabla después de eliminar.
        } else {
            sweetAlert(2, responseData.error, false);
        }
    }
};

// Función para realizar peticiones fetch al servidor.
const fetchData = async (url, action, formData) => {
    try {
        const response = await fetch(`${url}?action=${action}`, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        sweetAlert(3, 'Error en la solicitud', false);
        return { status: 0, error: 'Error en la solicitud' };
    }
};

// Función para mostrar mensajes de alerta usando SweetAlert.
const sweetAlert = (type, message, reload) => {
    switch (type) {
        case 1:
            Swal.fire('Éxito', message, 'success');
            break;
        case 2:
            Swal.fire('Error', message, 'error');
            break;
        case 3:
            Swal.fire('Error', message, 'error');
            break;
        case 4:
            Swal.fire('Información', message, 'info');
            break;
        default:
            break;
    }

    if (reload) {
        fillTable(); // Recargar la tabla después de una operación exitosa.
    }
};

// Función para confirmar acciones con el usuario usando SweetAlert.
const confirm = async (message) => {
    const result = await Swal.fire({
        title: 'Confirmación',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    });

    return result.isConfirmed;
};
