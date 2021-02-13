<?php
// Activamos el almacenamiento en el buffer.
ob_start();
if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  echo "Debe ingresar al sistema correctamente para visualizar el reporte.";

} elseif ($_SESSION['almacen']===1) {
	// Incluimos a la clase PDF_MC_Table.
	require 'PDF_MC_Table.php';
	// Instanciamos la clase para generar el documento pdf.
	$pdf = new PDF_MC_Table();
	// Agregamos la primera página al documento.
	$pdf->AddPage();
	// Seteamos el inicio del margen superior en 25 pixeles.
	$y_axis_initial = 25;
	// Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá.
	$pdf->SetFont($family='Arial', $style='B', $size=12);
	// $pdf->Cell($w=45, $h=6);
	$pdf->Cell($w=190, $h=6, $txt='LISTA DE PRODUCTOS', $border=0, $ln=0, $align='C');
	$pdf->Ln(10);
	// Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra.
	$pdf->SetFillColor($r=232, $g=232, $b=232);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell($w=40, $h=6, $txt='Nombre', $border=1, $ln=0, $align='C', $fill=1);
	$pdf->Cell(30, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
	$pdf->Cell(35, 6, utf8_decode('Sust. Activa'), 1, 0, 'C', 1);
	$pdf->Cell(25, 6, utf8_decode('Código'), 1, 0, 'C', 1);
	$pdf->Cell(15, 6, 'Stock', 1, 0, 'C', 1);
	$pdf->Cell(15, 6, 'Lote', 1, 0, 'C', 1);
	$pdf->Cell(30, 6, 'Proveedor', 1, 0, 'C', 1);
	$pdf->Ln(10);

	// Comenzamos a crear las filas de los registros según la consula mysql.
	require_once '../modelos/Producto.php';
	$producto = new Producto();
	$listaProductos = $producto->listar();

	// Implementamos las celdas de la tabla con los registros a mostrar.
	$pdf->SetWidths(array(40, 30, 35, 25, 15, 15, 30));
	while ($prod = $listaProductos->fetch_object()) {
		$pdf->SetFont('Arial', '', 10);
		$pdf->Row(array(
			utf8_decode($prod->nombre),
			utf8_decode($prod->categoria),
			utf8_decode($prod->sustancia_activa),
			$prod->codigo_barra,
			$prod->stock,
			$prod->lote,
			utf8_decode($prod->proveedor)
		));
	}

	// Mostramos el documento PDF.
	$pdf->Output();

} else {
	echo "No tiene permiso para visualizar el reporte.";
}

ob_end_flush();
?>