<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_maestros_proveedores_handler.php');
/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla PRODUCTO.
 */
class ProveedorData extends ProveedorHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;
    private $filename = null;

    /*
     *   Métodos para validar y establecer los datos.
     */
    public function setIdProveedor($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del proveedor es incorrecto';
            return false;
        }
    }

    public function setCodigoProveedor($value)
    {
        if (Validator::validateAlphanumeric($value)) {
            $this->codigo_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El código del proveedor es incorrecto';
            return false;
        }
    }

    public function setNombreProveedor($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 100)) {
            $this->nombre_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El nombre del proveedor es incorrecto';
            return false;
        }
    }

    public function setPaisProveedor($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->pais_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El país del proveedor es incorrecto';
            return false;
        }
    }

    public function setGiroNegocioProveedor($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 100)) {
            $this->giro_negocio_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El giro de negocio del proveedor es incorrecto';
            return false;
        }
    }

    public function setDuiProveedor($value)
    {
        if (Validator::validateDUI($value)) {
            $this->dui_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El DUI del proveedor es incorrecto';
            return false;
        }
    }

    public function setNombreComercialProveedor($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 100)) {
            $this->nombre_comercial_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El nombre comercial del proveedor es incorrecto';
            return false;
        }
    }

    public function setDUI($value)
    {
        if (!Validator::validateDUI($value)) {
            $this->data_error = 'El DUI debe tener el formato ########-#';
            return false;
        }  else {
            $this-> dui_proveedor = $value;
            return true;
        }
    }

    public function setFechaProveedor($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'La fecha del proveedor es incorrecta';
            return false;
        }
    }

    public function setNitProveedor($value)
    {
        if (Validator::validateDUI($value)) {
            $this->nit_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El NIT del proveedor es incorrecto';
            return false;
        }
    }

    public function setTelefonoProveedor($value)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El teléfono del proveedor es incorrecto';
            return false;
        }
    }

    public function setContactoProveedor($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 100)) {
            $this->contacto_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El contacto del proveedor es incorrecto';
            return false;
        }
    }

    public function setDireccionProveedor($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 200)) {
            $this->direccion_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'La dirección del proveedor es incorrecta';
           return false;
        }
    }

    public function setDepartamentoProveedor($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->departamento_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El departamento del proveedor es incorrecto';
            return false;
        }
    }

    public function setMunicipioProveedor($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->municipio_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El municipio del proveedor es incorrecto';
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

    public function getFilename()
    {
        return $this->filename;
    }
}
