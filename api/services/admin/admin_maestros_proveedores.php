<?php
// Incluir la clase del modelo de proveedores
require_once('../../models/data/admin_maestros_proveedores_data.php');

// Verificar si existe una acción a realizar, de lo contrario, finalizar con un mensaje de error.
if (isset($_GET['action'])) {
    // Iniciar o reanudar la sesión actual para utilizar variables de sesión en el script.
    session_start();

    // Instanciar la clase correspondiente a proveedores.
    $proveedor = new ProveedorData();

    // Arreglo para guardar el resultado que retorna la API.
    $result = array(
        'status' => 0,
        'message' => null,
        'dataset' => null,
        'error' => null,
        'exception' => null,
        'fileStatus' => null
    );

    // Verificar si hay una sesión iniciada como administrador, de lo contrario, finalizar con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Comparar la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                // Validar el término de búsqueda antes de ejecutar la búsqueda.
                if (!isset($_POST['search']) || empty($_POST['search'])) {
                    $result['error'] = 'El término de búsqueda no puede estar vacío';
                } else {
                    // Aquí puedes aplicar tu lógica de búsqueda utilizando $proveedor->searchRows()
                    if ($result['dataset'] = $proveedor->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                    } else {
                        $result['error'] = 'No hay coincidencias';
                    }
                }
                break;

            case 'createRow':
                // Validar y filtrar los datos del formulario antes de crear un nuevo proveedor.
                $_POST = array_map('htmlspecialchars', $_POST); // Ejemplo básico de sanitización, ajusta según tus necesidades.
                if (
                    !$proveedor->setNombreProveedor($_POST['nombreProveedor']) ||
                    !$proveedor->setCodigoProveedor($_POST['codigoProveedor']) ||
                    !$proveedor->setPaisProveedor($_POST['paisProveedor']) ||
                    !$proveedor->setGiroNegocioProveedor($_POST['giroNegocioProveedor']) ||
                    !$proveedor->setDuiProveedor($_POST['duiProveedor']) ||
                    !$proveedor->setNombreComercialProveedor($_POST['nombreComercialProveedor']) ||
                    !$proveedor->setFechaProveedor($_POST['fechaProveedor']) ||
                    !$proveedor->setNitProveedor($_POST['nitProveedor']) ||
                    !$proveedor->setTelefonoProveedor($_POST['telefonoProveedor']) ||
                    !$proveedor->setContactoProveedor($_POST['contactoProveedor']) ||
                    !$proveedor->setDireccionProveedor($_POST['direccionProveedor']) ||
                    !$proveedor->setDepartamentoProveedor($_POST['departamentoProveedor']) ||
                    !$proveedor->setMunicipioProveedor($_POST['municipioProveedor'])
                ) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($proveedor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readAll':
                // Obtener todos los proveedores registrados.
                if ($result['dataset'] = $proveedor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen proveedores registrados';
                }
                break;

            case 'readOne':
                // Obtener los detalles de un proveedor específico por su ID.
                if (!$proveedor->setIdProveedor($_POST['idProveedor'])) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($result['dataset'] = $proveedor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Proveedor inexistente';
                }
                break;

            case 'updateRow':
                // Validar y filtrar los datos del formulario antes de actualizar un proveedor existente.
                $_POST = array_map('htmlspecialchars', $_POST); // Ejemplo básico de sanitización, ajusta según tus necesidades.
                if (
                    !$proveedor->setIdProveedor($_POST['idProveedor']) ||
                    !$proveedor->setNombreProveedor($_POST['nombreProveedor']) ||
                    !$proveedor->setCodigoProveedor($_POST['codigoProveedor']) ||
                    !$proveedor->setPaisProveedor($_POST['paisProveedor']) ||
                    !$proveedor->setGiroNegocioProveedor($_POST['giroNegocioProveedor']) ||
                    !$proveedor->setDuiProveedor($_POST['duiProveedor']) ||
                    !$proveedor->setNombreComercialProveedor($_POST['nombreComercialProveedor']) ||
                    !$proveedor->setFechaProveedor($_POST['fechaProveedor']) ||
                    !$proveedor->setNitProveedor($_POST['nitProveedor']) ||
                    !$proveedor->setTelefonoProveedor($_POST['telefonoProveedor']) ||
                    !$proveedor->setContactoProveedor($_POST['contactoProveedor']) ||
                    !$proveedor->setDireccionProveedor($_POST['direccionProveedor']) ||
                    !$proveedor->setDepartamentoProveedor($_POST['departamentoProveedor']) ||
                    !$proveedor->setMunicipioProveedor($_POST['municipioProveedor'])
                ) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($proveedor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el proveedor';
                }
                break;

            case 'deleteRow':
                // Eliminar un proveedor por su ID.
                if (!$proveedor->setIdProveedor($_POST['idProveedor'])) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($proveedor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el proveedor';
                }
                break;

            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }

        // Obtener la excepción del servidor de base de datos si ocurre un problema.
        $result['exception'] = Database::getException();

        // Establecer el tipo de contenido a JSON.
        header('Content-type: application/json; charset=utf-8');

        // Imprimir el resultado en formato JSON y retornarlo al controlador.
        print(json_encode($result));
    } else {
        // Acceso denegado si no hay sesión de administrador.
        print(json_encode('Acceso denegado'));
    }
} else {
    // Recurso no disponible si no se especifica una acción.
    print(json_encode('Recurso no disponible'));
}
?>
