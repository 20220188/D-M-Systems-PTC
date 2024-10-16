<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*	Clase para manejar el comportamiento de los datos de la tabla tb_formas_pago.
*/
class FormasPagoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id_forma_pago = null;
    protected $forma_pago = null;

    /*
    *   Métodos para realizar las operaciones CRUD (create, read, update, and delete).
    */

    // Método para crear una nueva forma de pago

    // Método para obtener todas las formas de pago
    public function readAll()
    {
        $sql = 'SELECT id_forma_pago, forma_pago
                FROM tb_formas_pago
                ORDER BY forma_pago';
        return Database::getRows($sql);
    }

   
    // Método para generar un reporte de todas las formas de pago
  
}
?>
