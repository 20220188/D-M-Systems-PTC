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
        $sql = 'SELECT id_usuario, usuario, clave, ultimo_cambio_clave
                 FROM tb_usuarios
                 WHERE usuario = ?';
        $params = array($username);
        $data = Database::getRow($sql, $params);

        // Depuración: Verifica si se obtuvo algún dato
        if ($data) {
            // Depuración: Verifica el hash de la contraseña
            if (password_verify($password, $data['clave'])) {
                $_SESSION['idAdministrador'] = $data['id_usuario'];
                $_SESSION['aliasAdministrador'] = $data['usuario'];

                // Generar y almacenar código 2FA
                $codigo2FA = $this->generar2FACode($data['id_usuario']);

                return [
                    'status' => true,
                    'id_usuario' => $data['id_usuario'],
                    'codigo2FA' => $codigo2FA,
                    'ultimo_cambio_clave' => $data['ultimo_cambio_clave']
                ];
            } else {
                // Contraseña incorrecta
                return ['status' => false, 'message' => 'Contraseña incorrecta'];
            }
        }
        // Usuario no encontrado
        return ['status' => false, 'message' => 'Usuario no encontrado'];
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
    //actualice el array con el id
    public function changePassword()
    {
        $sql = 'UPDATE tb_usuarios
                SET clave = ?, ultimo_cambio_clave = CURRENT_TIMESTAMP
                WHERE id_usuario = ?';
        //aqui se hace la inyeccion de array
        $params = array($this->clave, $_SESSION['idAdministrador']);

        // Ejecutar el cambio de contraseña
        if (Database::executeRow($sql, $params)) {
            // Actualizar la fecha del último cambio de contraseña
            return $this->updateLastPasswordChange();
        }
        return false;
    }
    //este metodo sirve para los noventa dias de expiracion de contra
    public function updateLastPasswordChange()
    {
        $sql = 'UPDATE tb_usuarios SET ultimo_cambio_clave = NOW() WHERE id_usuario = ?';
        return Database::executeRow($sql, array($_SESSION['idAdministrador'])); // Usar el ID de usuario de la sesión
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
                SET nombre = ?, telefono = ?, correo = ?, usuario = ?
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

    // Genera un código de 2FA y lo guarda en la base de datos.
    private function generar2FACode($id_administrador)
    {
        // Genera un código aleatorio de 6 dígitos.
        $codigo = sprintf("%06d", mt_rand(1, 999999));
        // Actualiza la tabla de administradores con el código de 2FA y su tiempo de expiración (5 minutos).
        $sql = "UPDATE tb_usuarios SET codigo_2fa = ?, expiracion_2fa = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id_usuario = ?";
        $params = array($codigo, $id_administrador);
        Database::executeRow($sql, $params);

        return $codigo;
    }
    // Verifica si el código de 2FA proporcionado es correcto.
    public function verify2FACode($id_administrador, $codigo)
    {
        // Consulta el código de 2FA y su tiempo de expiración de la base de datos.
        $sql = 'SELECT codigo_2fa, expiracion_2fa FROM tb_usuarios WHERE id_usuario = ?';
        $params = array($id_administrador);
        $data = Database::getRow($sql, $params);

        // Verifica si el código es correcto y no ha expirado.
        if ($data && $data['codigo_2fa'] == $codigo && new DateTime() < new DateTime($data['expiracion_2fa'])) {
            $sql = 'UPDATE tb_usuarios SET codigo_2fa = NULL, expiracion_2fa = NULL WHERE id_usuario = ?';
            Database::executeRow($sql, array($id_administrador));
            return true;
        }
        return false; // El código es incorrecto o ha expirado.
    }

    // Obtiene el correo electrónico del administrador por su ID.
    public function getEmailById($id_administrador)
    {
        $sql = 'SELECT correo FROM tb_usuarios WHERE id_usuario = ?';
        $params = array($id_administrador);
        $data = Database::getRow($sql, $params);
        return $data ? $data['correo'] : null;
    }

    // Obtiene el alias (nombre de usuario) del administrador por su ID.
    public function getAliasById($id_administrador)
    {
        $sql = 'SELECT usuario FROM tb_usuarios WHERE id_usuario = ?';
        $params = array($id_administrador);
        $data = Database::getRow($sql, $params);
        return $data ? $data['usuario'] : null;
    }
}
