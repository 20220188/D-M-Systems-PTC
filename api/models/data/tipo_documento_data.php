<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/tipo_documento_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla TIPO_DOCUMENTO.
 */
class TipoDocumentoData extends TipoDocumentoHandler
{
    private $data_error = null;

    // Métodos para el manejo de la tabla TIPO_DOCUMENTO.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del tipo de documento es incorrecto';
            return false;
        }
    }

  

    public function getDataError()
    {
        return $this->data_error;
    }
}
