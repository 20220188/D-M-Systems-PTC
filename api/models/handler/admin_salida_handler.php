<?php
require_once('../../helpers/database.php');

class SalidasHandler
{
    protected $id = null;
    protected $numero_salida = null;
    protected $fecha_salida = null;
    protected $entrega = null;
    protected $tipo_salida = null;
    protected $cantidad = null;
    protected $nota = null;
    protected $id_cliente = null;
    protected $id_dependiente = null;


  

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_salida, numero_salida, fecha, entrega, tipo_salida, cantidad_salida,nota, nombre, nombre_dependiente
                FROM tb_salidas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_dependientes USING(id_dependiente)
                WHERE numero_salida LIKE ? OR tipo_salida LIKE ? OR cantidad_salida LIKE ? OR nombre LIKE ? OR nombre_dependiente LIKE ?
                ORDER BY numero_salida ASC';
        $params = array($value, $value, $value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_salidas(numero_salida, fecha,entrega,tipo_salida,id_cliente, id_dependiente,nota, cantidad_salida)
                VALUES(?, ?,?,?,?,?,?,?)';
        $params = array($this->numero_salida, $this->fecha_salida, $this->entrega, $this->tipo_salida, $this->id_cliente, $this->id_dependiente, $this->nota, $this->cantidad);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_salida, numero_salida, fecha, tipo_salida, cantidad_salida, nombre, nombre_dependiente
                FROM tb_salidas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_dependientes USING(id_dependiente)
                ORDER BY numero_salida ASC';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_salida, numero_salida, fecha, entrega, tipo_salida, cantidad_salida,nota, id_cliente, id_dependiente 
                FROM tb_salidas
                WHERE id_salida = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_salidas
                SET numero_salida = ?, fecha = ?, entrega = ?, tipo_salida = ?, id_cliente = ?, id_dependiente = ?, nota = ?, cantidad_salida = ?
                WHERE id_salida = ?';
        $params = array($this->numero_salida, $this->fecha_salida, $this->entrega, $this->tipo_salida, $this->id_cliente, $this->id_dependiente, $this->nota, $this->cantidad, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_salidas
                WHERE id_salida = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para los registros de la tabla de detalles de productos.
    */

    public function reportNS()
    {
        $sql = 'SELECT id_salida, numero_salida, fecha, tipo_salida, cantidad_salida, nombre, nombre_dependiente
                FROM tb_salidas
                INNER JOIN tb_clientes USING(id_cliente)
                INNER JOIN tb_dependientes USING(id_dependiente)
                WHERE numero_salida = ?';
        $params = array($this->numero_salida);
        return Database::getRows($sql, $params);
    }
}


?>