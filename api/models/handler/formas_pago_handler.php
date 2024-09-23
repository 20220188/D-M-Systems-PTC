<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*	Clase para manejar el comportamiento de los datos de la tabla tb_formas_pago.
*/
class FormasPagoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id_forma_pago = null;
    protected $forma_pago = null;

    /*
    *   Métodos para realizar las operaciones CRUD (create, read, update, and delete).
    */

    // Método para crear una nueva forma de pago
    public function createRow()
    {
        $sql = 'INSERT INTO tb_formas_pago (forma_pago)
                VALUES (?)';
        $params = array($this->forma_pago);
        return Database::executeRow($sql, $params);
    }

    // Método para buscar formas de pago mediante un valor de búsqueda
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_forma_pago, forma_pago
                FROM tb_formas_pago
                WHERE forma_pago LIKE ?
                ORDER BY forma_pago';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para obtener todas las formas de pago
    public function readAll()
    {
        $sql = 'SELECT id_forma_pago, forma_pago
                FROM tb_formas_pago
                ORDER BY forma_pago';
        return Database::getRows($sql);
    }

    // Método para obtener una forma de pago en específico
    public function readOne()
    {
        $sql = 'SELECT id_forma_pago, forma_pago
                FROM tb_formas_pago
                WHERE id_forma_pago = ?';
        $params = array($this->id_forma_pago);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar una forma de pago
    public function updateRow()
    {
        $sql = 'UPDATE tb_formas_pago
                SET forma_pago = ?
                WHERE id_forma_pago = ?';
        $params = array($this->forma_pago, $this->id_forma_pago);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar una forma de pago
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_formas_pago
                WHERE id_forma_pago = ?';
        $params = array($this->id_forma_pago);
        return Database::executeRow($sql, $params);
    }

    // Método para generar un reporte de todas las formas de pago
  
}
?>
