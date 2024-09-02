// Constante para completar la ruta de la API.
const ENTRADA_API = 'services/admin/admin_ingreso.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm');

const SAVE_FORM_REPORT = document.getElementById('saveFormReport')

const SAVE_MODAL_REPORT = new bootstrap.Modal('#saveModalReport'),
    MODAL_TITLE_REPORT = document.getElementById('modalTitleReport');
    ID_ENTRADA = document.getElementById('idEntrada'),
    NOTA_ENTRADA = document.getElementById('notaEntrada'),
    FECHA_ENTRADA = document.getElementById('fechaEntrada'),
    NUMERO_ENTRADA = document.getElementById('numeroEntrada'),
    TIPO_ENTRADA = document.getElementById('tipoEntrada');

const CB_FILTRO = document.getElementById('filtro'),
    NUMERO_ENTRADA1 = document.getElementById('numeroEntradaReport'),
    FECHA_ENTRADA1 = document.getElementById('fechaEntradaReport');


// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar entradas';
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
    (ID_ENTRADA.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(ENTRADA_API, action, FORM);
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
    const DATA = await fetchData(ENTRADA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.numero_entrada}</td>
                    <td>${row.tipo_entrada}</td>
                    <td>${row.nota}</td>
                    <td>${row.fecha}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_entrada})">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_entrada})">
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
    MODAL_TITLE.textContent = 'Crear entrada';
    // Se prepara el formulario.
    SAVE_FORM.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idEntrada', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(ENTRADA_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar entrada';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_ENTRADA.value = ROW.id_entrada;
        NOTA_ENTRADA.value = ROW.nota;
        FECHA_ENTRADA.value = ROW.fecha;
        NUMERO_ENTRADA.value = ROW.numero_entrada;
        TIPO_ENTRADA.value = ROW.tipo_entrada;
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
    const RESPONSE = await confirmAction('¿Desea eliminar la entrada de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idEntrada', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(ENTRADA_API, 'deleteRow', FORM);
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
// Oculta inicialmente los inputs.
NUMERO_ENTRADA1.classList.add('d-none');
FECHA_ENTRADA1.classList.add('d-none');

SAVE_FORM_REPORT.addEventListener('submit', (event) => {
    event.preventDefault();
    
    let selectedValue = '';

    // Determina qué valor enviar basado en el input visible.
    if (!NUMERO_ENTRADA1.classList.contains('d-none')) {
        selectedValue = NUMERO_ENTRADA1.value;
        console.log('Valor seleccionado (Número de Entrada):', selectedValue);
    } else if (!FECHA_ENTRADA1.classList.contains('d-none')) {
        selectedValue = FECHA_ENTRADA1.value;
        console.log('Valor seleccionado (Fecha de Entrada):', selectedValue);
    }

    // Llama a la función openCreateR con el valor seleccionado.
    openCreateR(selectedValue);
    openReport(selectedValue);
});

const openCreateR = (selectedValue) => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_REPORT.show();
    MODAL_TITLE_REPORT.textContent = 'Generar reporte';
    // Se prepara el formulario.
    SAVE_FORM_REPORT.reset();

    CB_FILTRO.addEventListener('change', () => {

        // Dependiendo del valor seleccionado, muestra u oculta los inputs correspondientes.
        if (CB_FILTRO.value === 'numeroEntrada') { // Muestra el input de número de entrada.
            NUMERO_ENTRADA1.classList.remove('d-none');
            NUMERO_ENTRADA1.required = true;
            FECHA_ENTRADA1.classList.add('d-none');
            FECHA_ENTRADA1.required = false;

            console.log('Filtro seleccionado: Número de Entrada');
        } else if (CB_FILTRO.value === 'fechaEntrada') { // Muestra el input de fecha de entrada.
            FECHA_ENTRADA1.classList.remove('d-none');
            FECHA_ENTRADA1.required = true;
            NUMERO_ENTRADA1.classList.add('d-none');
            NUMERO_ENTRADA1.required = false;

            console.log('Filtro seleccionado: Fecha de Entrada');
        } else {
            // Si no se selecciona una opción válida, oculta ambos inputs.
            NUMERO_ENTRADA1.classList.add('d-none');
            NUMERO_ENTRADA1.required = false;
            FECHA_ENTRADA1.classList.add('d-none');
            FECHA_ENTRADA1.required = false;

            console.log('Ningún filtro válido seleccionado');
        }
    });
}

const openReport = (selectedValue) => {
    // Obtén el número de entrada o la fecha de entrada.
    const numeroEntrada = NUMERO_ENTRADA1.value;
    const fechaEntrada = FECHA_ENTRADA1.value;

    // Asegúrate de que el valor esté disponible
    if (!selectedValue) {
        alert('Valor de entrada no disponible.');
        return;
    }

    console.log('Valor enviado al reporte:', selectedValue);

    // Declara la constante tipo objeto con la ruta específica del reporte en el servidor
    const PATH = new URL(`${SERVER_URL}reports/admin/salidas_numerosalida.php`);

    // Agrega un parámetro a la ruta con el valor seleccionado
    if (CB_FILTRO.value === 'numeroEntrada') {
        PATH.searchParams.append('numeroSalida', numeroEntrada);
    } else if (CB_FILTRO.value === 'fechaEntrada') {
        PATH.searchParams.append('fechaSalida', fechaEntrada);
    }

    console.log('Ruta del reporte:', PATH.href);

    // Abre el reporte en una nueva pestaña
    window.open(PATH.href);
}