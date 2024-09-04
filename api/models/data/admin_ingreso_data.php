<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_ingreso_handler.php');
/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla PRODUCTO.
 */
class EntradasData extends EntradasHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;
    private $filename = null;

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
            $this->data_error = 'El identificador de la entrada es incorrecto';

            return false;
        }
    }

    public function setNota($value, $min = 2, $max = 250)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La nota de la entrada debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nota = $value;
            return true;
        } else {
            $this->data_error = 'La nota de la entrada debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setTipoEntrada($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El tipo de entrada debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->tipo_entrada = $value;
            return true;
        } else {
            $this->data_error = 'El tipo de entrada debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }


    public function setNumeroEntrada($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->numero_entrada = $value;
            return true;
        } else {
            $this->numero_entrada = null;
            $this->data_error = 'El número de entrada es incorrecto';
            return false;
        }
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->fecha = null;
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setFiltro($value)
    {
        if ($value === 'numeroEntrada' || $value === 'fechaEntrada') {
            $this->filtro = $value;
            return true;
        } else {
            $this->filtro = null;
            return false;
        }
    }

    public function getFiltro()
    {
        return $this->filtro;
    }



    /*
     *  Métodos para obtener los atributos adicionales.
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
