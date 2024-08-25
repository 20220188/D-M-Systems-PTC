<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestro_laboratorios_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de laboratorios');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(9, 35); // Mueve a la posición (9, 35) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(50, 10, $pdf->encodeString('ID Laboratorio'), 1, 0, 'C', 1);    
    $pdf->cell(50, 10, $pdf->encodeString('Código'), 1, 0, 'C', 1);
    $pdf->cell(80, 10, $pdf->encodeString('Nombre del Laboratorio'), 1, 1, 'C', 1);
}

// Se instancia el módulo Laboratorios para procesar los datos.
$laboratorio = new LaboratoriosData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataLaboratorios = $laboratorio->reporteLaboratorios()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);

    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataLaboratorios as $rowLaboratorio) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX(9); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de los laboratorios.
        $pdf->cell(50, 10, $pdf->encodeString($rowLaboratorio['id_laboratorio']), 1, 0);
        $pdf->cell(50, 10, $pdf->encodeString($rowLaboratorio['codigo']), 1, 0);
        $pdf->cell(80, 10, $pdf->encodeString($rowLaboratorio['nombre_laboratorio']), 1, 1);

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay laboratorios registrados'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'laboratorios.pdf');
?>
