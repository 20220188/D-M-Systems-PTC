<?php
// Se incluye la clase para validar los datos de salida.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_salida_handler.php');
/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla PRODUCTO.
 */
class SalidasData extends SalidasHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;


    /*
     *   Métodos para validar y establecer los datos.
     */

    // Métodos para el manejo de la tabla PRODUCTO.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la salida es incorrecto';
            
            return false;
        }
    }

    public function setNota($value, $min = 2, $max = 250)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La nota de la salida debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nota = $value;
            return true;
        } else {
            $this->data_error = 'La nota de la salida debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_salida = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setTipoSalida($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El tipo de salida debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->tipo_salida = $value;
            return true;
        } else {
            $this->data_error = 'El tipo de salida debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setNumeroSalida($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->numero_salida = $value;
            return true;
        } else {
            $this->data_error = 'El número de salida es incorrecto';
            return false;
        }
    }

    public function setEntrega($value)
    {
        if (Validator::validateAlphanumeric($value)) {
            $this->entrega = $value;
            return true;
        } else {
            $this->data_error = 'La entrega es incorrecto';
            return false;
        }
    }

    public function setCantidad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            $this->data_error = 'La cantidad de salida es incorrecta';
            return false;
        }
    }

    public function setCliente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    public function setDependiente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_dependiente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del dependiente es incorrecto';
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
