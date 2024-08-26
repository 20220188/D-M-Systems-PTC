<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');

// Se incluye la clase para la transferencia y acceso a datos.
require_once('../../models/data/admin_salida_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de salida N° ' . $_GET['numeroSalida']);

// Función para imprimir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(10, 35); // Mueve a la posición (10, 35) para los encabezados de la tabla
    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(35, 10, $pdf->encodeString('N° de salida'), 1, 0, 'C', 1);    
    $pdf->cell(30, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Tipo de salida', 1, 0, 'C', 1);
    $pdf->cell(20, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Cliente', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Dependiente', 1, 1, 'C', 1); // Cambiar el último parámetro a 1 para el salto de línea
}

// Se instancia la clase SalidasData para procesar los datos.
$salida = new SalidasData;
// Se establece el valor del número de salida.
$salida->setNumeroSalida($_GET['numeroSalida']);

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataSalidas = $salida->reportNS()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);
    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataSalidas as $rowSalida) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX(10); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de las salidas.
        $pdf->cell(35, 10, $pdf->encodeString($rowSalida['numero_salida']), 1, 0, 'C');
        $pdf->cell(30, 10, $pdf->encodeString($rowSalida['fecha']), 1, 0, 'C');
        $pdf->cell(30, 10, $pdf->encodeString($rowSalida['tipo_salida']), 1, 0, 'C');
        $pdf->cell(20, 10, $rowSalida['cantidad_salida'], 1, 0, 'C');
        $pdf->cell(40, 10, $pdf->encodeString($rowSalida['nombre']), 1, 0, 'C');
        $pdf->cell(40, 10, $pdf->encodeString($rowSalida['nombre_dependiente']), 1, 1, 'C');

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay datos disponibles para esta salida'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'salida.pdf');
?>