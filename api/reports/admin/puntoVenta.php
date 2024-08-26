<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestros_punto_de_venta_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de puntos de venta');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Definir la posición X para centrar la tabla en la página
    $tableX = 45; // Ajusta este valor para centrar la tabla (la página tiene un ancho de 210 mm)

    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY($tableX, 35); // Mueve a la posición (tableX, 35) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(50, 10, $pdf->encodeString('ID Puntos de venta'), 1, 0, 'C', 1);    
    $pdf->cell(50, 10, $pdf->encodeString('Punto de venta'), 1, 1, 'C', 1); // 1 en el último parámetro para crear una nueva línea después de esta celda
}

// Se instancia el módulo PuntoVentas para procesar los datos.
$Puntoventa = new PuntoDeVentaHandler;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataPuntoVenta = $Puntoventa->PuntoVentaReport()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);

    // Definir la posición X para centrar la tabla en la página
    $tableX = 45; // Ajusta este valor para centrar la tabla

    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataPuntoVenta as $rowPuntoVenta) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX($tableX); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de los PuntoVentas.
        $pdf->cell(50, 10, $pdf->encodeString($rowPuntoVenta['id_punto_venta']), 1, 0, 'C');
        $pdf->cell(50, 10, $pdf->encodeString($rowPuntoVenta['punto_venta']), 1, 1, 'C'); // 1 en el último parámetro para crear una nueva línea después de esta celda

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay puntos de venta registrados'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'PuntoDeVenta.pdf');
?>
