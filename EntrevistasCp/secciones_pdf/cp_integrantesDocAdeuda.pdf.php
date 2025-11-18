<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// =========================
// CARGAR TCPDF
// =========================
$path = __DIR__ . '/../html2pdf/TCPDF/tcpdf.php';
if (!file_exists($path)) {
    die("No se encuentra TCPDF en: $path");
}
require_once $path;

// Verificar que la clase TCPDF existe
if (!class_exists('TCPDF')) {
    die("TCPDF no se cargó correctamente");
}

// =========================
// INICIO DE SESIÓN Y DATOS
// =========================
session_start();

$numeroconsulta  = $_SESSION['numeroConsulta'] ?? '';
$nrorub          = $_GET['nrorub'] ?? '';
$nroDoc          = $_GET['nroDoc'] ?? '';
$idhogar         = $_GET['idhogar'] ?? '';
$ntitu           = $_GET['ntitu'] ?? '';
$idpersonahogar  = $_GET['idpersonahogar'] ?? '';

include_once '../entrevistas.class.php';
include 'common_func.pdf.php';

$sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroconsulta, $idpersonahogar);

$documentos = [
    'DNI','Cert Domi','Part Nac','Cert Esc','Contr Salud','Cert Discap',
    'Part Defu.','Inf. Sumaria','Ce Negativa','Recibo',
    'Const Monotri','L. Sabana','Inf. Auto'
];

// =========================
// FORMULARIO HTML
// =========================
if (!isset($_POST['imprimir'])) {
    $dataHogar = $sh->getHogarCP()->getData();
    if (count($dataHogar) === 0) {
        echo "<p>No hay datos para mostrar.</p>";
        exit;
    }
    
    $nombreTitular = getApeNom($dataHogar[0] ?? (object)[]);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Documentación Faltante</title>
        <style>
            body { font-family: Arial, sans-serif; margin:20px; background:#f4f7f6; }
            .container { max-width:95%; margin:0 auto; padding:20px; background:#fff; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:8px; }
            h2 { color:#2c3e50; border-bottom:2px solid #3498db; padding-bottom:10px; margin-bottom:20px; }
            .documentos-table { width:100%; border-collapse: collapse; margin-bottom:20px; }
            .documentos-table th, .documentos-table td { padding:8px 5px; border:1px solid #ddd; text-align:center; }
            .documentos-table th { background:#3498db; color:white; font-size:11px; text-transform:uppercase; letter-spacing:0.5px; }
            .documentos-table tr:nth-child(even) { background:#f9f9f9; }
            .documentos-table td:first-child { text-align:left; font-weight:bold; white-space:nowrap; }
            .documentos-table td:nth-child(2) { white-space:nowrap; }
            .documentos-table input[type="checkbox"] { transform:scale(1.2); cursor:pointer; }
            textarea { width:100%; padding:10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; resize:vertical; font-size:14px; margin-top:5px; }
            button[type="submit"] { background:#2ecc71; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-size:16px; transition:0.3s; }
            button[type="submit"]:hover { background:#27ae60; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>📝 Documentación Faltante del Hogar</h2>
            <p>Titular del Hogar: <strong><?php echo htmlspecialchars($nombreTitular); ?></strong></p>
            <p>Nro. de Documento: <strong><?php echo htmlspecialchars($nroDoc); ?></strong></p>

            <form method="post">
                <table class="documentos-table">
                    <thead>
                        <tr>
                            <th>Apellido y Nombre</th>
                            <th>Dni/Cuil</th>
                            <?php foreach ($documentos as $doc) echo "<th>" . htmlspecialchars($doc) . "</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataHogar as $idx => $hdo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(getApeNom($hdo)); ?></td>
                            <td><?php echo htmlspecialchars($hdo->nro_doc ?? ''); ?></td>
                            <?php foreach ($documentos as $doc): ?>
                                <td><input type="checkbox" name="faltantes[<?php echo $idx; ?>][]" value="<?php echo htmlspecialchars($doc); ?>"></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <label><b>Observaciones:</b></label>
                <textarea name="observaciones" rows="4" placeholder="Ingrese aquí sus observaciones..."></textarea>

                <br><br>
                <button type="submit" name="imprimir">Guardar y Generar PDF</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// =========================
// GENERAR PDF
// =========================

// Limpiar buffer de salida antes de PDF
if (ob_get_length()) {
    ob_end_clean();
}

$observaciones = $_POST['observaciones'] ?? '';
$faltantes = $_POST['faltantes'] ?? [];
$dataHogar = $sh->getHogarCP()->getData();
$nombreTitular = getApeNom($dataHogar[0] ?? (object)[]);

// Crear PDF TCPDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema DGPOLA');
$pdf->SetTitle('Documentación Faltante');
$pdf->SetMargins(10, 20, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

// Fuente que soporte UTF-8
$pdf->SetFont('dejavusans', '', 10);

// Título
$pdf->SetFont('dejavusans', 'B', 16);
$pdf->Cell(0, 10, 'Documentación Faltante', 0, 1, 'C');

// Info Titular
$pdf->SetFont('dejavusans', '', 12);
$pdf->Ln(3);
$pdf->Cell(0, 10, "Titular: $nombreTitular", 0, 1);
$pdf->Cell(0, 10, "Nro Doc: $nroDoc", 0, 1);
$pdf->Ln(5);

// Configuración tabla
$pdf->SetFont('dejavusans', 'B', 7);
$pdf->SetFillColor(230,230,230);

$margenIzq = 10;
$margenDer = 10;
$anchoPagina = 297; // A4 horizontal
$anchoDisponible = $anchoPagina - $margenIzq - $margenDer;

$colNombre = 45;
$colDni = 22;
$cantDocs = count($documentos);
$anchoRestante = $anchoDisponible - ($colNombre + $colDni);
$colDoc = ($cantDocs > 0) ? floor($anchoRestante / $cantDocs) : 18;

// Encabezado
$pdf->Cell($colNombre,8,'Apellido y Nombre',1,0,'C',true);
$pdf->Cell($colDni,8,'DNI',1,0,'C',true);
foreach ($documentos as $doc) {
    $pdf->Cell($colDoc,8,$doc,1,0,'C',true);
}
$pdf->Ln();

// Contenido
$pdf->SetFont('dejavusans', '', 6);
foreach ($dataHogar as $idx => $hdo) {
    $pdf->Cell($colNombre,6,getApeNom($hdo),1,0,'L');
    $pdf->Cell($colDni,6,$hdo->nro_doc ?? '',1,0,'C');
    foreach ($documentos as $doc) {
        $marca = (isset($faltantes[$idx]) && in_array($doc,$faltantes[$idx])) ? 'X' : '';
        $pdf->Cell($colDoc,6,$marca,1,0,'C');
    }
    $pdf->Ln();
}

// Observaciones
$pdf->Ln(8);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,10,'Observaciones:',0,1);
$pdf->SetFont('dejavusans', '', 11);
$pdf->MultiCell(0,8,$observaciones);

// Descargar PDF
$pdf->Output('documentacion_faltante_' . $nombreTitular . '.pdf', 'D');
exit;
?>

