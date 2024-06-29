<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class MaestroProductosHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */

    /*Atributos para la seccion de informacion obligatoria*/ 
    protected $id = null;
    protected $codigo = null;
    protected $descripcion = null;
    protected $nombre = null;
    protected $precio_sin_iva = null;
    protected $precio_con_iva = null;
    protected $costo_unitario = null;
    protected $fecha_vencimiento = null;

    /*Atributos para la seccion de detalles de productos*/
    protected $presentacion = null;
    protected $ubicaion = null;
    protected $minimo = null;
    protected $maximo = null;
    protected $marca = null;
    protected $fecha = null;
    protected $id_laboratorio = null;

    /*Atributos para la seccion de detalles de precios*/
    protected $descuento = null;
    protected $precio_con_descuento = null;
    protected $precio_opcional1 = null;
    protected $precio_opcional2 = null;
    protected $precio_opcional3 = null;
    protected $precio_opcional4 = null;

    /*Atributos para la seccion de detalles de informacion de solo lectura*/
    protected $fecha_ultima_compra = null;
    protected $entradas = null;
    protected $salidas = null;
    protected $precio_ultima_compra = null;
    protected $costo_total = null;
    protected $id_proveedor = null;
    protected $existencias_actuales = null;
    protected $id_iva = null;

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_admin, alias_admin, clave_admin
                FROM tb_administradores
                WHERE  alias_admin = ?';
        $params = array($username);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_admin'])) {
            $_SESSION['idAdministrador'] = $data['id_admin'];
            $_SESSION['aliasAdministrador'] = $data['alias_admin'];
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_admin
                FROM tb_administradores
                WHERE id_admin = ?';
        $params = array($_SESSION['idAdministrador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_admin'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_administradores
                SET clave_admin = ?
                WHERE id_admin = ?';
        $params = array($this->clave, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, correo_admin, alias_admin
                FROM tb_administradores
                WHERE id_admin = ?';
        $params = array($_SESSION['idAdministrador']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE tb_administradores
                SET nombre_admin = ?, apellido_admin = ?, correo_admin = ?, alias_admin = ?
                WHERE id_admin = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, correo_admin, alias_admin
                FROM tb_administradores
                WHERE apellido_admin LIKE ? OR nombre_admin LIKE ?
                ORDER BY apellido_admin';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_administradores(nombre_admin, apellido_admin, correo_admin, alias_admin, clave_admin)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $this->clave);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, correo_admin, alias_admin
                FROM tb_administradores
                ORDER BY nombre_admin';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, correo_admin, alias_admin
                FROM tb_administradores
                WHERE id_admin = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_administradores
                SET nombre_admin = ?, apellido_admin = ?, correo_admin = ?
                WHERE id_admin = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_administradores
                WHERE id_admin = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
