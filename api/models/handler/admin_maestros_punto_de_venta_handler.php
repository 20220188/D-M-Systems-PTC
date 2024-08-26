<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 * Class to handle the data operations for the Punto de Venta (Point of Sale) system.
 */
class PuntoDeVentaHandler
{
    /*
     * Attributes for the tb_puntos_venta table.
     */
    protected $id_punto_venta = null;
    protected $punto_venta = null;
    protected $clave = null;

 

    /*
     * Methods for CRUD operations on the tb_puntos_venta table.
     */

    // Method to search points of sale
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_punto_venta, punto_venta, clave
                FROM tb_puntos_venta
                WHERE punto_venta LIKE ? 
                ORDER BY punto_venta';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Method to create a new point of sale
    public function createRow()
    {
        $sql = 'INSERT INTO tb_puntos_venta(punto_venta, clave)
                VALUES(?, ?)';
        $params = array($this->punto_venta, $this->clave);
        return Database::executeRow($sql, $params);
    }

    // Method to read all points of sale
    public function readAll()
    {
        $sql = 'SELECT id_punto_venta, punto_venta
                FROM tb_puntos_venta
                ORDER BY punto_venta';
        return Database::getRows($sql);
    }

    // Method to read a single point of sale by its ID
    public function readOne()
    {
        $sql = 'SELECT id_punto_venta, punto_venta, clave
                FROM tb_puntos_venta
                WHERE id_punto_venta = ?';
        $params = array($this->id_punto_venta);
        return Database::getRow($sql, $params);
    }

    // Method to update an existing point of sale
    public function updateRow()
    {
        $sql = 'UPDATE tb_puntos_venta
                SET punto_venta = ?, clave = ?
                WHERE id_punto_venta = ?';
        $params = array($this->punto_venta, $this->clave, $this->id_punto_venta);
        return Database::executeRow($sql, $params);
    }

    // Method to delete a point of sale
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_puntos_venta
                WHERE id_punto_venta = ?';
        $params = array($this->id_punto_venta);
        return Database::executeRow($sql, $params);
    }

    public function PuntoVentaReport()
    {
        $sql = 'SELECT id_punto_venta, punto_venta 
                FROM tb_puntos_venta
                ORDER BY punto_venta';
        return Database::getRows($sql);
    }


    public function PuntoVentaGrafico(){
        $sql = 'SELECT id_punto_venta, punto_venta
FROM tb_puntos_venta
ORDER BY id_punto_venta DESC
LIMIT 3;
';
        return Database::getRows($sql);
    }

   

   
}
?>
