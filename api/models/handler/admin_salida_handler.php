<?php
require_once('../../helpers/database.php');

class SalidasHandler
{
    protected $id_salida = null;
    protected $fecha_salida = null;
    protected $cantidad = null;
    protected $id_cliente = null;
    protected $id_dependiente = null;
    protected $id_producto = null;

    public function createSalida($fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto)
    {
        $sql = 'INSERT INTO tb_salidas(fecha_salida, cantidad, id_cliente, id_dependiente, id_producto) 
                VALUES(?, ?, ?, ?, ?)';
        $params = array($fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto);
        return Database::executeRow($sql, $params);
    }

    public function readSalida($id_salida)
    {
        $sql = 'SELECT s.id_salida, s.fecha_salida, s.cantidad, c.nombre AS nombre_cliente, 
                       d.nombre AS nombre_dependiente, p.nombre AS nombre_producto
                FROM tb_salidas AS s
                INNER JOIN tb_clientes AS c ON s.id_cliente = c.id_cliente
                INNER JOIN tb_dependientes AS d ON s.id_dependiente = d.id_dependiente
                INNER JOIN tb_detalle_productos AS p ON s.id_producto = p.id_producto
                WHERE s.id_salida = ?';
        $params = array($id_salida);
        return Database::getRow($sql, $params);
    }

    public function updateSalida($id_salida, $fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto)
    {
        $sql = 'UPDATE tb_salidas 
                SET fecha_salida = ?, cantidad = ?, id_cliente = ?, id_dependiente = ?, id_producto = ?
                WHERE id_salida = ?';
        $params = array($fecha_salida, $cantidad, $id_cliente, $id_dependiente, $id_producto, $id_salida);
        return Database::executeRow($sql, $params);
    }

    public function deleteSalida($id_salida)
    {
        $sql = 'DELETE FROM tb_salidas WHERE id_salida = ?';
        $params = array($id_salida);
        return Database::executeRow($sql, $params);
    }
}
?>
