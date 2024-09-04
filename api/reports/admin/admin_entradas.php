<?php
// Archivo: reports/admin/entradas_reporte.php

// Primero, añadamos algunas líneas de depuración al inicio del archivo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Imprimir los parámetros recibidos para depuración
//echo "Parámetros recibidos: ";
//print_r($_GET);

require_once('../../helpers/report.php');
require_once('../../models/data/admin_ingreso_data.php');

$pdf = new Report;

if (isset($_GET['filtro']) && isset($_GET['valor'])) {
    $filtro = $_GET['filtro'];
    $valor = $_GET['valor'];

    //echo "Filtro: $filtro, Valor: $valor<br>";

    $tituloReporte = ($filtro === 'numeroEntrada') 
        ? "Reporte de entrada N° $valor" 
        : "Reporte de entradas del $valor";

    $pdf->startReport($tituloReporte);

    // Función para imprimir el encabezado de la tabla
    function printTableHeader($pdf) {
        $pdf->SetXY(10, 35);
        $pdf->setFillColor(225);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell(35, 10, $pdf->encodeString('N° de entrada'), 1, 0, 'C', 1);    
        $pdf->cell(50, 10, 'Fecha', 1, 0, 'C', 1);
        $pdf->cell(40, 10, 'Tipo de entrada', 1, 0, 'C', 1);
        $pdf->cell(70, 10, 'Nota', 1, 1, 'C', 1);
    }

    $entrada = new EntradasData;

    // Asegúrate de que estos métodos existan en tu clase EntradasData
    if ($filtro === 'numeroEntrada') {
        $entrada->setNumeroEntrada($valor);
    } elseif ($filtro === 'fechaEntrada') {
        $entrada->setFecha($valor);
    }
    $entrada->setFiltro($filtro);

    // Añade depuración antes de llamar a reportNE()
    //echo "Llamando a reportNE() con filtro: " . $entrada->getFiltro() . "<br>";

    $dataEntradas = $entrada->reportNE();

    // Añade depuración después de llamar a reportNE()
    //echo "Resultado de reportNE(): ";
    //var_dump($dataEntradas);

    if ($dataEntradas && !empty($dataEntradas)) {
        $pdf->SetFont('Arial', '', 10);
        printTableHeader($pdf);
        $rowCount = 0;

        foreach ($dataEntradas as $rowEntrada) {
            if ($rowCount >= 20) {
                $pdf->AddPage();
                printTableHeader($pdf);
                $rowCount = 0;
            }

            $pdf->SetX(10);
            $pdf->cell(35, 10, $pdf->encodeString($rowEntrada['numero_entrada']), 1, 0, 'C');
            $pdf->cell(50, 10, $pdf->encodeString($rowEntrada['fecha']), 1, 0, 'C');
            $pdf->cell(40, 10, $pdf->encodeString($rowEntrada['tipo_entrada']), 1, 0, 'C');
            $pdf->cell(70, 10, $pdf->encodeString($rowEntrada['nota']), 1, 1, 'C');

            $rowCount++;
        }
    } else {
        $pdf->SetXY(10, 35);
        $pdf->SetFont('Arial', '', 10);
        $pdf->cell(0, 10, $pdf->encodeString('No hay datos disponibles para el filtro seleccionado.'), 1, 1, 'C');
    }
} else {
    $pdf->SetXY(10, 35);
    $pdf->SetFont('Arial', '', 10);
    $pdf->cell(0, 10, $pdf->encodeString('No se ha proporcionado un filtro o valor válido.'), 1, 1, 'C');
}

// Comenta esta línea durante la depuración para ver los mensajes de error
$pdf->output('I', 'reporte_entradas.pdf');

// En su lugar, imprime el contenido en texto plano
echo $pdf->output('S');
?>