<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class DependienteHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */

    /*Atributos para la seccion de informacion obligatoria*/
    protected $id = null;
    protected $codigo = null;
    protected $nombre = null;
    


 



    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    /*
    *  Método para los registros de la tabla de productos.
    */

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT 	id_dependiente, codigo, nombre_dependiente 
                FROM tb_dependientes
                WHERE codigo LIKE ? OR nombre_dependiente  LIKE ? 
                ORDER BY nombre_dependiente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_dependientes(codigo, nombre_dependiente)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->codigo, $this->nombre );
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_dependiente, codigo, nombre_dependiente 
                FROM tb_dependientes
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_dependiente, codigo, nombre_dependiente
                FROM tb_dependientes
                WHERE id_dependiente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_dependientes
                SET codigo = ?, nombre_dependiente = ?
                WHERE id_dependiente = ?';
        $params = array($this->codigo, $this->nombre, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_dependientes
                WHERE id_dependiente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }


    /*
    *  Método para los registros de la tabla de detalles de productos.
    */
}
