<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/modulo_ventas_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla tb_formas_pago.
 */
class VentasData extends VentasHandler
{
    /*
     * Atributos adicionales.
     */
    private $data_error = null;

public function setIdVenta($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id = $value;
        return true;
    } else {
        $this->data_error = 'El identificador de la venta es incorrecto';
        return false;
    }
}

public function setFechaVenta($value)
{
    if (Validator::validateDate($value)) {
        $this->fecha_venta = $value;
        return true;
    } else {
        $this->data_error = 'La fecha de la venta es incorrecta';
        return false;
    }
}

public function setIdVendedor($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this-> id_dependiente= $value;
        return true;
    } else {
        $this->data_error = 'El identificador del vendedor es incorrecto';
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

public function setFormaPago($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id_forma_pago = $value;
        return true;
    } else {
        $this->data_error = 'El identificador del vendedor es incorrecto';
        return false;
    }
}

public function setIdDocumento($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id_documento = $value;
        return true;
    } else {
        $this->data_error = 'El identificador del vendedor es incorrecto';
        return false;
    }
}

public function setIdTipoDocumento($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id_tipo_documento= $value;
        return true;
    } else {
        $this->data_error = 'El identificador del vendedor es incorrecto';
        return false;
    }
}

public function setIdBodega($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id_bodega = $value;
        return true;
    } else {
        $this->data_error = 'El identificador del vendedor es incorrecto';
        return false;
    }
}

public function setTotalVenta($value)
{
    if (Validator::validateMoney($value)) {
        $this->total_venta = $value;
        return true;
    } else {
        $this->data_error = 'El total de la venta debe ser un valor numérico';
        return false;
    }
}

public function setIvaVenta($value)
{
    if (Validator::validateMoney($value)) {
        $this->iva_venta = $value;
        return true;
    } else {
        $this->data_error = 'El IVA debe ser un valor numérico';
        return false;
    }
}

public function setNotas($value, $min = 0, $max = 250)
{
    if (!Validator::validateString($value)) {
        $this->data_error = 'La nota contienen caracteres prohibidos';
        return false;
    } elseif (Validator::validateLength($value, $min, $max)) {
        $this->nota = $value;
        return true;
    } else {
        $this->data_error = 'Las notas deben tener una longitud máxima de ' . $max . ' caracteres';
        return false;
    }
}


//detalles

public function setIdDetalleVenta($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->id_detalle_venta = $value;
        return true;
    } else {
        $this->data_error = 'El identificador del detalle de venta es incorrecto';
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
public function setCodigo($value, $min = 2, $max = 15)
    {
        if (!Validator::validateNaturalNumber($value)) {
            $this->data_error = 'El codigo debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->codigo = $value;
            return true;
        } else {
            $this->data_error = 'El codigo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

public function setCantidad($value)
{
    if (Validator::validateNaturalNumber($value)) {
        $this->cantidad = $value;
        return true;
    } else {
        $this->data_error = 'La cantidad debe ser un valor numérico';
        return false;
    }
}

public function setPrecioUnitario($value)
{
    if (Validator::validateMoney($value)) {
        $this->precio_unitario = $value;
        return true;
    } else {
        $this->data_error = 'El precio unitario debe ser un valor numérico';
        return false;
    }
}

public function setDescuento($value)
{
    if (Validator::validateMoney($value)) {
        $this->descuento = $value;
        return true;
    } else {
        $this->data_error = 'El descuento debe ser un valor numérico';
        return false;
    }
}

public function setSubtotal($value)
{
    if (Validator::validateMoney($value)) {
        $this->subtotal = $value;
        return true;
    } else {
        $this->data_error = 'El subtotal debe ser un valor numérico';
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