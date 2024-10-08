<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestro_productos_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de productos');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(9, 35); // Mueve a la posición (10, 50) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(39, 10, 'Nombre', 1, 0, 'C', 1);    
    $pdf->cell(30, 10, 'Codigo', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Precio Unitario', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Descuento', 1, 0, 'C', 1);
    $pdf->cell(39, 10, 'Precio con descuento', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Existencias', 1, 1, 'C', 1);
}

// Se instancia el módulo Producto para procesar los datos.
$producto = new ProductosData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataProductos = $producto->productosReport()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);

    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataProductos as $rowProducto) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX(9); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(39, 10, $pdf->encodeString($rowProducto['nombre']), 1, 0);
        $pdf->cell(30, 10, $rowProducto['codigo'], 1, 0);
        $pdf->cell(30, 10, $rowProducto['costo_unitario'], 1, 0);
        $pdf->cell(30, 10, $rowProducto['descuento'], 1, 0);
        $pdf->cell(39, 10, $rowProducto['precio_con_descuento'], 1, 0);
        $pdf->cell(30, 10, $rowProducto['existencia'], 1, 1);

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay productos'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
?>
