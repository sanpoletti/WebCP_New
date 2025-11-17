<?php

$data = $sh->getHogarCP()->getData();

if (count($data) > 0) {

    // Título
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode("CONFORMACIÓN DEL HOGAR"), 0, 1, 'C');
    $pdf->Ln(2);

    // Encabezados de tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 8, utf8_decode("Apellido y Nombre"), 1);
    $pdf->Cell(30, 8, utf8_decode("Nro.Doc."), 1, 0, 'C');
    $pdf->Cell(40, 8, utf8_decode("Cuil."), 1, 0, 'C');
    $pdf->Cell(30, 8, utf8_decode("Fech.Nac."), 1, 0, 'C');
    $pdf->Cell(30, 8, utf8_decode("Edad actual"), 1, 1, 'C');

    // Filas con datos
    $pdf->SetFont('Arial', '', 10);

    foreach ($data as $row) {

        $nombre = utf8_decode(
            $row->getProperty('apellido') . ' ' . $row->getProperty('nombre')
        );

        $pdf->Cell(60, 8, $nombre, 1);
        $pdf->Cell(30, 8, $row->getProperty('nro_doc'), 1, 0, 'C');
        $pdf->Cell(40, 8, $row->getProperty('cuil'), 1, 0, 'C');
        $pdf->Cell(30, 8, $row->getProperty('fecha_nac'), 1, 0, 'C');
        $pdf->Cell(30, 8, $row->getProperty('edadac'), 1, 1, 'C');
    }

    $pdf->Ln(5);
}
?>

