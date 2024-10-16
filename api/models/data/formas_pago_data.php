<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/formas_pago_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla tb_formas_pago.
 */
class FormaPagoData extends FormasPagoHandler
{
    /*
     * Atributos adicionales.
     */
    private $data_error = null;

    /*
     * Métodos para validar y establecer los datos.
     */
    public function setIdFormaPago($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_forma_pago = $value;
            return true;
        } else {
            $this->data_error = 'El identificador de la forma de pago es incorrecto';
            return false;
        }
    }


    /*
     * Métodos para obtener el valor de los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}
?>
