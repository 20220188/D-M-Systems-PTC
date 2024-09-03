<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class LaboratoriosHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */

    /*Atributos para la seccion de informacion*/
    protected $id = null;
    protected $codigo = null;
    protected $nombre = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    /*
    *  Método para los registros de la tabla de laboratorios.
    */

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_laboratorio, codigo, nombre_laboratorio
                FROM tb_laboratorios
                WHERE codigo LIKE ? OR nombre_laboratorio LIKE ? 
                ORDER BY nombre_laboratorio';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_laboratorios(codigo, nombre_laboratorio)
                VALUES(?, ?)';
        $params = array($this->codigo, $this->nombre);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_laboratorio,nombre_laboratorio, codigo 
                FROM tb_laboratorios
                ORDER BY nombre_laboratorio';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_laboratorio, codigo, nombre_laboratorio
                FROM tb_laboratorios
                WHERE id_laboratorio = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_laboratorios
                SET codigo = ?, nombre_laboratorio = ?
                WHERE id_laboratorio = ?';
        $params = array($this->codigo, $this->nombre, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_laboratorios
                WHERE id_laboratorio = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
 *   Método para generar el reporte de laboratorios.
 */
    public function reporteLaboratorios()
    {
        $sql = 'SELECT id_laboratorio, codigo, nombre_laboratorio
            FROM tb_laboratorios
            ORDER BY nombre_laboratorio';
        $params = array();
        return Database::getRows($sql, $params);
    }

    public function getUltimosLaboratorios()
    {
        $sql = 'SELECT id_laboratorio, codigo, nombre_laboratorio 
            FROM tb_laboratorios 
            ORDER BY id_laboratorio DESC 
            LIMIT 3';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
