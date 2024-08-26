<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestros_punto_de_venta_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $Puntoventa = new PuntoDeVentaData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $Puntoventa->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$Puntoventa->setnombrePuntoVenta($_POST['nombrePuntoVenta']) or
                    !$Puntoventa->setClave($_POST['clavePuntoVenta'])
                ) {
                    $result['error'] = $Puntoventa->getDataError();
                }  elseif ($Puntoventa->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Punto de venta creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $Puntoventa->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen Punto de ventas registrados';
                }
                break;
            case 'readOne':
                if (!$Puntoventa->setidPuntoVenta($_POST['idPuntoVenta'])) {
                    $result['error'] = $Puntoventa->getDataError();
                } elseif ($result['dataset'] = $Puntoventa->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Punto de venta inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$Puntoventa->setidPuntoVenta($_POST['idPuntoVenta']) ||
                    !$Puntoventa->setnombrePuntoVenta($_POST['nombrePuntoVenta']) ||
                    !$Puntoventa->setClave($_POST['clavePuntoVenta'])
                ) {
                    $result['error'] = $Puntoventa->getDataError();
                } elseif ($Puntoventa->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Punto de venta modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el punto de venta';
                }
                break;


            case 'deleteRow':
                if (!$Puntoventa->setidPuntoVenta($_POST['idPuntoVenta'])) {
                    $result['error'] = $Puntoventa->getDataError();
                } elseif ($Puntoventa->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Punto de venta eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el Punto de venta';
                }
                break;

                    case 'PuntoVentaGrafico':
                        if ($result['dataset'] = $Puntoventa->PuntoVentaGrafico()) {
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
