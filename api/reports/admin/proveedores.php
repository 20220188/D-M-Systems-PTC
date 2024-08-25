<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestros_proveedores_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de proveedores');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(10, 35); // Mueve a la posición (10, 35) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(40, 10, $pdf->encodeString('Nombre'), 1, 0, 'C', 1);
    $pdf->cell(40, 10, $pdf->encodeString('Nombre comercial'), 1, 0, 'C', 1);
    $pdf->cell(40, 10, $pdf->encodeString('Teléfono'), 1, 0, 'C', 1);
    $pdf->cell(40, 10, $pdf->encodeString('Código'), 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Contacto', 1, 1, 'C', 1); // Añade un salto de línea al final
}

// Se instancia el módulo proveedor para procesar los datos.
$proveedor = new proveedorData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataproveedor = $proveedor->ProveedorReport()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);

    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataproveedor as $rowproveedor) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX(10); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de los proveedor.
        $pdf->cell(40, 10, $pdf->encodeString($rowproveedor['nombre_proveedor']), 1, 0);
        $pdf->cell(40, 10, $pdf->encodeString($rowproveedor['nombre_comercial_proveedor']), 1, 0);
        $pdf->cell(40, 10, $pdf->encodeString($rowproveedor['telefono_proveedor']), 1, 0);
        $pdf->cell(40, 10, $pdf->encodeString($rowproveedor['codigo_proveedor']), 1, 0);
        $pdf->cell(40, 10, $pdf->encodeString($rowproveedor['contacto_proveedor']), 1, 1);

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay proveedores disponibles'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'proveedor.pdf');
?>
