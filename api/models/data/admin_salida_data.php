<?php
require_once('../../helpers/validator.php');
require_once('../../models/handler/salidas_handler.php');

class SalidasData extends SalidasHandler
{
    private $data_error = null;

    public function setFechaSalida($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_salida = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setCantidad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            $this->data_error = 'La cantidad es incorrecta';
            return false;
        }
    }

    public function setIdCliente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    public function setIdDependiente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_dependiente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del dependiente es incorrecto';
            return false;
        }
    }

    public function setIdProducto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del producto es incorrecto';
            return false;
        }
    }

    public function getDataError()
    {
        return $this->data_error;
    }
}
?>
