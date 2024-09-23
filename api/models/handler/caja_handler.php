<?php
require_once('../../helpers/database.php');

class VentasHandler
{
    protected $id = null;
    protected $monto_venta = null;
    protected $fecha_venta = null;
    protected $id_cliente = null;
    protected $id_producto = null;
    protected $cantidad_producto = null;
    protected $nota = null;

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_venta, monto_venta, fecha_venta, nombre, codigo
                FROM tb_ventas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_productos USING(id_producto)
                WHERE monto_venta LIKE ? OR nombre LIKE ? OR codigo LIKE ?
                ORDER BY fecha_venta DESC';
        $params = array($value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_ventas(monto_venta, fecha_venta, id_cliente, id_producto, cantidad_producto, nota)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->monto_venta, $this->fecha_venta, $this->id_cliente, $this->id_producto, $this->cantidad_producto, $this->nota);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_venta, monto_venta, fecha_venta, nombre, codigo
                FROM tb_ventas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_productos USING(id_producto)
                ORDER BY fecha_venta DESC';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_venta, monto_venta, fecha_venta, id_cliente, id_producto, cantidad_producto, nota
                FROM tb_ventas
                WHERE id_venta = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_ventas
                SET monto_venta = ?, fecha_venta = ?, id_cliente = ?, id_producto = ?, cantidad_producto = ?, nota = ?
                WHERE id_venta = ?';
        $params = array($this->monto_venta, $this->fecha_venta, $this->id_cliente, $this->id_producto, $this->cantidad_producto, $this->nota, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_ventas
                WHERE id_venta = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>
