<?php
if (!isset($_SESSION))
	session_start();
if (!isset($_SESSION['URL_PATH'])) {
	$_SESSION['URL_PATH'] = "http://" . $_SERVER['HTTP_HOST'] . "/SistemaCrio";
}

if (!isset($_SESSION['persona'])) {
	echo "<script>window.location.replace('" . $_SESSION['URL_PATH'] . "/App/Views/Persona/index.php')</script>";
	exit;
}

require('HTMLDEPRODUCTOS.php');

// Crear PDF
$pdf = new PDF_HTML('LANDSCAPE', 'mm', [350, 290]);
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', [350, 290]);

// Cargar productos
$productos = unserialize($_SESSION['Listaproductos']);
foreach ($productos as $row) {
	$pdf->SetX(30);
	$pdf->Cell(50, 10, $row->Cod_producto, 1, 0, 'C', 0);
	$pdf->Cell(115, 10, $row->Nombre, 1, 0, 'C', 0);
	$pdf->Cell(65, 10, $row->Categoria, 1, 0, 'C', 0);
	$pdf->Cell(35, 10, $row->Existencias, 1, 0, 'C', 0);
	$pdf->Cell(40, 10, '$' . $row->Precio, 1, 1, 'C', 0);
}

// Generar PDF
$pdf->Output('I', 'ReporteProductos.pdf');
?>