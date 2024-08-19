<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_modulo_compras_handler.php');

/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla COMPRAS.
 */
class ComprasData extends ComprasHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;

    /*
     *   Métodos para validar y establecer los datos.
     */
    public function setFactura($value, $min = 1, $max = 20)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La factura debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->factura = $value;
            return true;
        } else {
            $this->data_error = 'La factura debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setSerie($value, $min = 1, $max = 10)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La serie debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->serie = $value;
            return true;
        } else {
            $this->data_error = 'La serie debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setNota($value, $min = 0, $max = 250)
    {
        if (Validator::validateLength($value, $min, $max)) {
            $this->nota = $value;
            return true;
        } else {
            $this->data_error = 'La nota debe tener una longitud máxima de ' . $max . ' caracteres';
            return false;
        }
    }

    public function setSeriePersepcion($value, $min = 1, $max = 10)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La serie de percepción debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->seriePersepcion = $value;
            return true;
        } else {
            $this->data_error = 'La serie de percepción debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setNIT($value)
    {
        if (Validator::validateAlphanumeric($value)) {
            $this->NIT = $value;
            return true;
        } else {
            $this->data_error = 'El NIT debe ser un valor alfanumérico';
            return false;
        }
    }

    public function setIdProducto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idProducto = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del producto es incorrecto';
            return false;
        }
    }

    public function setIdFormaPago($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idFormaPago = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la forma de pago es incorrecto';
            return false;
        }
    }

    public function setIdBodega($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idBodega = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la bodega es incorrecto';
            return false;
        }
    }

    public function setIdDocumento($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idDocumento = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del documento es incorrecto';
            return false;
        }
    }

    public function setIdTipoDocumento($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idTipoDocumento = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del tipo de documento es incorrecto';
            return false;
        }
    }

    /*
     *  Métodos para obtener los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}