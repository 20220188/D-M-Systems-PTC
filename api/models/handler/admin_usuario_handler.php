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
    public function searchRows($value)
    {
        $sql = 'SELECT id_proveedor, codigo_proveedor, nombre_proveedor, pais_proveedor, giro_negocio_proveedor, dui_proveedor, nombre_comercial_proveedor, fecha_proveedor, nit_proveedor, telefono_proveedor, contacto_proveedor, direccion_proveedor, departamento_proveedor, municipio_proveedor
                FROM proveedor
                WHERE nombre_proveedor LIKE ? OR codigo_proveedor LIKE ?
                ORDER BY nombre_proveedor';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    public function readAll()
    {
        $sql = 'SELECT id_usuario, usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario
                FROM tb_usuarios
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
                SET usuario = ?, clave = ?, correo = ?, nombre = ?, DUI = ?, telefono = ?, id_nivel_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->usuario, $this->clave, $this->correo, $this->nombre, $this->DUI, $this->telefono, $this->id_nivel_usuario, $this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::executeRow($sql, $params);
    }
}
?>
