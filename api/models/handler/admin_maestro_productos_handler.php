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
    protected $ubicaion = null;
    protected $minimo = null;
    protected $maximo = null;
    protected $marca = null;
    protected $fecha = null;
    protected $id_laboratorio = null;

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
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento, precio_con_iva, costo_unitario
                FROM tb_productos
                WHERE codigo LIKE ? OR nombre LIKE ? OR descripcion LIKE ? OR fecha_vencimiento LIKE ? OR precio_con_iva LIKE ? OR costo_unitario LIKE ?
                ORDER BY nombre';
        $params = array($value, $value, $value, $value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_productos(imagen, codigo, nombre, descripcion, fecha_vencimiento, precio_sin_iva, precio_con_iva, costo_unitario)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->imagen, $this->codigo, $this->nombre, $this->descripcion, $this->fecha_vencimiento, $this->precio_sin_iva, $this->precio_con_iva, $this->costo_unitario);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento, precio_con_iva, costo_unitario
                FROM tb_productos
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, imagen, codigo, nombre, descripcion, fecha_vencimiento,precio_sin_iva, precio_con_iva, costo_unitario
                FROM tb_productos
                WHERE id_admin = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_productos
                SET imagen = ?, codigo = ?, nombre = ?, descripcion = ?, fecha_vencimiento = ?, precio_sin_iva = ?, precio_con_iva = ?, costo_unitario = ?
                WHERE id_producto = ?';
        $params = array($this->imagen, $this->codigo, $this->nombre, $this->descripcion, $this->fecha_vencimiento, $this->precio_sin_iva, $this->precio_con_iva, $this->costo_unitario, $this->id);
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
}
