<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/admin_usuarios_data.php');

// Verifica si se ha recibido el parámetro `userId` a través de GET o POST.
$id_nivel_usuario = isset($_GET['nivelUsuario']) ? $_GET['nivelUsuario'] : null;

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Listado de usuarios por ID de nivel');

// Definir el encabezado de la tabla
function printTableHeader($pdf) {
    // Ajuste de la posición para los encabezados de la tabla
    $pdf->SetXY(10, 35); // Mueve a la posición (10, 35) para los encabezados de la tabla

    // Establece el color de fondo para los encabezados
    $pdf->setFillColor(225);

    $pdf->SetFont('Arial', 'B', 10); // Establece el tamaño de la fuente para los encabezados
    $pdf->cell(40, 10, 'Usuario', 1, 0, 'C', 1);    
    $pdf->cell(30, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Correo', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'DUI', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Nivel', 1, 1, 'C', 1); // Cambiar el último parámetro a 1 para el salto de línea
}

// Se instancia el módulo Usuario para procesar los datos.
$Usuario = new UsuarioData;

// Verifica si se recibió el parámetro `userId`
if ($id_nivel_usuario !== null && !empty($id_nivel_usuario)) {
    // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
    if ($dataUsuario = $Usuario->UsuarioReportNivel($id_nivel_usuario)) {
        // Establece la fuente para los datos
        $pdf->SetFont('Arial', '', 10);

        // Imprime el encabezado de la tabla
        printTableHeader($pdf);

        // Contador de filas impresas
        $rowCount = 0;

        // Se recorren los registros fila por fila.
        foreach ($dataUsuario as $rowUsuario) {
            // Si se llega al final de la página, se añade una nueva página y se reimprime el encabezado
            if ($rowCount >= 20) {
                $pdf->AddPage();
                printTableHeader($pdf);
                $rowCount = 0; // Reinicia el contador de filas
            }

            $pdf->SetX(10); // Mueve a la posición del contenido de la tabla

             // Se imprimen las celdas con los datos de los Clientes.
        $pdf->cell(40, 10, $pdf->encodeString($rowUsuario['usuario']), 1, 0);
        $pdf->cell(30, 10, $rowUsuario['nombre'], 1, 0);
        $pdf->cell(50, 10, $rowUsuario['correo'], 1, 0);
        $pdf->cell(40, 10, $rowUsuario['DUI'], 1, 0);
        $pdf->cell(30, 10, $rowUsuario['id_nivel_usuario'], 1, 1);


            $rowCount++; // Incrementa el contador de filas
        }
    } else {
        $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
        $pdf->cell(0, 10, $pdf->encodeString('No hay usuarios con este nivel.'), 1, 1, 'C'); // Centra el mensaje
    }
} else {
    // Si no se recibe el parámetro `userId`, imprime un mensaje de error.
    $pdf->SetFont('Arial', '', 10); // Establece la fuente para el mensaje
    $pdf->cell(0, 10, $pdf->encodeString('Parámetro userId no proporcionado.'), 1, 1, 'C'); // Centra el mensaje
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'usuariosporid.pdf');
?>
