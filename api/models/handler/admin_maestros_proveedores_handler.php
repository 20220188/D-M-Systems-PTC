<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/**
 * Clase para manejar el comportamiento de los datos de la tabla proveedor.
 */
class ProveedorHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id_proveedor = null;
    protected $codigo_proveedor = null;
    protected $nombre_proveedor = null;
    protected $pais_proveedor = null;
    protected $giro_negocio_proveedor = null;
    protected $dui_proveedor = null;
    protected $nombre_comercial_proveedor = null;
    protected $fecha_proveedor = null;
    protected $nit_proveedor = null;
    protected $telefono_proveedor = null;
    protected $contacto_proveedor = null;
    protected $direccion_proveedor = null;
    protected $departamento_proveedor = null;
    protected $municipio_proveedor = null;

    /*
    *   Métodos para realizar las operaciones CRUD (buscar, crear, leer, actualizar y eliminar).
    */
    public function searchRows($value)
    {
        $value = '%' . $value . '%';
        $sql = 'SELECT id_proveedor, codigo_proveedor, nombre_proveedor, pais_proveedor, giro_negocio_proveedor, dui_proveedor, nombre_comercial_proveedor, fecha_proveedor, nit_proveedor, telefono_proveedor, contacto_proveedor, direccion_proveedor, departamento_proveedor, municipio_proveedor
                FROM tb_proveedores
                WHERE nombre_proveedor LIKE ? OR codigo_proveedor LIKE ? 
                ORDER BY nombre_proveedor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_proveedores (codigo_proveedor, nombre_proveedor, pais_proveedor, giro_negocio_proveedor, dui_proveedor, nombre_comercial_proveedor, fecha_proveedor, nit_proveedor, telefono_proveedor, contacto_proveedor, direccion_proveedor, departamento_proveedor, municipio_proveedor)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->codigo_proveedor, $this->nombre_proveedor, $this->pais_proveedor, $this->giro_negocio_proveedor, $this->dui_proveedor, $this->nombre_comercial_proveedor, $this->fecha_proveedor, $this->nit_proveedor, $this->telefono_proveedor, $this->contacto_proveedor, $this->direccion_proveedor, $this->departamento_proveedor, $this->municipio_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_proveedor, codigo_proveedor, nombre_proveedor, giro_negocio_proveedor, telefono_proveedor
                FROM tb_proveedores
                ORDER BY nombre_proveedor';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_proveedor, codigo_proveedor, nombre_proveedor, pais_proveedor, giro_negocio_proveedor, dui_proveedor, nombre_comercial_proveedor, fecha_proveedor, nit_proveedor, telefono_proveedor, contacto_proveedor, direccion_proveedor, departamento_proveedor, municipio_proveedor
                FROM tb_proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id_proveedor);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_proveedores
                SET codigo_proveedor = ?, nombre_proveedor = ?, pais_proveedor = ?, 
                giro_negocio_proveedor = ?, dui_proveedor = ?, nombre_comercial_proveedor = ?,
                fecha_proveedor = ?, nit_proveedor = ?, telefono_proveedor = ?, 
                contacto_proveedor = ?, direccion_proveedor = ?, departamento_proveedor = ?, 
                municipio_proveedor = ?
                WHERE id_proveedor = ?';
        $params = array($this->codigo_proveedor, $this->nombre_proveedor, $this->pais_proveedor, $this->giro_negocio_proveedor, $this->dui_proveedor, $this->nombre_comercial_proveedor, $this->fecha_proveedor, $this->nit_proveedor, $this->telefono_proveedor, $this->contacto_proveedor, $this->direccion_proveedor, $this->departamento_proveedor, $this->municipio_proveedor, $this->id_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id_proveedor);
        return Database::executeRow($sql, $params);
    }

    public function ProveedorReport()
    {
        $sql = 'SELECT nombre_proveedor, nombre_comercial_proveedor, telefono_proveedor, codigo_proveedor, contacto_proveedor
                FROM tb_proveedores';
        return Database::getRows($sql);
    }

    public function ProveedorReport1()
    {
        $sql = 'SELECT nombre_proveedor, nombre_comercial_proveedor, telefono_proveedor, codigo_proveedor, contacto_proveedor
                FROM tb_proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id_proveedor);
        return Database::getRows($sql, $params);
    }

    public function getUltimosProveedor()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor 
                FROM tb_proveedores 
                ORDER BY id_proveedor DESC 
                LIMIT 3';
        return Database::getRows($sql);
    }
}
?>
