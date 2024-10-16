<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/bodega_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla BODEGA.
 */
class BodegaData extends BodegaHandler
{
    private $data_error = null;

    // Métodos para el manejo de la tabla BODEGA.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la bodega es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El nombre debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setUbicacion($value, $min = 2, $max = 100)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'La ubicación contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->ubicacion = $value;
            return true;
        } else {
            $this->data_error = 'La ubicación debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function getDataError()
    {
        return $this->data_error;
    }
}
