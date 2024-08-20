<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_ingreso_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $entrada = new EntradasData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $entrada->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                case 'createRow':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$entrada->setNota($_POST['notaEntrada']) or
                        !$entrada->setFecha($_POST['fechaEntrada']) or
                        !$entrada->setTipoEntrada($_POST['tipoEntrada']) or
                        !$entrada->setNumeroEntrada($_POST['numeroEntrada'])
                    ) {
                        $result['error'] = $entrada->getDataError();
                    } elseif ($entrada->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'entrada$entrada creado correctamente';
                        //Se asigna el estado del archivo después de insertar.
                    } else {
                        $result['error'] = 'Ocurrió un problema al crear un entrada$entrada';
                    }
                    break;
            case 'readAll':
                if ($result['dataset'] = $entrada->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen entradas registrados';
                }
                break;
            case 'readOne':
                if (!$entrada->setId($_POST['idEntrada'])) {
                    $result['error'] = $entrada->getDataError();
                } elseif ($result['dataset'] = $entrada->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'entrada inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$entrada->setId($_POST['idEntrada']) or
                    !$entrada->setNota($_POST['notaEntrada']) or
                    !$entrada->setFecha($_POST['fechaEntrada']) or
                    !$entrada->setTipoEntrada($_POST['tipoEntrada']) or
                    !$entrada->setNumeroEntrada($_POST['numeroEntrada'])
                ) {
                    $result['error'] = $entrada->getDataError();
                } elseif ($entrada->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'entrada modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la entrada';
                }
                break;
            case 'deleteRow':
                if (
                    !$entrada->setId($_POST['idEntrada'])
                ) {
                    $result['error'] = $entrada->getDataError();
                } elseif ($entrada->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'entrada eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el entrada';
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
