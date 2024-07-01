<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestro_dependiente_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $dependiente = new DependientesData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $dependiente->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$dependiente->setNombre($_POST['nombreDependiente']) or
                    !$dependiente->setCodigo($_POST['codigoDependiente']) 
                   
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto creado correctamente';
                   
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $dependiente->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen productos registrados';
                }
                break;
            case 'readOne':
                if (!$dependiente->setId($_POST['idDependiente'])) {
                    $result['error'] = $dependiente->getDataError();
                } elseif ($result['dataset'] = $dependiente->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'dependiente inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$dependiente->setId($_POST['idDependiente']) or
                    !$dependiente->setNombre($_POST['nombreDependiente']) 
                
                ) {
                    $result['error'] = $dependiente->getDataError();
                } elseif ($dependiente->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                   
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el producto';
                }
                break;
            case 'deleteRow':
                if (
                    !$dependiente->setId($_POST['idProducto']) 
                    
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($dependiente->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado correctamente';
                   
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el producto';
                }
                break;
                //Casos para DETALLE_PRODUCTO
                /*
            case 'createRowDetalleProducto':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setPrecio($_POST['precioDetalle']) or
                    !$producto->setExistencias($_POST['existenciasDetalle']) or
                    !$producto->setTalla($_POST['tallaDetalle']) or
                    !$producto->setId($_POST['idProductoDetalle'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->createRowDetalleProducto()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAllDetalle':
                if (!$producto->setId($_POST['idProducto'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($result['dataset'] = $producto->readAllDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen detalles registrados';
                }
                break;
            case 'readOneDetalleProducto':
                if (!$producto->setDetalleproducto($_POST['idDetalle'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($result['dataset'] = $producto->readOneDetalle()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Detalle inexistente';
                }
                break;
            case 'updateRowDetalleProducto':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setDetalleproducto($_POST['idDetalle']) or
                    !$producto->setPrecio($_POST['precioDetalle']) or
                    !$producto->setExistencias($_POST['existenciasDetalle']) or
                    !$producto->setTalla($_POST['tallaDetalle'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->updateRowDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle modificado correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al modificar el detalle';
                }
                break;
            case 'deleteRowDetalleProducto':
                if (!$producto->setDetalleproducto($_POST['idDetalle'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->deleteRowDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el detalle';
                }
                break;*/
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
