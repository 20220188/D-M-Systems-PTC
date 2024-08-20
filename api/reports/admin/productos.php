<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestro_productos_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de productos');

// Se instancia el módelo Producto para procesar los datos.
$producto = new ProductosData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataProductos = $producto->productosReport()) {
    //encabezadors
    $pdf->setFillColor(225);
    $pdf->cell(50, 10, 'Nombre', 1, 0, 'C', 1);    
    $pdf->cell(35, 10, 'Codigo', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Precio Unitario', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Descuento', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Precio con descuento', 1, 1, 'C', 1);
    // Se recorren los registros fila por fila.
    foreach ($dataProductos as $rowProducto) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(50, 10, $pdf->encodeString($rowProducto['nombre']), 1, 0);
        $pdf->cell(35, 10, $rowProducto['codigo'], 1, 0);
        $pdf->cell(35, 10, $rowProducto['costo_unitario'], 1, 0);
        $pdf->cell(35, 10, $rowProducto['descuento'], 1, 0);
        $pdf->cell(35, 10, $rowProducto['precio_con_descuento'], 1, 1);        
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay productos'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
