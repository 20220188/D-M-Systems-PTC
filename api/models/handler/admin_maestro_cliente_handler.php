<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class clienteHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $NIT = null;
    protected $NRC = null;
    protected $tipo = null;
    protected $nombrec = null;
    protected $codigo = null;
    protected $direccion = null;
    protected $telefono = null;
    protected $correo = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente, nombre, NIT, NRC, tipo, nombre_comercial, codigo, direccion, telefono, correo
                FROM tb_clientes
                WHERE nombre LIKE ? OR codigo LIKE ? OR NIT LIKE ? 
                ORDER BY nombre';
        $params = array($value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_clientes(nombre, NIT, NRC, tipo, nombre_comercial, codigo, direccion, telefono, correo)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->NIT, $this->NRC, $this->tipo, $this->nombrec, $this->codigo, $this->direccion, $this->telefono, $this->correo);
        return Database::executeRow($sql, $params);
    }

    public function ClientesReport()
    {
        $sql = 'SELECT
    nombre,
    codigo,
    direccion,
    nombre_comercial,
    telefono
    FROM tb_clientes';
        return Database::getRows($sql);
    }


    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre, NIT, NRC, tipo, nombre_comercial, codigo, direccion, telefono, correo
                FROM tb_clientes
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre, NIT, NRC, tipo, nombre_comercial, codigo, direccion, telefono, correo
                FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_clientes
                SET nombre = ?, NIT = ?, NRC = ?, tipo = ?, nombre_comercial = ?, codigo = ?, direccion = ?, telefono = ?, correo = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->NIT, $this->NRC, $this->tipo, $this->nombrec, $this->codigo, $this->direccion, $this->telefono, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
