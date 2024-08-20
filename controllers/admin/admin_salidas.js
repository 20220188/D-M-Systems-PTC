const SALIDAS_API = 'services/admin/salidas_service.php';

const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');

const SAVE_FORM = document.getElementById('saveForm'),
    ID_SALIDA = document.getElementById('idSalida'),
    FECHA_SALIDA = document.getElementById('fechaSalida'),
    CANTIDAD = document.getElementById('cantidad'),
    ID_CLIENTE = document.getElementById('idCliente'),
    ID_DEPENDIENTE = document.getElementById('idDependiente'),
    ID_PRODUCTO = document.getElementById('idProducto');

document.addEventListener('DOMContentLoaded', () => {
    loadTemplate();
    MAIN_TITLE.textContent = 'Gestionar salidas';
    fillTable();
});

SAVE_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();
    (ID_SALIDA.value) ? action = 'updateRow' : action = 'createRow';
    const FORM = new FormData(SAVE_FORM);
    const DATA = await fetchData(SALIDAS_API, action, FORM);
    if (DATA.status) {
        SAVE_MODAL.hide();
        sweetAlert(1, DATA.message, true);
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

const fillTable = async (form = null) => {
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    (form) ? action = 'searchRows' : action = 'readAll';
    const DATA = await fetchData(SALIDAS_API, action, form);
    if (DATA.status) {
        DATA.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.fecha_salida}</td>
                    <td>${row.cantidad}</td>
                    <td>${row.nombre_cliente}</td>
                    <td>${row.nombre_dependiente}</td>
                    <td>${row.nombre_producto}</td>
                    <td class="td-button">
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_salida})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_salida})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.exception, true);
    }
}

const openCreate = () => {
    SAVE_FORM.reset();
    MODAL_TITLE.textContent = 'Crear salida';
}

const openUpdate = async (id_salida) => {
    const FORM = new FormData();
    FORM.append('id_salida', id_salida);
    const DATA = await fetchData(SALIDAS_API, 'readOne', FORM);
    if (DATA.status) {
        SAVE_FORM.reset();
        ID_SALIDA.value = DATA.dataset.id_salida;
        FECHA_SALIDA.value = DATA.dataset.fecha_salida;
        CANTIDAD.value = DATA.dataset.cantidad;
        ID_CLIENTE.value = DATA.dataset.id_cliente;
        ID_DEPENDIENTE.value = DATA.dataset.id_dependiente;
        ID_PRODUCTO.value = DATA.dataset.id_producto;
        MODAL_TITLE.textContent = 'Actualizar salida';
        SAVE_MODAL.show();
    } else {
        sweetAlert(2, DATA.exception, false);
    }
}

const openDelete = (id_salida) => {
    const callback = async () => {
        const FORM = new FormData();
        FORM.append('id_salida', id_salida);
        const DATA = await fetchData(SALIDAS_API, 'deleteRow', FORM);
        if (DATA.status) {
            sweetAlert(1, DATA.message, true);
            fillTable();
        } else {
            sweetAlert(2, DATA.exception, false);
        }
    }
    confirmAction('¿Seguro que quieres eliminar la salida?', callback);
}
