<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla proveedor.
*/
class ProveedorHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id_proveedor = null;
    protected $nombre_proveedor = null;

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_proveedor, nombre_proveedor
                FROM tb_proveedores
                WHERE nombre_poveedor LIKE ? 
                ORDER BY nombre_proveedor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_proveedores
                VALUES(?, ?)';
        $params = array($this->nombre_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor
                FROM tb_proveedor
                ORDER BY nombre_proveedor';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor
                FROM tb_proveedor
                WHERE id_proveedor = ?';
        $params = array($this->id_proveedor);
        return Database::getRow($sql, $params);
    }


    public function updateRow()
    {
        $sql = 'UPDATE tb_proveedor
                SET nombre_proveedor = ?
                WHERE id_proveedor = ?';
        $params = array($this->nombre_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_proveedor
                WHERE id_proveedor = ?';
        $params = array($this->id_proveedor);
        return Database::executeRow($sql, $params);
    }


 
   
}