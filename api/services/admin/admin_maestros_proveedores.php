<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestros_proveedores_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $proveedor = new ProveedorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $proveedor->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$proveedor->setNombreProveedor($_POST['nombreProveedor']) or
                    !$proveedor->setCodigoProveedor($_POST['codigoProveedor']) or
                    !$proveedor->setPaisProveedor($_POST['paisProveedor']) or
                    !$proveedor->setGiroNegocioProveedor($_POST['giroNegocioProveedor']) or
                    !$proveedor->setDuiProveedor($_POST['duiProveedor']) or
                    !$proveedor->setNombreComercialProveedor($_POST['nombreComercialProveedor']) or
                    !$proveedor->setFechaProveedor($_POST['fechaProveedor']) or
                    !$proveedor->setNitProveedor($_POST['nitProveedor']) or
                    !$proveedor->setTelefonoProveedor($_POST['telefonoProveedor']) or
                    !$proveedor->setContactoProveedor($_POST['contactoProveedor']) or
                    !$proveedor->setDireccionProveedor($_POST['direccionProveedor']) or
                    !$proveedor->setDepartamentoProveedor($_POST['departamentoProveedor']) or
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
                if ($result['dataset'] = $proveedor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen Proveedors registrados';
                }
                break;
            case 'readOne':
                if (!$proveedor->setIdProveedor($_POST['idProveedor'])) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($result['dataset'] = $proveedor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Proveedor inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$proveedor->setIdProveedor($_POST['idProveedor']) or
                    !$proveedor->setNombreProveedor($_POST['nombreProveedor']) or
                    !$proveedor->setCodigoProveedor($_POST['codigoProveedor']) or
                    !$proveedor->setPaisProveedor($_POST['paisProveedor']) or
                    !$proveedor->setGiroNegocioProveedor($_POST['giroNegocioProveedor']) or
                    !$proveedor->setDuiProveedor($_POST['duiProveedor']) or
                    !$proveedor->setNombreComercialProveedor($_POST['nombreComercialProveedor']) or
                    !$proveedor->setFechaProveedor($_POST['fechaProveedor']) or
                    !$proveedor->setNitProveedor($_POST['nitProveedor']) or
                    !$proveedor->setTelefonoProveedor($_POST['telefonoProveedor']) or
                    !$proveedor->setContactoProveedor($_POST['contactoProveedor']) or
                    !$proveedor->setDireccionProveedor($_POST['direccionProveedor']) or
                    !$proveedor->setDepartamentoProveedor($_POST['departamentoProveedor']) or
                    !$proveedor->setMunicipioProveedor($_POST['municipioProveedor'])
                ) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($proveedor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el Proveedor';
                }
                break;
            case 'deleteRow':
                if (
                    !$proveedor->setIdProveedor($_POST['idProveedor'])
                   
                ) {
                    $result['error'] = $proveedor->getDataError();
                } elseif ($proveedor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Proveedor eliminado correctamente';
                
                    
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el Proveedor';
                }
                break;
            
            case 'getUltimosProveedor':
                if ($result['dataset'] = $proveedor->getUltimosProveedor()) {
                    $result['status'] = 1;
                    $result['message'] = 'Datos obtenidos correctamente';
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
                default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
            }

        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
