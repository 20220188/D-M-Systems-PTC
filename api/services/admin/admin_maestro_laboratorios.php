<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestro_laboratorios_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $laboratorio = new LaboratoriosData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $laboratorio->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$laboratorio->setNombre($_POST['nombreLaboratorio']) or
                    !$laboratorio->setCodigo($_POST['codigoLaboratorio'])
                ) {
                    $result['error'] = $laboratorio->getDataError();
                } elseif ($laboratorio->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Laboratorio creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                    $result['error'] = 'Código ya existente';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $laboratorio->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen laboratorios registrados';
                }
                break;
            case 'readOne':
                if (!$laboratorio->setId($_POST['idLab'])) {
                    $result['error'] = $laboratorio->getDataError();
                } elseif ($result['dataset'] = $laboratorio->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Laboratorio inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$laboratorio->setId($_POST['idLab']) or
                    !$laboratorio->setNombre($_POST['nombreLaboratorio']) or
                    !$laboratorio->setCodigo($_POST['codigoLaboratorio'])
                ) {
                    $result['error'] = $laboratorio->getDataError();
                } elseif ($laboratorio->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Laboratorio modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el laboratorio';
                }
                break;
            case 'deleteRow':
                if (
                    !$laboratorio->setId($_POST['idLab'])
                ) {
                    $result['error'] = $laboratorio->getDataError();
                } elseif ($laboratorio->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Laboratorio eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el laboratorio';
                }
                break;


            case 'getUltimosLaboratorios':
                if ($result['dataset'] = $laboratorio->getUltimosLaboratorios()) {
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
?>
