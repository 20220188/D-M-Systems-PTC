<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/admin_maestro_productos_handler.php');
/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla PRODUCTO.
 */
class ProductosData extends ProductosHandler
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

    public function setDescripcion($value, $min = 2, $max = 250)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'La descripción contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->descripcion = $value;
            return true;
        } else {
            $this->data_error = 'La descripción debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setPrecioSinIva($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_sin_iva = $value;
            return true;
        } else {
            $this->data_error = 'El precio sin IVA debe ser un valor numérico';
            return false;
        }
    }



    public function setPrecioConIva($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_con_iva = $value;
            return true;
        } else {
            $this->data_error = 'El precio de venta debe ser un valor numérico';
            return false;
        }
    }

    public function setCostoUnitario($value)
    {
        if (Validator::validateMoney($value)) {
            $this->costo_unitario = $value;
            return true;
        } else {
            $this->data_error = 'El costo unitario debe ser un valor numérico';
            return false;
        }
    }

    public function setExistencias($value)
    {
        if (Validator::validateMoney($value)) {
            $this->existencias = $value;
            return true;
        } else {
            $this->data_error = 'La cantidad de stock debe ser un valor numérico';
            return false;
        }
    }

    public function setFechaVencimiento($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_vencimiento = $value;
            return true;
        } else {
            $this->data_error = 'La fecha de vencimiento es incorrecta';
            return false;
        }
    }

    public function setImagen($file, $filename = null)
    {
        if (Validator::validateImageFile($file, 400)) {
            $this->imagen = Validator::getFileName();
            return true;
        } elseif (Validator::getFileError()) {
            $this->data_error = Validator::getFileError();
            return false;
        } elseif ($filename) {
            $this->imagen = $filename;
            return true;
        } else {
            $this->imagen = 'default.png';
            return true;
        }
    }

    public function setFilename()
    {
        if ($data = $this->readFilename()) {
            $this->filename = $data['imagen'];
            return true;
        } else {
            $this->data_error = 'Producto inexistente';
            return false;
        }
    }

    /*
     *  Métodos para el manejo de la tabla DETALLE_PRODUCTO.
     */

    public function setIdDetalle($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_detalle_producto = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del detalle es incorrecto';
            return false;
        }
    }

    public function setPresentacion($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La presentación debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->presentacion = $value;
            return true;
        } else {
            $this->data_error = 'La presentación debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setUbicacion($value, $min = 2, $max = 250)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La ubicación debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->ubicacion = $value;
            return true;
        } else {
            $this->data_error = 'La ubicación debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setMinimo($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->minimo = $value;
            return true;
        } else {
            $this->data_error = 'El mínimo debe ser un valor numérico';
            return false;
        }
    }

    public function setMaximo($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->maximo = $value;
            return true;
        } else {
            $this->data_error = 'El máximo debe ser un valor numérico';
            return false;
        }
    }

    public function setMarca($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La marca debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->marca = $value;
            return true;
        } else {
            $this->data_error = 'La marca debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setFechaIngreso($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setPeriodoExistencia($value)
    {
        if (Validator::validateDate($value)) {
            $this->periodo_existencia = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }

    public function setIdLaboratorio($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_laboratorio = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del laboratorio es incorrecto';
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

    public function setPrecioConDescuento($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_con_descuento = $value;
            return true;
        } else {
            $this->data_error = 'El precio con descuento debe ser un valor numérico';
            return false;
        }
    }

    public function setPrecioOpcional1($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_opcional1 = $value;
            return true;
        } else {
            $this->data_error = 'El precio opcional 1 debe ser un valor numérico';
            return false;
        }
    }

    public function setPrecioOpcional2($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_opcional2 = $value;
            return true;
        } else {
            $this->data_error = 'El precio opcional 2 debe ser un valor numérico';
            return false;
        }
    }

    public function setPrecioOpcional3($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_opcional3 = $value;
            return true;
        } else {
            $this->data_error = 'El precio opcional 3 debe ser un valor numérico';
            return false;
        }
    }

    public function setPrecioOpcional4($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_opcional4 = $value;
            return true;
        } else {
            $this->data_error = 'El precio opcional 4 debe ser un valor numérico';
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

    public function getFilename()
    {
        return $this->filename;
    }
}
