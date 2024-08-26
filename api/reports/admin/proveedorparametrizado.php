<?php
// Se incluyen los archivos necesarios para manejar los datos del proveedor y generar el reporte.
require_once('../../models/data/admin_maestros_proveedores_data.php');
require_once('../../helpers/report.php');

// Crear una instancia del objeto Report para generar el PDF.
$pdf = new Report;

// Obtener el ID del proveedor desde la URL y convertirlo a entero.
$idProveedor = isset($_GET['idProveedor']) ? intval($_GET['idProveedor']) : 0;

// Verificar si el ID del proveedor es válido.
if ($idProveedor > 0) {
    // Iniciar el reporte PDF con el título correspondiente.
    $pdf->startReport('Reporte de proveedor N° ' . $idProveedor);

    // Crear una instancia del objeto ProveedorData para manejar los datos del proveedor.
    $proveedor = new ProveedorData();
    $proveedor->setIdProveedor($idProveedor);

    // Obtener los datos del proveedor.
    $dataProveedores = $proveedor->ProveedorReport1();

    // Verificar si se obtuvieron datos del proveedor.
    if ($dataProveedores) {
        // Configurar la fuente y la posición para el encabezado de la tabla.
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(10, 35);
        $pdf->setFillColor(225);
        $pdf->SetFont('Arial', 'B', 10);

        // Imprimir las cabeceras de las columnas.
        $pdf->cell(40, 10, 'Nombre', 1, 0, 'C', 1);    
        $pdf->cell(50, 10, 'Nombre Comercial', 1, 0, 'C', 1);
        $pdf->cell(40, 10, 'Teléfono', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'Código', 1, 0, 'C', 1);
        $pdf->cell(50, 10, 'Contacto', 1, 1, 'C', 1);

        // Imprimir los datos de cada proveedor.
        foreach ($dataProveedores as $rowProveedor) {
            $pdf->SetX(10);
            $pdf->cell(40, 10, $pdf->encodeString($rowProveedor['nombre_proveedor']), 1, 0, 'C');
            $pdf->cell(50, 10, $pdf->encodeString($rowProveedor['nombre_comercial_proveedor']), 1, 0, 'C');
            $pdf->cell(40, 10, $pdf->encodeString($rowProveedor['telefono_proveedor']), 1, 0, 'C');
            $pdf->cell(30, 10, $pdf->encodeString($rowProveedor['codigo_proveedor']), 1, 0, 'C');
            $pdf->cell(50, 10, $pdf->encodeString($rowProveedor['contacto_proveedor']), 1, 1, 'C');
        }
    } else {
        // Mensaje en caso de que no haya datos disponibles para el proveedor.
        $pdf->SetFont('Arial', '', 10);
        $pdf->cell(0, 10, 'No hay datos disponibles para este proveedor', 1, 1, 'C');
    }

    // Salida del PDF con el nombre 'proveedor.pdf'.
    $pdf->output('I', 'proveedor.pdf');
} else {
    // Mensaje en caso de que el ID del proveedor no sea válido.
    echo 'ID de proveedor inválido.';
}
?>
