<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class ProductosHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */

    /*Atributos para la seccion de informacion obligatoria*/
    protected $id = null;
    protected $codigo = null;
    protected $descripcion = null;
    protected $nombre = null;
    protected $imagen = null;
    protected $precio_sin_iva = null;
    protected $precio_con_iva = null;
    protected $costo_unitario = null;
    protected $fecha_vencimiento = null;


    /*Atributos para la tabla de detalles de productos*/
    protected $id_detalle_producto = null;
    protected $presentacion = null;
    protected $ubicacion = null;
    protected $minimo = null;
    protected $maximo = null;
    protected $marca = null;
    protected $fecha = null;
    protected $periodo_existencia = null;
    protected $id_laboratorio = null;
    protected $existencias = null;

    /*Atributos para la seccion de detalles de precios*/
    protected $descuento = null;
    protected $precio_con_descuento = null;
    protected $precio_opcional1 = null;
    protected $precio_opcional2 = null;
    protected $precio_opcional3 = null;
    protected $precio_opcional4 = null;

    /*Atributos para la seccion de detalles de informacion de solo lectura
    protected $fecha_ultima_compra = null;
    protected $entradas = null;
    protected $salidas = null;
    protected $precio_ultima_compra = null;
    protected $costo_total = null;
    protected $id_proveedor = null;
    protected $existencias_actuales = null;
    protected $id_iva = null;
    */

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/productos/';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    /*
    *  Método para los registros de la tabla de productos.
    */

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento, presentacion
                FROM tb_productos
                WHERE codigo LIKE ? OR nombre LIKE ? OR descripcion LIKE ? OR presentacion LIKE ? 
                ORDER BY nombre';
        $params = array($value, $value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_productos(imagen, codigo, nombre, descripcion, fecha_vencimiento, presentacion)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->imagen, $this->codigo, $this->nombre, $this->descripcion, $this->fecha_vencimiento, $this->presentacion);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento,presentacion
                FROM tb_productos
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readAllWithPrice()
    {
        $sql = 'SELECT p.id_producto, p.codigo, p.nombre, p.presentacion, 
                    d.precio_con_iva
                FROM tb_productos p
                INNER JOIN tb_detalle_productos d USING(id_producto)
                ORDER BY p.nombre';
        return Database::getRows($sql);
    }
    


    public function productosReport()
    {
        $sql = 'SELECT
    p.nombre,
    p.codigo,
    d.costo_unitario,
    d.descuento,
    d.precio_con_descuento,
    d.existencia
FROM
    tb_productos p
JOIN
    tb_detalle_Productos d ON p.id_producto = d.id_producto';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento, presentacion
                FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_productos
                SET imagen = ?, codigo = ?, nombre = ?, descripcion = ?, fecha_vencimiento = ?, presentacion = ?
                WHERE id_producto = ?';
        $params = array($this->imagen, $this->codigo, $this->nombre, $this->descripcion, $this->fecha_vencimiento, $this->presentacion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen
                FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
    *  Método para los registros de la tabla de detalles de productos.
    */


    public function createRowDetalle()
    {
        $sql = 'INSERT INTO tb_detalle_productos( ubicacion, minimo, maximo, marca, periodo_existencia, fecha, existencia, id_laboratorio, precio_sin_iva, precio_con_iva, costo_unitario,descuento, precio_con_descuento, precio_opcional1, precio_opcional2, precio_opcional3, precio_opcional4, id_producto)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->ubicacion, $this->minimo, $this->maximo, $this->marca, $this->periodo_existencia, $this->fecha, $this->existencias, $this->id_laboratorio, $this->precio_sin_iva, $this->precio_con_iva, $this->costo_unitario, $this->descuento, $this->precio_con_descuento, $this->precio_opcional1, $this->precio_opcional2, $this->precio_opcional3, $this->precio_opcional4, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAllDetalle()
    {
        $sql = 'SELECT dp.id_detalle_producto, p.presentacion, dp.ubicacion, dp.minimo, dp.maximo, dp.marca, dp.periodo_existencia, dp.fecha,dp.existencia, l.nombre_laboratorio, precio_con_iva, costo_unitario, dp.precio_con_descuento
        FROM tb_detalle_productos dp
        INNER JOIN tb_laboratorios l USING(id_laboratorio)
        INNER JOIN tb_productos p USING(id_producto)
        WHERE dp.id_producto = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    public function readOneDetalle()
    {
        $sql = 'SELECT id_detalle_producto, ubicacion, minimo, maximo, marca, periodo_existencia, fecha, existencia, id_laboratorio, precio_sin_iva, precio_con_iva, costo_unitario, descuento, precio_con_descuento, precio_opcional1, precio_opcional2, precio_opcional3, precio_opcional4, id_producto
                FROM tb_detalle_productos
                INNER JOIN tb_laboratorios USING(id_laboratorio)
                INNER JOIN tb_productos USING(id_producto) 
                WHERE id_detalle_producto = ?';
        $params = array($this->id_detalle_producto);
        return Database::getRow($sql, $params);
    }

    public function updateRowDetalle()
    {
        $sql = 'UPDATE tb_detalle_productos 
                SET  ubicacion = ?, minimo = ?, maximo = ?, marca = ?, periodo_existencia = ?, fecha = ?, existencia = ?, id_laboratorio = ?, precio_sin_iva = ?, precio_con_iva = ?, costo_unitario = ?, precio_con_descuento = ?, precio_opcional1 = ?, precio_opcional2 = ?, precio_opcional3 = ?, precio_opcional4 = ?
                WHERE id_detalle_producto = ?';
        $params = array($this->ubicacion, $this->minimo, $this->maximo, $this->marca, $this->periodo_existencia, $this->fecha, $this->existencias, $this->id_laboratorio, $this->precio_sin_iva, $this->precio_con_iva, $this->costo_unitario,  $this->precio_con_descuento, $this->precio_opcional1, $this->precio_opcional2, $this->precio_opcional3, $this->precio_opcional4, $this->id_detalle_producto);
        return Database::executeRow($sql, $params);
    }

    public function deleteRowDetalle()
    {
        $sql = 'DELETE FROM tb_detalle_productos
                WHERE id_detalle_producto = ?';
        $params = array($this->id_detalle_producto);
        return Database::executeRow($sql, $params);
    }
    public function getProductosConMasExistencias()
    {
        $sql = 'SELECT p.nombre, dp.existencia 
            FROM tb_productos p
            JOIN tb_detalle_productos dp ON p.id_producto = dp.id_producto
            ORDER BY dp.existencia DESC 
            LIMIT 5';
        $params = null;
        return Database::getRows($sql, $params);
    }
    

    //Acciones dentro de ventas
    public function getProductByCode()
    {
            $sql = 'SELECT 
        p.codigo,
        p.nombre,
        p.descripcion,
        d.precio_con_iva AS precio
    FROM 
        tb_productos p
    INNER JOIN 
        tb_detalle_productos d
    ON 
        p.id_producto = d.id_producto
                WHERE codigo = ?';
        $params = array($this->codigo);
        return Database::getRow($sql, $params);
    }
}
