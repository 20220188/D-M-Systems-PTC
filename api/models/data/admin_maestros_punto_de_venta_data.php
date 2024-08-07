<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_maestros_punto_de_venta_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla tb_puntos_venta.
 */
class PuntoDeVentaData extends PuntoDeVentaHandler
{
    /*
     * Atributos adicionales.
     */
    private $data_error = null;

    /*
     * Métodos para validar y establecer los datos.
     */
    public function setidPuntoVenta($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_punto_venta = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del punto de venta es incorrecto';
            return false;
        }
    }

    public function setnombrePuntoVenta($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El punto de venta debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->punto_venta = $value;
            return true;
        } else {
            $this->data_error = 'El punto de venta debe tener una longitud entre ' . $min . ' y ' . $max;
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

    /*
     * Métodos para obtener el valor de los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}
?>
