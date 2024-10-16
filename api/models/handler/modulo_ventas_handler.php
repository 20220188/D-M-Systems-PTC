<?php
require_once('../../helpers/database.php');

class VentasHandler
{
    protected $id = null;
    protected $fecha_venta = null;
    protected $id_dependiente = null;
    protected $id_cliente = null;
    protected $id_forma_pago = null;
    protected $id_documento = null;
    protected $id_tipo_documento = null;
    protected $id_bodega = null;

    protected $nota = null;

    protected $subtotal = null;

    // Search rows based on different fields
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_venta, fecha_venta, nombre_cliente, nombre_dependiente, forma_pago, documento, subtotal
                FROM tb_ventas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_dependientes USING(id_dependiente)
                INNER JOIN tb_formas_pago USING(id_forma_pago)
                INNER JOIN tb_documentos USING(id_documento)
                WHERE nombre_cliente LIKE ? OR nombre_dependiente LIKE ? OR documento LIKE ? OR forma_pago LIKE ?
                ORDER BY fecha_venta ASC';
        $params = array($value, $value, $value, $value);
        return Database::getRows($sql, $params);
    }

    // Create a new row in the tb_ventas table
    public function createRow()
    {
        $sql = 'INSERT INTO tb_ventas(fecha_venta, id_dependiente, id_cliente, id_forma_pago, id_documento,notas,  id_tipo_documento, id_bodega, subtotal)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->id_dependiente, $this->id_cliente, $this->id_forma_pago, $this->nota, $this->id_documento, $this->id_tipo_documento, $this->id_bodega, $this->subtotal);
        return Database::executeRow($sql, $params);
    }

    // Read all sales records
    public function readAll()
    {
        $sql = 'SELECT id_venta, fecha_venta, nombre_cliente, nombre_dependiente, forma_pago, documento, subtotal
                FROM tb_ventas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_dependientes USING(id_dependiente)
                INNER JOIN tb_formas_pago USING(id_forma_pago)
                INNER JOIN tb_documentos USING(id_documento)
                ORDER BY fecha_venta DESC';
        return Database::getRows($sql);
    }

    // Read a single sale record by its ID
    public function readOne()
    {
        $sql = 'SELECT id_venta, fecha_venta, id_dependiente, id_cliente, id_forma_pago, id_documento, id_tipo_documento, id_bodega, subtotal
                FROM tb_ventas
                WHERE id_venta = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Update an existing sale record
    public function updateRow()
    {
        $sql = 'UPDATE tb_ventas
                SET fecha_venta = ?, id_dependiente = ?, id_cliente = ?, id_forma_pago = ?, id_documento = ?, id_tipo_documento = ?, id_bodega = ?, subtotal = ?
                WHERE id_venta = ?';
        $params = array($this->fecha_venta, $this->id_dependiente, $this->id_cliente, $this->id_forma_pago, $this->id_documento, $this->id_tipo_documento, $this->id_bodega, $this->subtotal, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Delete a sale record by its ID
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_ventas
                WHERE id_venta = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    
}
?>
