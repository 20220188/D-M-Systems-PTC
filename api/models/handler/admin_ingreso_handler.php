<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class EntradasHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */

    /*Atributos para la seccion de informacion*/
    protected $id = null;
    protected $nota = null;
    protected $fecha = null;
    protected $tipo_entrada = null;
    protected $numero_entrada = null;
    protected $id_detalle_producto = null;
    protected $filtro = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    /*
    *  Método para los registros de la tabla de laboratorios.
    */

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_entrada, nota, fecha, tipo_entrada, numero_entrada
                FROM tb_entradas
                WHERE numero_entrada LIKE ?
                ORDER BY numero_entrada ASC';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_entradas(nota, fecha,tipo_entrada,numero_entrada)
                VALUES(?, ?,?,?)';
        $params = array($this->nota, $this->fecha, $this->tipo_entrada, $this->numero_entrada);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_entrada , nota, fecha, tipo_entrada, numero_entrada 
                FROM tb_entradas
                ORDER BY numero_entrada ASC';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_entrada, nota, fecha, tipo_entrada, numero_entrada
                FROM tb_entradas
                WHERE id_entrada = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_entradas
                SET nota = ?, fecha = ?, tipo_entrada = ?, numero_entrada = ?
                WHERE id_entrada = ?';
        $params = array($this->nota, $this->fecha, $this->tipo_entrada, $this->numero_entrada, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_entradas
                WHERE id_entrada = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para los registros de la tabla de detalles de productos.
    */

    public function reportNE()
    {
        $sql = 'SELECT id_entrada, numero_entrada, fecha, tipo_entrada, nota
                FROM tb_entradas
                WHERE ';
        
        $params = array();
        
        if ($this->filtro === 'numeroEntrada' && $this->numero_entrada !== null) {
            $sql .= 'numero_entrada = ?';
            $params[] = $this->numero_entrada;
        } elseif ($this->filtro === 'fechaEntrada' && $this->fecha !== null) {
            $sql .= 'fecha = ?';
            $params[] = $this->fecha;
        } else {
            // Si no hay filtro válido, retorna false
            return false;
        }
        
        // Añade depuración
        //echo "SQL: $sql<br>";
        //echo "Params: ";
        //print_r($params);
        //echo "<br>";

        $result = Database::getRows($sql, $params);
        
        // Añade depuración
        //echo "Resultado de la consulta: ";
        //var_dump($result);
        //echo "<br>";

        return $result;
    }

}
