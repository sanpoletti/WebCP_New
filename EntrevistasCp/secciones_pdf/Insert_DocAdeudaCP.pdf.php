<?php
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
// FORMULARIO
// =========================
if (!isset($_POST['imprimir'])) {

    $dataHogar = $sh->getHogarCP()->getData();
    
    if (count($dataHogar) === 0) {
        echo "<p>No hay datos para mostrar.</p>";
        exit;
    }
    
    // me quedo con el nombre del titular del hogar
    $nombreTitular = '';
    if (!empty($dataHogar) && isset($dataHogar[0])) {
        $nombreTitular = getApeNom($dataHogar[0]);
    }
    ?>
    <form method="post">
        <table border="1" style="border-collapse: collapse; width: 100%; text-align:center;">
            <tr>
                <th>Apellido y Nombre</th>
                <th>Dni/Cuil</th>
                <?php foreach ($documentos as $doc) echo "<th>$doc</th>"; ?>
            </tr>
    <?php
    foreach ($dataHogar as $idx => $hdo) {
        
        // inicializo todos los documentos en 0
        $valoresDocs = array_fill_keys($documentos, 0);
        
        echo "<tr>";
        echo "<td>" . getApeNom($hdo) . "</td>";
        echo "<td>" . htmlspecialchars($hdo->nro_doc ?? '') . "</td>";
        foreach ($documentos as $doc) {
            echo "<td><input type='checkbox' name='faltantes[$idx][]' value='$doc'></td>";
        }
        echo "</tr>";
    }
    ?>
        </table>

        <br>
        <label><b>Observaciones:</b></label><br>
        <textarea name="observaciones" rows="4" cols="80" placeholder="Ingrese aquí sus observaciones..."></textarea>

        <br><br>
        <button type="submit" name="imprimir">Guardar</button>
    </form>
    <?php
    exit;
}

// =========================
// PROCESAR PDF
// =========================
if (isset($_POST['imprimir'])) {

    $observaciones = $_POST['observaciones'] ?? '';
    $faltantes = $_POST['faltantes'] ?? [];

    // Cargar nuevamente la info de hogar para armar la tabla en PDF
    $dataHogar = $sh->getHogarCP()->getData();

    // ✅ Obtener nombre del titular (primer registro)
    $nombreTitular = '';
    if (!empty($dataHogar) && isset($dataHogar[0])) {
        $nombreTitular = getApeNom($dataHogar[0]);
    } else {
        $nombreTitular = 'SIN DATOS';
    }

    require_once(__DIR__ . "/../libs/fpdf.php");

    // ✅ Evitar el error "Some data has already been output"
    if (ob_get_length()) {
        ob_end_clean();
    }

    
    class PDF extends FPDF {
        function Header() {
            // Logo (ajustar ruta y tamaño)
            $this->Image('C:/xampp/htdocs/EntrevistasCp/img/logo.gif', 15, 20, 30);
            $this->Ln(15);
            $this->SetFont('Arial','B',12);
            
        }
        function Footer() {
            $this->SetY(-20);
            $this->SetFont('Arial','I',10);
            $this->Cell(0,10,'Firma y aclaracion del entrevistado: __________________________',0,1,'L');
            //$this->Ln(10);
            // Fecha debajo de la firma
            $this->SetFont('Arial','I',8); // más chica
            $this->Cell(0,8,'Fecha: '.date('d/m/Y'),0,0,'L');
        }
    }
    
    // ✅ Crear PDF con la clase correcta
    $pdf = new PDF('L','mm','A4');
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,utf8_decode('Documentación Faltante'),0,1,'C');

    // Info Titular
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(3);
    $pdf->Cell(0,10,"Titular: ".utf8_decode($nombreTitular),0,1);
    $pdf->Cell(0,10,"Nro Doc: ".utf8_decode($nroDoc),0,1);
    $pdf->Ln(5);
    
    
    // Configuración general
    $pdf->SetFont('Arial','B',7);
    $pdf->SetFillColor(230,230,230);
    
    // Márgenes y ancho total disponible
    $margenIzq = 10;
    $margenDer = 10;
    $anchoPagina = 297; // A4 horizontal en mm
    $anchoDisponible = $anchoPagina - $margenIzq - $margenDer;
    
    
    

    // Definir anchos
    $colNombre = 45;  // Apellido y Nombre
    $colDni = 22;     // DNI
    $colDoc = 18;     // Cada documento
    
    
    // Calcular ancho dinámico para cada documento
    $cantDocs = count($documentos);
    $anchoRestante = $anchoDisponible - ($colNombre + $colDni);
    $colDoc = ($cantDocs > 0) ? floor($anchoRestante / $cantDocs) : 18; // mínimo 18 mm
    
    
    // Encabezado
    $pdf->Cell($colNombre,8,'Apellido y Nombre',1,0,'C',true);
    $pdf->Cell($colDni,8,'DNI',1,0,'C',true);
    foreach ($documentos as $doc) {
        $pdf->Cell($colDoc,8,utf8_decode($doc),1,0,'C',true);
    }
    $pdf->Ln();
    
    // Contenido
    $pdf->SetFont('Arial','',6); // Más chico para que entre más
    foreach ($dataHogar as $idx => $hdo) {
        $pdf->Cell($colNombre,6,utf8_decode(getApeNom($hdo)),1,0,'L');
        $pdf->Cell($colDni,6,$hdo->nro_doc ?? '',1,0,'C');
        foreach ($documentos as $doc) {
            $marca = (isset($faltantes[$idx]) && in_array($doc,$faltantes[$idx])) ? 'X' : '';
            $pdf->Cell($colDoc,6,$marca,1,0,'C');
        }
        $pdf->Ln();
    }

    // Observaciones
    $pdf->Ln(8);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Observaciones:',0,1);
    $pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0,8,utf8_decode($observaciones));

    // ✅ Descargar en lugar de guardar
    $pdf->Output('D', 'documentacion_faltante_' . $nombreTitular . '.pdf');
    exit;
}
?>

