<?php
// Se incluye la clase para generar archivos PDF.
require_once('../../libraries/fpdf185/fpdf.php');

/*
*   Clase para definir las plantillas de los reportes del sitio privado.
*   Para más información http://www.fpdf.org/
*/
class Report extends FPDF
{
    // Constante para definir la ruta de las vistas del sitio privado.
    const CLIENT_URL = 'http://localhost/D-M-SYSTEM/views/admin/';
    // Propiedad para guardar el título del reporte.
    private $title = null;

    /*
    *   Método para iniciar el reporte con el encabezado del documento.
    *   Parámetros: $title (título del reporte).
    *   Retorno: ninguno.
    */
    public function startReport($title)
    {
        session_start();
        if (isset($_SESSION['idAdministrador'])) {
            $this->title = $title;
            $this->setTitle('DM-SYSTEM - Reporte', true);
            $this->setMargins(15, 15, 15);
            $this->addPage('p', 'letter');
            $this->aliasNbPages();
        } else {
            header('location:' . self::CLIENT_URL);
            exit; // Asegura que no se sigue ejecutando el script después de redireccionar
        }
    }

    /*
    *   Método para codificar una cadena de alfabeto español a UTF-8.
    *   Parámetros: $string (cadena).
    *   Retorno: cadena convertida.
    */
    public function encodeString($string)
    {
        return mb_convert_encoding($string, 'ISO-8859-1', 'utf-8');
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del encabezado de los reportes.
    *   Se llama automáticamente en el método addPage()
    */
    public function header()
    {
        // Se establece el logo.
        $this->image('../../images/fondoreportt.png', 0, 0, 215.9, 279.4);
    
        // Define el ancho y la altura de las celdas
        $cellHeight = 10;
        $titleWidth = 0; // Ancho automático
        $dateWidth = 10; // Ancho fijo para la fecha/hora
    
        // Mueve el cursor a la posición inicial del encabezado
        $this->SetXY(5, 15); // Ajusta las coordenadas (X, Y)
    
        // Imprime la fecha/hora en una celda a la izquierda
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 0, 0); // Establece el color del texto a negro
        $this->Cell($dateWidth, $cellHeight, 'Fecha/Hora: ' . date('d-m-Y H:i:s'), 0, 0, 'L');
    
        // Calcula la posición X para el título (después de la celda de fecha/hora)
        $titleX = $this->GetX();
        $titleY = $this->GetY();

        // Establece el formato del título
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(0, 0, 0); // Blanco para el título
    
        // Imprime el título en la celda restante, centrado
        $this->SetXY($titleX, $titleY); // Reposiciona el cursor en la posición X calculada
        $this->Cell(0, $cellHeight, $this->encodeString($this->title), 0, 1, 'C');
    }
    

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del pie de los reportes.
    *   Se llama automáticamente en el método output()
    */
    public function footer()
{
    $this->setFont('Arial', 'I', 10);
    $this->setY(-15);

    $usuario = isset($_SESSION['aliasAdministrador']) ? $this->encodeString($_SESSION['aliasAdministrador']) : 'Desconocido';

    $this->SetTextColor(255, 255, 255); // Establece el color del texto a blanco
    $this->Cell(300, -9, "Reporte generado por el usuario: " . $usuario, 0, 0, 'C');

    // Se establece la posición para el número de página (a 15 milímetros del final).
    $this->SetY(-15);
    // Establece el color del texto a negro
    $this->SetTextColor(0, 0, 0); // Color negro en formato RGB

    // Se establece la fuente para el número de página.
    $this->setFont('Arial', 'I', 8);

    // Se imprime una celda con el número de página.
    $this->SetTextColor(255, 255, 255); // Establece el color del texto a blanco
    $this->cell(380, 17, $this->encodeString('Página ') . $this->pageNo() . '/{nb}', 0, 0, 'C');
}
}
