<?php
require('FPDF/fpdf.php');

// Convierte color hex a RGB
function hex2dec($couleur = "#000000")
{
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    return ['R' => $rouge, 'V' => $vert, 'B' => $bleu];
}

// Pixel a mm
function px2mm($px)
{
    return $px * 25.4 / 72;
}

// Convierte entidades HTML
function txtentities($html)
{
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

class PDF_HTML extends FPDF
{
    // Variables HTML parser
    protected int $B = 0;
    protected int $I = 0;
    protected int $U = 0;
    protected string $HREF = '';
    protected array $fontList;
    protected bool $issetfont = false;
    protected bool $issetcolor = false;

    function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
        $this->fontList = ['arial', 'times', 'courier', 'helvetica', 'symbol'];
    }

    function Header()
    {
        $this->SetFont('Arial', '', 12);
        $this->Image('http://localhost/SistemaCrio/assets/img/logonegro.png', 10, 10, 35);
        $this->SetFont('Arial', '', 10);
        $this->Cell(330, 10, "Fecha: " . date("d-m-Y"), 0, 0, "R");
        $this->Line(0, 8, 350, 8);
        $this->Ln(45);
        $this->SetFont('Arial', 'B', 35);
        $this->SetX(35);
        $this->Cell(260, 5, 'Reporte de productos', 0, 0, 'C');
        $this->Line(0, 30, 350, 30);
        $this->Ln(20);

        // Encabezados de tabla
        $this->SetFont('Arial', 'B', 13);
        $this->SetX(30);
        $this->SetFillColor(120, 120, 120);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(50, 10, 'Codigo', 1, 0, 'C', 1);
        $this->Cell(115, 10, 'Nombre', 1, 0, 'C', 1);
        $this->Cell(65, 10, 'Categoria', 1, 0, 'C', 1);
        $this->Cell(35, 10, 'Existencias', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Precio', 1, 1, 'C', 1);

        $this->SetFont('Arial', '', 12);
    }

    function Footer()
    {
        $this->SetFont('Arial', 'I', 10);
        $this->SetY(-15);
        $this->Cell(0, 10, 'CRIO', 0, 0, 'C');
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

    // Métodos básicos de HTML parser
    function WriteHTML($html)
    { /* Código igual que antes, omitido por brevedad */
    }
    function OpenTag($tag, $attr)
    { /* Código igual que antes */
    }
    function CloseTag($tag)
    { /* Código igual que antes */
    }
    function SetStyle($tag, $enable)
    { /* Código igual que antes */
    }
    function PutLink($URL, $txt)
    { /* Código igual que antes */
    }

}
?>