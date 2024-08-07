<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class AdministradorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $alias = null;
    protected $clave = null;
    protected $DUI = null;
    protected $telefono = null;

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_usuario, usuario, clave
                FROM tb_usuarios
                WHERE  usuario = ?';
        $params = array($username);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave'])) {
            $_SESSION['idAdministrador'] = $data['id_usuario'];
            $_SESSION['aliasAdministrador'] = $data['usuario'];
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['idAdministrador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_usuarios
                SET clave = ?
                WHERE id_usuario = ?';
        $params = array($this->clave, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    //editar de aqui para abajo
    public function readProfile()
    {
        $sql = 'SELECT id_usuario, nombre, telefono, correo, usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['idAdministrador']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE tb_usuarios
                SET nombre = ?, telefono = ?, correo = ?, usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->telefono, $this->correo, $this->alias, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_usuario, nombre, telefono, correo, usuario
                FROM tb_usuarios
                WHERE telefono LIKE ? OR nombre LIKE ?
                ORDER BY nombre';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        // Insertar el administrador con el nivel de usuario correspondiente
        $sql = 'INSERT INTO tb_usuarios(nombre, usuario, correo, clave, DUI, telefono, id_nivel_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->alias, $this->correo, $this->clave, $this->DUI, $this->telefono, 1); // ID de nivel de usuario = 1
        return Database::executeRow($sql, $params);
    }

    public function createAdminApp()
    {
        // Insertar el administrador con el nivel de usuario correspondiente
        $sql = 'INSERT INTO tb_usuarios(nombre, usuario, correo, clave, DUI, telefono, id_nivel_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->alias, $this->correo, $this->clave, $this->DUI, $this->telefono, 1); // ID de nivel de usuario = 1
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre, correo, usuario
                FROM tb_usuarios
                ORDER BY nombre';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre, correo, usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
//seguir despues
    public function updateRow()
    {
        $sql = 'UPDATE tb_usuarios
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM administrador
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_usuario
                FROM tb_usuarios
                WHERE DUI = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }
}
