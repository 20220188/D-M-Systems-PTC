<?php
require_once('../../models/handler/salidas_data.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $salidas = new SalidasData();

    switch ($action) {
        case 'createRow':
            $fecha_salida = $_POST['fecha_salida'] ?? '';
            $cantidad = $_POST['cantidad'] ?? '';
            $id_cliente = $_POST['id_cliente'] ?? '';
            $id_dependiente = $_POST['id_dependiente'] ?? '';
            $id_producto = $_POST['id_producto'] ?? '';

            if ($salidas->setFechaSalida($fecha_salida) &&
                $salidas->setCantidad($cantidad) &&
                $salidas->setIdCliente($id_cliente) &&
                $salidas->setIdDependiente($id_dependiente) &&
                $salidas->setIdProducto($id_producto)) {
                $result = $salidas->createSalida($fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto);
                echo json_encode(['status' => $result, 'message' => $result ? 'Salida creada con éxito' : 'Error al crear la salida']);
            } else {
                echo json_encode(['status' => false, 'error' => $salidas->getDataError()]);
            }
            break;

        case 'readOne':
            $id_salida = $_POST['id_salida'] ?? '';
            $data = $salidas->readSalida($id_salida);
            echo json_encode(['status' => true, 'dataset' => $data]);
            break;

        case 'updateRow':
            $id_salida = $_POST['id_salida'] ?? '';
            $fecha_salida = $_POST['fecha_salida'] ?? '';
            $cantidad = $_POST['cantidad'] ?? '';
            $id_cliente = $_POST['id_cliente'] ?? '';
            $id_dependiente = $_POST['id_dependiente'] ?? '';
            $id_producto = $_POST['id_producto'] ?? '';

            if ($salidas->setFechaSalida($fecha_salida) &&
                $salidas->setCantidad($cantidad) &&
                $salidas->setIdCliente($id_cliente) &&
                $salidas->setIdDependiente($id_dependiente) &&
                $salidas->setIdProducto($id_producto)) {
                $result = $salidas->updateSalida($id_salida, $fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto);
                echo json_encode(['status' => $result, 'message' => $result ? 'Salida actualizada con éxito' : 'Error al actualizar la salida']);
            } else {
                echo json_encode(['status' => false, 'error' => $salidas->getDataError()]);
            }
            break;

        case 'deleteRow':
            $id_salida = $_POST['id_salida'] ?? '';
            $result = $salidas->deleteSalida($id_salida);
            echo json_encode(['status' => $result, 'message' => $result ? 'Salida eliminada con éxito' : 'Error al eliminar la salida']);
            break;

        default:
            echo json_encode(['status' => false, 'error' => 'Acción no válida']);
            break;
    }
} else {
    echo json_encode(['status' => false, 'error' => 'Método de solicitud no permitido']);
}
?>
