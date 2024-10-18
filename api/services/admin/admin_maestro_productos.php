<?php
// Se incluye la clase del modelo.
require_once('../../models/data/admin_maestro_productos_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $producto = new ProductosData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $producto->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setNombre($_POST['nombreProducto']) or
                    !$producto->setDescripcion($_POST['descripcionProducto']) or
                    !$producto->setCodigo($_POST['codigoProducto']) or
                    !$producto->setFechaVencimiento($_POST['fechaVencimiento']) or
                    !$producto->setPresentacion($_POST['presentacionDetalle']) or
                    !$producto->setImagen($_FILES['imagenProducto']) 
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto creado correctamente';
                    // Se asigna el estado del archivo después de insertar.
                    $result['fileStatus'] = Validator::saveFile($_FILES['imagenProducto'], $producto::RUTA_IMAGEN);
                } else {
                    $result['exception'] = Database::getException();
                    $result['error'] = 'Codigo ya existente';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $producto->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen productos registrados';
                }
                break;
                case 'readAllWithPrice':
                    if ($result['dataset'] = $producto->readAllWithPrice()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                    } else {
                        $result['error'] = 'No existen productos registrados';
                    }
                    break;
            case 'readOne':
                if (!$producto->setId($_POST['idProducto'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($result['dataset'] = $producto->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Producto inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setId($_POST['idProducto']) or
                    !$producto->setFilename() or
                    !$producto->setNombre($_POST['nombreProducto']) or
                    !$producto->setDescripcion($_POST['descripcionProducto']) or
                    !$producto->setCodigo($_POST['codigoProducto']) or
                    !$producto->setFechaVencimiento($_POST['fechaVencimiento']) or
                    !$producto->setPresentacion($_POST['presentacionDetalle']) or
                    !$producto->setImagen($_FILES['imagenProducto'], $producto->getFilename())
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                    // Se asigna el estado del archivo después de actualizar.
                    $result['fileStatus'] = Validator::changeFile($_FILES['imagenProducto'], $producto::RUTA_IMAGEN, $producto->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el producto';
                }
                break;
            case 'deleteRow':
                if (
                    !$producto->setId($_POST['idProducto']) or
                    !$producto->setFilename()
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado correctamente';
                    // Se asigna el estado del archivo después de eliminar.
                    $result['fileStatus'] = Validator::deleteFile($producto::RUTA_IMAGEN, $producto->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el producto';
                }
                break;
                //Casos para DETALLE_PRODUCTO
            case 'createRowDetalleProducto':
                $_POST = Validator::validateForm($_POST);
                if (
                    
                    !$producto->setUbicacion($_POST['ubicacionDetalle']) or
                    !$producto->setMinimo($_POST['minimoDetalle']) or
                    !$producto->setMaximo($_POST['maximoDetalle']) or
                    !$producto->setMarca($_POST['marcaDetalle']) or
                    !$producto->setExistencias($_POST['existenciaDetalle']) or
                    !$producto->setFechaIngreso($_POST['fechaIngresoDetalle']) or
                    !$producto->setPeriodoExistencia($_POST['periodoEistenciaDetalle']) or
                    !$producto->setIdLaboratorio($_POST['laboratorioDetalle']) or
                    !$producto->setPrecioSinIva($_POST['precioSinIVA']) or
                    !$producto->setPrecioConIva($_POST['precioVentaConIVA']) or
                    !$producto->setCostoUnitario($_POST['precioUnitario']) or
                    !$producto->setDescuento($_POST['descuentoDetalle']) or
                    !$producto->setPrecioConDescuento($_POST['precioDescDetalle']) or
                    !$producto->setPrecioOpcional1($_POST['precioOpc1Detalle']) or
                    !$producto->setPrecioOpcional2($_POST['precioOpc2Detalle']) or
                    !$producto->setPrecioOpcional3($_POST['precioOpc3Detalle']) or
                    !$producto->setPrecioOpcional4($_POST['precioOpc4Detalle']) or
                    !$producto->setId($_POST['idProductoDetalle'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->createRowDetalle()) {
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
                if (!$producto->setIdDetalle($_POST['idDetalle'])) {
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
                    !$producto->setIdDetalle($_POST['idDetalle']) or
                    !$producto->setUbicacion($_POST['ubicacionDetalle']) or
                    !$producto->setMinimo($_POST['minimoDetalle']) or
                    !$producto->setMaximo($_POST['maximoDetalle']) or
                    !$producto->setMarca($_POST['marcaDetalle']) or
                    !$producto->setExistencias($_POST['existenciaDetalle']) or
                    !$producto->setFechaIngreso($_POST['fechaIngresoDetalle']) or
                    !$producto->setPeriodoExistencia($_POST['periodoEistenciaDetalle']) or
                    !$producto->setIdLaboratorio($_POST['laboratorioDetalle']) or
                    !$producto->setPrecioSinIva($_POST['precioSinIVA']) or
                    !$producto->setPrecioConIva($_POST['precioVentaConIVA']) or
                    !$producto->setCostoUnitario($_POST['precioUnitario']) or
                    !$producto->setDescuento($_POST['descuentoDetalle']) or
                    !$producto->setPrecioConDescuento($_POST['precioDescDetalle']) or
                    !$producto->setPrecioOpcional1($_POST['precioOpc1Detalle']) or
                    !$producto->setPrecioOpcional2($_POST['precioOpc2Detalle']) or
                    !$producto->setPrecioOpcional3($_POST['precioOpc3Detalle']) or
                    !$producto->setPrecioOpcional4($_POST['precioOpc4Detalle'])
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
                if (!$producto->setIdDetalle($_POST['idDetalle'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->deleteRowDetalle()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el detalle';
                }
                break;
                case 'getProductosConMasExistencias':
                    if ($result['dataset'] = $producto->getProductosConMasExistencias()) {
                        $result['status'] = 1;
                        $result['message'] = 'Productos obtenidos correctamente';
                    } else {
                        $result['error'] = 'No se pudieron obtener los productos con más existencias';
                    }
                    break;
                    case 'getProductByCode':
                        // Validamos el código del producto que viene desde el frontend
                        if (!$producto->setCodigo($_POST['codigoProducto'])) {
                            $result['error'] = $producto->getDataError();
                        } elseif ($result['dataset'] = $producto->getProductByCode()) {
                            $result['status'] = 1;
                            $result['message'] = 'Producto encontrado';
                        } else {
                            $result['error'] = 'Producto no encontrado';
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
