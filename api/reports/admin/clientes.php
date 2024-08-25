<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_maestro_cliente_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de clientes');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(10, 35); // Mueve a la posición (10, 35) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    // Encabezados
    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(40, 10, 'Nombre', 1, 0, 'C', 1);    
    $pdf->cell(30, 10, 'Codigo', 1, 0, 'C', 1);
    $pdf->cell(50, 10, $pdf->encodeString('Dirección'), 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Nombre comercial', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Telefono', 1, 1, 'C', 1); // Cambiar el último parámetro a 1 para el salto de línea
}

// Se instancia el módulo Cliente para procesar los datos.
$Cliente = new ClienteData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataClientes = $Cliente->ClientesReport()) {
    // Establece la fuente para los datos
    $pdf->SetFont('Arial', '', 10);

    // Imprime el encabezado de la tabla
    printTableHeader($pdf);

    // Contador de filas impresas
    $rowCount = 0;

    // Se recorren los registros fila por fila.
    foreach ($dataClientes as $rowCliente) {
        // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
        if ($rowCount >= 20) {
            $pdf->AddPage();
            printTableHeader($pdf);
            $rowCount = 0; // Reinicia el contador de filas
        }

        $pdf->SetX(10); // Mueve a la posición del contenido de la tabla

        // Se imprimen las celdas con los datos de los Clientes.
        $pdf->cell(40, 10, $pdf->encodeString($rowCliente['nombre']), 1, 0);
        $pdf->cell(30, 10, $rowCliente['codigo'], 1, 0);
        $pdf->cell(50, 10, $rowCliente['direccion'], 1, 0);
        $pdf->cell(40, 10, $rowCliente['nombre_comercial'], 1, 0);
        $pdf->cell(30, 10, $rowCliente['telefono'], 1, 1);

        $rowCount++; // Incrementa el contador de filas
    }
} else {
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('No hay Clientes'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'clientes.pdf');
?>
