<?php
require_once('../../helpers/database.php');

class VentasHandler
{
    protected $id_venta = null;
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
        $sql = 'INSERT INTO tb_ventas(fecha_venta, id_dependiente, id_cliente, id_bodega, notas, subtotal)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->id_dependiente, $this->id_cliente, $this->id_bodega, $this->nota, $this->subtotal);
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
                    b.bodega,
                    v.notas,
                    v.subtotal
                FROM 
                    tb_ventas v
                    INNER JOIN tb_dependientes d ON v.id_dependiente = d.id_dependiente
                    INNER JOIN tb_clientes c ON v.id_cliente = c.id_cliente
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
        $params = array($this->fecha_venta, $this->id_dependiente, $this->id_cliente, $this->id_bodega, $this->subtotal, $this->id);
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
                ';
        $params = array($codigo);
        return Database::getRow($sql, $params);
    }

    public function createDetalleVenta()
    {
        // Corrección: Usar this->id_venta en lugar de this->id
        $sql = 'INSERT INTO tb_detalle_venta(id_venta, id_producto, cantidad, precio_con_iva)
                VALUES(?, (SELECT id_producto FROM tb_productos WHERE codigo = ?), ?, ?)';
        // Corrección: Usar this->id_venta
        $params = array($this->id_venta, $this->codigo, $this->cantidad, $this->precio_unitario);
        return Database::executeRow($sql, $params);
    }

    

    public function readDetalleVenta()
{
    // Corrección: Usar this->id_venta
    $sql = 'SELECT 
                dv.id_detalle_venta,
                p.codigo, 
                p.nombre, 
                dv.cantidad, 
                dv.precio_con_iva
            FROM 
                tb_detalle_venta dv
                INNER JOIN tb_productos p USING(id_producto)
            WHERE 
                dv.id_venta = ?';
    $params = array($this->id_venta);
    return Database::getRows($sql, $params);
}

    public function readOneDetalle()
    {
        $sql = 'SELECT dv.id_detalle_venta,dv.id_venta, p.codigo, p.nombre, dv.cantidad, dv.precio_con_iva, p.presentacion
                FROM tb_detalle_venta dv
                INNER JOIN tb_productos p USING(id_producto)
                WHERE dv.id_detalle_venta = ?';
        $params = array($this->id_detalle_venta);
        return Database::getRow($sql, $params);
    }

    public function updateRowDetalle()
{
    try {
        // Inicia una nueva transacción para asegurar que todas las operaciones se completen correctamente.
        Database::beginTransaction();
        
        // Separa los IDs de detalle, esperando que estén en una cadena separada por comas.
        $detalleIds = explode(',', $this->id_detalle_venta);
        
        // Calcula cuántas unidades se asignarán a cada detalle.
        $cantidadPorDetalle = floor($this->cantidad / count($detalleIds));
        
        // Calcula cuántas unidades sobran después de distribuirlas uniformemente.
        $cantidadExtra = $this->cantidad % count($detalleIds);
        
        // Recorre cada ID de detalle en el arreglo $detalleIds.
        foreach ($detalleIds as $index => $id) {
            // Inicializa la cantidad actual con la cantidad por detalle.
            $cantidadActual = $cantidadPorDetalle;

            // Para el primer detalle, añade la cantidad extra para asegurar que todas las unidades se distribuyan.
            if ($index == 0) {
                $cantidadActual += $cantidadExtra;
            }
            
            
            $sql = 'UPDATE tb_detalle_venta
                    SET cantidad = ?
                    WHERE id_detalle_venta = ? AND id_venta = ?';
            
            $params = array($cantidadActual, $id, $this->id_venta);

            if (!Database::executeRow($sql, $params)) {
                // Si la ejecución falla, lanza una excepción.
                throw new Exception('Error al actualizar detalle');
            }
        }
        
        // Si todas las actualizaciones son exitosas, se confirma la transacción.
        Database::commitTransaction();
        return true; 
    } catch (Exception $e) {
        // Si ocurre una excepción, se revierte la transacción para deshacer cambios.
        Database::rollbackTransaction();
        return false; 
    }
}

    
    
        // Delete a sale record by its ID
        public function deleteRowDetalle()
        {
            $sql = 'DELETE FROM tb_detalle_venta
                    WHERE id_detalle_venta = ?';
            $params = array($this->id_detalle_venta);
            return Database::executeRow($sql, $params);
        }

        public function readDetallesByCode($codigo)
{
    $sql = 'SELECT dv.id_detalle_venta, dv.id_venta, p.codigo, p.nombre, 
            dv.cantidad, dv.precio_con_iva, p.presentacion
            FROM tb_detalle_venta dv
            INNER JOIN tb_productos p USING(id_producto)
            WHERE dv.id_venta = ? AND p.codigo = ?';
    $params = array($this->id_venta, $codigo);
    return Database::getRows($sql, $params);
}

public function deleteDetallesByCode($codigo)
{
    $sql = 'DELETE dv FROM tb_detalle_venta dv
            INNER JOIN tb_productos p USING(id_producto)
            WHERE dv.id_venta = ? AND p.codigo = ?';
    $params = array($this->id_venta, $codigo);
    return Database::executeRow($sql, $params);
}



}
