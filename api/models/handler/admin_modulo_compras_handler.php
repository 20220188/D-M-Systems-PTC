<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */


class ComprasHandler
{

      // Atributos para manejar los datos de la tabla COMPRAS.
      protected $factura = null;
      protected $fecha = null;
      protected $serie = null;
      protected $nota = null;
      protected $seriePersepcion = null;
      protected $NIT = null;
      protected $idProducto = null;
      protected $idFormaPago = null;
      protected $idBodega = null;
      protected $idDocumento = null;
      protected $idTipoDocumento = null;
  
    // Método para crear un nuevo registro de compra
    public function createCompra($factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento)
    {
        $sql = 'INSERT INTO tb_compras(factura, fecha, serie, nota, serie_persepcion, NIT, id_producto, id_forma_pago, id_bodega, id_documento, id_tipo_documento)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento);
        return Database::executeRow($sql, $params);
    }

    // Método para leer los detalles de una compra específica
    public function readCompra($idCompra)
    {
        $sql = 'SELECT c.id_compra, c.factura, c.fecha, c.serie, c.nota, c.serie_persepcion, c.NIT, p.nombre AS nombre_producto, 
                       fp.forma_pago, b.bodega, d.documento, td.tipo_documento
                FROM tb_compras AS c
                INNER JOIN tb_productos AS p ON c.id_producto = p.id_producto
                INNER JOIN tb_formas_pago AS fp ON c.id_forma_pago = fp.id_forma_pago
                INNER JOIN tb_bodegas AS b ON c.id_bodega = b.id_bodega
                INNER JOIN tb_documentos AS d ON c.id_documento = d.id_documento
                INNER JOIN tb_tipos_documento AS td ON c.id_tipo_documento = td.id_tipo_documento
                WHERE c.id_compra = ?';
        $params = array($idCompra);
        return Database::getRows($sql, $params);
    }

    // Método para actualizar un registro de compra
    public function updateCompra($idCompra, $factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento)
    {
        $sql = 'UPDATE tb_compras
                SET factura = ?, fecha = ?, serie = ?, nota = ?, serie_persepcion = ?, NIT = ?, id_producto = ?, id_forma_pago = ?, id_bodega = ?, id_documento = ?, id_tipo_documento = ?
                WHERE id_compra = ?';
        $params = array($factura, $fecha, $serie, $nota, $seriePersepcion, $NIT, $idProducto, $idFormaPago, $idBodega, $idDocumento, $idTipoDocumento, $idCompra);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un registro de compra
    public function deleteCompra($idCompra)
    {
        $sql = 'DELETE FROM tb_compras WHERE id_compra = ?';
        $params = array($idCompra);
        return Database::executeRow($sql, $params);
    }
}