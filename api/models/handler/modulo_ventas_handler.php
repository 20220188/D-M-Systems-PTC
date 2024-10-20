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
    protected $total_venta = null;
    protected $iva_venta = null;
    protected $id_detalle_venta = null;
    protected $cantidad = null;
    protected $precio_unitario = null;
    protected $descuento = null;
    protected $codigo = null;

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
        $sql = 'INSERT INTO tb_ventas(fecha_venta, id_dependiente, id_cliente, id_forma_pago, id_documento, id_tipo_documento, id_bodega, notas, subtotal)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->id_dependiente, $this->id_cliente, $this->id_forma_pago, $this->id_documento, $this->id_tipo_documento, $this->id_bodega, $this->nota, $this->subtotal);
        return Database::executeRow($sql, $params);
    }

    // Read all sales records
    public function readAll()
    {
        $sql = 'SELECT 
                    v.id_venta, 
                    v.fecha_venta, 
                    d.nombre_dependiente AS vendedor,
                    c.nombre AS cliente,
                    fp.forma_pago,
                    doc.documento,
                    td.tipo_documento,
                    b.bodega,
                    v.notas,
                    v.subtotal
                FROM 
                    tb_ventas v
                    INNER JOIN tb_dependientes d ON v.id_dependiente = d.id_dependiente
                    INNER JOIN tb_clientes c ON v.id_cliente = c.id_cliente
                    INNER JOIN tb_formas_pago fp ON v.id_forma_pago = fp.id_forma_pago
                    INNER JOIN tb_documentos doc ON v.id_documento = doc.id_documento
                    INNER JOIN tb_tipos_documento td ON v.id_tipo_documento = td.id_tipo_documento
                    INNER JOIN tb_bodegas b ON v.id_bodega = b.id_bodega
                ORDER BY 
                    v.fecha_venta DESC';

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



    public function searchByCode($codigo)
    {
        $sql = 'SELECT 
                    p.id_producto, 
                    p.codigo, 
                    p.nombre, 
                    p.presentacion,
                    d.precio_con_iva
                FROM 
                    tb_productos p
                    INNER JOIN tb_detalle_productos d USING(id_producto)
                WHERE 
                    p.codigo = ?
                LIMIT 1';
        $params = array($codigo);
        return Database::getRow($sql, $params);
    }

    public function createDetalleVenta()
    {
        $sql = 'INSERT INTO tb_detalle_venta(id_venta, id_producto, cantidad, precio_con_iva)
                VALUES(?, (SELECT id_producto FROM tb_productos WHERE codigo = ?), ?, ?)';
        $params = array($this->id, $this->codigo, $this->cantidad, $this->precio_unitario);
        return Database::executeRow($sql, $params);
        print_r($params);
    }

    public function readDetalleVenta()
    {
        $sql = 'SELECT 
                    p.codigo, 
                    p.nombre, 
                    dv.cantidad, 
                    dv.precio_con_iva
                FROM 
                    tb_detalle_venta dv
                    INNER JOIN tb_productos p USING(id_producto)
                WHERE 
                    dv.id_venta = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

}
