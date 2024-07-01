<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/usuario_handler.php');

/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla tb_usuarios.
 */
class UsuarioData extends UsuarioHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;

    /*
     *   Métodos para validar y establecer los datos.
     */
    public function setIdUsuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del usuario es incorrecto';
            return false;
        }
    }

    public function setUsuario($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 10)) {
            $this->usuario = $value;
            return true;
        } else {
            $this->data_error = 'El nombre de usuario es incorrecto';
            return false;
        }
    }

    public function setClave($value)
    {
        if (Validator::validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            $this->data_error = 'La clave es incorrecta';
            return false;
        }
    }

    public function setCorreo($value)
    {
        if (Validator::validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            $this->data_error = 'El correo es incorrecto';
            return false;
        }
    }

    public function setNombre($value)
    {
        if (Validator::validateAlphabetic($value, 1, 25)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre es incorrecto';
            return false;
        }
    }

    public function setDUI($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 10)) {
            $this->DUI = $value;
            return true;
        } else {
            $this->data_error = 'El DUI es incorrecto';
            return false;
        }
    }

    public function setTelefono($value)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono = $value;
            return true;
        } else {
            $this->data_error = 'El teléfono es incorrecto';
            return false;
        }
    }

    public function setIdNivelUsuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_nivel_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del nivel de usuario es incorrecto';
            return false;
        }
    }

    /*
     *  Métodos para obtener el valor de los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}
?>
