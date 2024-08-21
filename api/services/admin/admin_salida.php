<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_salida_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $salida = new salidasData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $salida->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                case 'createRow':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$salida->setNota($_POST['notaSalida']) or
                        !$salida->setFecha($_POST['fechaSalida']) or
                        !$salida->setTipoSalida($_POST['tipoSalida']) or
                        !$salida->setNumeroSalida($_POST['numeroSalida']) or
                        !$salida->setEntrega($_POST['entregaSalida']) or
                        !$salida->setCantidad($_POST['cantidadSalida']) or
                        !$salida->setCliente($_POST['cliente']) or
                        !$salida->setDependiente($_POST['Dependiente']) 

                    ) {
                        $result['error'] = $salida->getDataError();
                    } elseif ($salida->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'salida creado correctamente';
                        //Se asigna el estado del archivo después de insertar.
                    } else {
                        $result['error'] = 'Ocurrió un problema al crear un salida';
                    }
                    break;
            case 'readAll':
                if ($result['dataset'] = $salida->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen salidas registrados';
                }
                break;
            case 'readOne':
                if (!$salida->setId($_POST['idSalida'])) {
                    $result['error'] = $salida->getDataError();
                } elseif ($result['dataset'] = $salida->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Salida inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$salida->setId($_POST['idSalida']) or
                    !$salida->setNota($_POST['notaSalida']) or
                    !$salida->setFecha($_POST['fechaSalida']) or
                    !$salida->setTipoSalida($_POST['tipoSalida']) or
                    !$salida->setNumeroSalida($_POST['numeroSalida']) or
                    !$salida->setEntrega($_POST['entregaSalida']) or
                    !$salida->setCantidad($_POST['cantidadSalida']) or
                    !$salida->setCliente($_POST['cliente']) or
                    !$salida->setDependiente($_POST['Dependiente'])
                ) {
                    $result['error'] = $salida->getDataError();
                } elseif ($salida->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Salida modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la salida';
                }
                break;
            case 'deleteRow':
                if (
                    !$salida->setId($_POST['idSalida'])
                ) {
                    $result['error'] = $salida->getDataError();
                } elseif ($salida->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Salida eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la salida';
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
