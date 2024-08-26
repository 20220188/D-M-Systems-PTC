<?php

// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestro_dependientes_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $dependientes = new DependientesData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para recuperar los últimos 3 registros de dependientes.
            case 'getUltimosDependientes':
                if ($result['dataset'] = $dependientes->getUltimosDependientes()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se han obtenido los últimos 3 dependientes';
                } else {
                    $result['error'] = 'No se encontraron dependientes';
                }
                break;
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $dependientes->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$dependientes->setNombre($_POST['nombreDependiente']) or
                    !$dependientes->setCodigo($_POST['codigoDependiente'])
                ) {
                    $result['error'] = $dependientes->getDataError();
                } elseif ($dependientes->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Dependiente creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                    $result['error'] = 'Código ya existente';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $dependientes->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen dependientes registrados';
                }
                break;
            case 'readOne':
                if (!$dependientes->setId($_POST['idDependiente'])) {
                    $result['error'] = $dependientes->getDataError();
                } elseif ($result['dataset'] = $dependientes->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Dependiente inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$dependientes->setId($_POST['idDependiente']) or
                    !$dependientes->setNombre($_POST['nombreDependiente']) or
                    !$dependientes->setCodigo($_POST['codigoDependiente'])
                ) {
                    $result['error'] = $dependientes->getDataError();
                } elseif ($dependientes->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Dependiente modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el dependiente';
                }
                break;
            case 'deleteRow':
                if (
                    !$dependientes->setId($_POST['idDependiente'])
                ) {
                    $result['error'] = $dependientes->getDataError();
                } elseif ($dependientes->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Dependiente eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el dependiente';
                }
                break;
                case 'getUltimosDependientes1':
                    if ($result['dataset'] = $dependientes->getUltimosDependientes()) {
                        $result['status'] = 1;
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
