<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*	Clase para manejar el comportamiento de los datos de la tabla tb_usuarios.
*/
class UsuarioHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id_usuario = null;
    protected $usuario = null;
    protected $clave = null;
    protected $correo = null;
    protected $nombre = null;
    protected $DUI = null;
    protected $telefono = null;
    protected $id_nivel_usuario = null;

    /*
    *   Métodos para realizar las operaciones CRUD (create, read, update, and delete).
    */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_usuarios (usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->usuario, $this->clave, $this->correo, $this->nombre, $this->DUI, $this->telefono, $this->id_nivel_usuario);
        return Database::executeRow($sql, $params);
    }
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT u.id_usuario, u.usuario, u.clave, u.correo, u.nombre, u.DUI, u.telefono, u.id_nivel_usuario, nu.tipo_usuario
                FROM tb_usuarios u
                INNER JOIN tb_niveles_usuarios nu ON u.id_nivel_usuario = nu.id_nivel_usuario
                WHERE u.nombre LIKE ? OR nu.tipo_usuario LIKE ?
                ORDER BY u.nombre';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }
    public function readAll()
    {
        $sql = 'SELECT id_usuario, usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario, tipo_usuario
                FROM tb_usuarios
                INNER JOIN tb_niveles_usuarios USING(id_nivel_usuario)
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_usuarios
                SET usuario = ?, correo = ?, nombre = ?, DUI = ?, telefono = ?, id_nivel_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->usuario, $this->correo, $this->nombre, $this->DUI, $this->telefono, $this->id_nivel_usuario, $this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function readAllNiveles()
    {
        $sql = 'SELECT  id_nivel_usuario,tipo_usuario
                FROM tb_niveles_usuarios
                ORDER BY tipo_usuario';
        return Database::getRows($sql);
    }

    public function checkDuplicateDUI($value)
    {
        $sql = 'SELECT id_usuario
                FROM tb_usuarios
                WHERE DUI = ?';
        $params = array($value);
        return Database::getRow($sql, $params);
    }

    public function checkDuplicateCorreo($value)
    {
        $sql = 'SELECT id_usuario
                FROM tb_usuarios
                WHERE correo = ?';
        $params = array($value);
        return Database::getRow($sql, $params);
    }

    public function UsuarioReport()
    {
        $sql = 'SELECT
    usuario,
    nombre,
    telefono,
    correo,
    DUI,
    id_nivel_usuario
    FROM tb_usuarios';
        return Database::getRows($sql);
    }

    public function UsuarioReportNivel($id_nivel_usuario)
    {
        $sql = 'SELECT
            usuario,
    nombre,
    telefono,
    correo,
    DUI,
    id_nivel_usuario
    FROM tb_usuarios
    WHERE id_nivel_usuario = ?';

        $params = array($id_nivel_usuario); // El ID del usuario pasado como parámetro
        return Database::getRows($sql, $params);
    }
}
