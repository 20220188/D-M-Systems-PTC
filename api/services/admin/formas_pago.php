<?php
// Se incluye la clase del modelo.
require_once('../../models/data/ventas_modulo_ventas_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $FormaPago = new FormaPagoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $FormaPago->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;

            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (!$FormaPago->setFormaPago($_POST['forma_pago'])) {
                    $result['error'] = $FormaPago->getDataError();
                } elseif ($FormaPago->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Forma de pago creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readAll':
                if ($result['dataset'] = $FormaPago->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen formas de pago registradas';
                }
                break;

            case 'readOne':
                if (!$FormaPago->setIdFormaPago($_POST['id_forma_pago'])) {
                    $result['error'] = $FormaPago->getDataError();
                } elseif ($result['dataset'] = $FormaPago->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Forma de pago inexistente';
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$FormaPago->setIdFormaPago($_POST['id_forma_pago']) ||
                    !$FormaPago->setFormaPago($_POST['forma_pago'])
                ) {
                    $result['error'] = $FormaPago->getDataError();
                } elseif ($FormaPago->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Forma de pago modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la forma de pago';
                }
                break;

            case 'deleteRow':
                if (!$FormaPago->setIdFormaPago($_POST['id_forma_pago'])) {
                    $result['error'] = $FormaPago->getDataError();
                } elseif ($FormaPago->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Forma de pago eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la forma de pago';
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
