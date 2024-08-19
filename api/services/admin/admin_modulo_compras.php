<?php
// Se incluye el archivo para manejar los datos de las compras.
require_once('../../models/handler/admin_modulo_compras_data.php');

// Se obtienen los datos de la solicitud.
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Se obtiene el tipo de acción a realizar.
    $action = $_POST['action'] ?? '';

    // Instanciar el objeto para manejar los datos de las compras.
    $compras = new ComprasData();

    switch ($action) {
        case 'createRow':
            // Se obtienen los datos del formulario.
            $factura = $_POST['factura'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $serie = $_POST['serie'] ?? '';
            $nota = $_POST['nota'] ?? '';
            $seriePersepcion = $_POST['seriePersepcion'] ?? '';
            $NIT = $_POST['NIT'] ?? '';
            $idProducto = $_POST['idProducto'] ?? '';
            $idFormaPago = $_POST['idFormaPago'] ?? '';
            $idBodega = $_POST['idBodega'] ?? '';
            $idDocumento = $_POST['idDocumento'] ?? '';
            $idTipoDocumento = $_POST['idTipoDocumento'] ?? '';

            // Establecer los datos.
            if ($compras->setFactura($factura) &&
                $compras->setFecha($fecha) &&
                $compras->setSerie($serie) &&
                $compras->setNota($nota) &&
                $compras->setSeriePersepcion($seriePersepcion) &&
                $compras->setNIT($NIT) &&
                $compras->setIdProducto($idProducto) &&
                $compras->setIdFormaPago($idFormaPago) &&
                $compras->setIdBodega($idBodega) &&
                $compras->setIdDocumento($idDocumento) &&
                $compras->setIdTipoDocumento($idTipoDocumento)) {
                $result = $compras->createCompra($factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento);
                echo json_encode(['status' => $result, 'message' => $result ? 'Compra creada con éxito' : 'Error al crear la compra']);
            } else {
                echo json_encode(['status' => false, 'error' => $compras->getDataError()]);
            }
            break;

        case 'readOne':
            $idCompra = $_POST['idCompra'] ?? '';
            $data = $compras->readCompra($idCompra);
            echo json_encode(['status' => true, 'dataset' => $data]);
            break;

        case 'updateRow':
            $idCompra = $_POST['idCompra'] ?? '';
            $factura = $_POST['factura'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $serie = $_POST['serie'] ?? '';
            $nota = $_POST['nota'] ?? '';
            $seriePersepcion = $_POST['seriePersepcion'] ?? '';
            $NIT = $_POST['NIT'] ?? '';
            $idProducto = $_POST['idProducto'] ?? '';
            $idFormaPago = $_POST['idFormaPago'] ?? '';
            $idBodega = $_POST['idBodega'] ?? '';
            $idDocumento = $_POST['idDocumento'] ?? '';
            $idTipoDocumento = $_POST['idTipoDocumento'] ?? '';

            if ($compras->setFactura($factura) &&
                $compras->setFecha($fecha) &&
                $compras->setSerie($serie) &&
                $compras->setNota($nota) &&
                $compras->setSeriePersepcion($seriePersepcion) &&
                $compras->setNIT($NIT) &&
                $compras->setIdProducto($idProducto) &&
                $compras->setIdFormaPago($idFormaPago) &&
                $compras->setIdBodega($idBodega) &&
                $compras->setIdDocumento($idDocumento) &&
                $compras->setIdTipoDocumento($idTipoDocumento)) {
                $result = $compras->updateCompra($idCompra, $factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento);
                echo json_encode(['status' => $result, 'message' => $result ? 'Compra actualizada con éxito' : 'Error al actualizar la compra']);
            } else {
                echo json_encode(['status' => false, 'error' => $compras->getDataError()]);
            }
            break;

        case 'deleteRow':
            $idCompra = $_POST['idCompra'] ?? '';
            $result = $compras->deleteCompra($idCompra);
            echo json_encode(['status' => $result, 'message' => $result ? 'Compra eliminada con éxito' : 'Error al eliminar la compra']);
            break;

        default:
            echo json_encode(['status' => false, 'error' => 'Acción no válida']);
            break;
    }
} else {
    echo json_encode(['status' => false, 'error' => 'Método de solicitud no permitido']);
}
?>
