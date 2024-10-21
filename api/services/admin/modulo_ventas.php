<?php
// Se incluye la clase del modelo.
require_once('../../models/data/modulo_ventas_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $venta = new VentasData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);

    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {


            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$venta->setFechaVenta($_POST['FechaVenta']) or
                    !$venta->setIdCliente($_POST['ClienteVenta']) or
                    !$venta->setIdVendedor($_POST['Vendedor']) or
                    !$venta->setFormaPago($_POST['FormaPagoVenta']) or
                    !$venta->setIdDocumento($_POST['DocumentoVenta']) or
                    !$venta->setIdTipoDocumento($_POST['TipoDocumentoVenta']) or
                    !$venta->setNotas($_POST['Notasventa']) or
                    !$venta->setIdBodega($_POST['BodegaVenta'])
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Venta creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

            case 'readAll':
                if ($result['dataset'] = $venta->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen ventas registradas';
                }
                break;

            case 'readOne':
                if (!$venta->setIdVenta($_POST['idVenta'])) {
                    $result['error'] = $venta->getDataError();
                } elseif ($result['dataset'] = $venta->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Venta inexistente';
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$venta->setFechaVenta($_POST['fechaVenta']) or
                    !$venta->setIdCliente($_POST['ClienteVenta']) or
                    !$venta->setIdVendedor($_POST['Vendedor']) or
                    !$venta->setFormaPago($_POST['formaPago']) or
                    !$venta->setIdDocumento($_POST['DocumentoVenta']) or
                    !$venta->setIdTipoDocumento($_POST['TipoDocumentoVenta']) or
                    !$venta->setIdBodega($_POST['BodegaVenta']) or
                    !$venta->setSubtotal($_POST['subtotalVenta'])
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Venta modificada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al modificar la venta';
                }
                break;

            case 'deleteRow':
                if (!$venta->setIdVenta($_POST['idVentaDetalle'])) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Venta eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al eliminar la venta';
                }
                break;

                /*
                /ACCIONES PARA EL MANEJO DE LOS DETALLES DE LA VENTA
                */
            case 'searchByCode':
                if (!isset($_POST['codigo'])) {
                    $result['error'] = 'Código no proporcionado';
                } elseif ($result['dataset'] = $venta->searchByCode($_POST['codigo'])) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Producto no encontrado';
                }
                break;

                case 'createDetalleVenta':
                    $_POST = Validator::validateForm($_POST);
                    // Verificamos que el ID de la venta exista y sea válido
                    if (!isset($_POST['idVentaDetalle']) || empty($_POST['idVentaDetalle'])) {
                        $result['error'] = 'El ID de la venta es requerido';
                    } elseif (
                        !$venta->setIdVenta($_POST['idVentaDetalle']) or
                        !$venta->setCantidad($_POST['cantidadDetalle']) or
                        !$venta->setPrecioUnitario($_POST['precioUnitarioDetalle']) or
                        !$venta->setCodigo($_POST['codigoDetalle'])
                    ) {
                        $result['error'] = $venta->getDataError();
                    } elseif ($venta->createDetalleVenta()) {
                        $result['status'] = 1;
                        $result['message'] = 'Detalle de venta agregado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;

                

            case 'readOneDetalle':
                if (!$venta->setIdDetalleVenta($_POST['idDetalle'])) {
                    $result['error'] = $venta->getDataError();
                } elseif ($result['dataset'] = $venta->readOneDetalle()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Producto inexistente';
                }
                break;
                
                case 'updateRowDetalle':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$venta->setIdDetalleVenta($_POST['idDetalle']) or
                        !$venta->setCantidad($_POST['cantidadDetalle']) or
                        !$venta->setIdVenta($_POST['idVentaDetalle']) or
                        // Agregar validación del precio
                        !$venta->setPrecioUnitario($_POST['precioUnitarioDetalle'])
                    ) {
                        $result['error'] = $venta->getDataError();
                    } elseif ($venta->updateRowDetalle()) {
                        $result['status'] = 1;
                        $result['message'] = 'Producto modificado correctamente';
                        // Actualizar el dataset después de la modificación
                        if ($result['dataset'] = $venta->readDetalleVenta()) {
                            $result['status'] = 1;
                        }
                    } else {
                        $result['exception'] = 'Ocurrió un problema al modificar el producto';
                    }
                    break;
                
                    case 'deleteRowDetalle':
                        if (!$venta->setIdDetalleVenta($_POST['idDetalle'])) {
                            $result['error'] = $venta->getDataError();
                        } elseif ($venta->deleteRowDetalle()) {
                            $result['status'] = 1;
                            $result['message'] = 'Producto eliminado correctamente';
                            // Actualizar el dataset después de la eliminación
                            if ($result['dataset'] = $venta->readDetalleVenta()) {
                                $result['status'] = 1;
                            }
                        } else {
                            $result['exception'] = 'Ocurrió un problema al eliminar el producto';
                        }
                        break;
                    case 'readDetalleVenta':
                        if (!$venta->setIdVenta($_POST['idVentaDetalle'])) {
                            $result['error'] = $venta->getDataError();
                        } else {
                            $result['dataset'] = $venta->readDetalleVenta();
                            if ($result['dataset'] && count($result['dataset']) > 0) {
                                $result['status'] = 1;
                                $result['message'] = 'Se encontraron ' . count($result['dataset']) . ' productos';
                            } else {
                                $result['status'] = 1;  // Mantenemos status 1 pero sin datos
                                $result['dataset'] = [];
                                $result['message'] = 'No hay detalles para esta venta';
                            }
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
