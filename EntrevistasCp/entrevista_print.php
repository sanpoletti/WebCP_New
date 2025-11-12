<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../login/phpUserClass/access.class.php';

$numeroconsulta  = $_GET['numeroconsulta'] ?? '';
$idhogar         = $_GET['idhogar'] ?? '';
$identrevista    = $_GET['identrevista'] ?? '';
$ntitu           = $_GET['ntitu'] ?? '';
$nroDoc          = $_GET['nroDoc'] ?? '';

// Incluyo clases
include_once 'entrevistas.class.php';
$sh = new Entrevistas($ntitu, $idhogar, $_GET['nrorub'] ?? '', $numeroconsulta, $_GET['idpersonahogar'] ?? '');

// Aca podes reusar los includes que ya tenes
ob_start();




?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Impresión Entrevista</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .bloque { margin-bottom: 25px; padding: 15px; border: 1px solid #ccc; border-radius: 8px; }
        .bloque h3 { margin-top: 0; }
        .btn-print { display: block; margin: 20px auto; padding: 10px 20px; background: #2d89ef; color: white; border: none; border-radius: 8px; cursor: pointer; }
        .btn-print:hover { background: #1b5eaa; }
    </style>
</head>
<body>

    <h2>Entrevista Hogar </h2>

    <div class="bloque">     
        <?php 
        
        
    /*    Explicación:
        __DIR__ → ruta absoluta del archivo actual (entrevista_print.php).
        ../ → sube un nivel de carpeta.
        ../../libs/fpdf.php → sube dos niveles y entra en libs para buscar fpdf.php
    */    
        require_once(__DIR__ . "/libs/fpdf.php");
        
        
        class PDF extends FPDF {
            function Header() {
                // Logo (ajustá el nombre del archivo si es logo.gif o logo.GIF)
                
                $this->Image(__DIR__ . "/img/logo.GIF", 15, 10, 30);
                
                $this->SetFont('Arial','B',14);
                $this->Cell(0,10,utf8_decode('Entrevista Hogar'),0,1,'C');
                $this->Ln(5);
            }
            
            function Footer() {
                $this->SetY(-35);
                $this->SetFont('Arial','I',10);
                $this->Cell(0,10,utf8_decode('Firma y aclaracion del entrevistado: __________________________'),0,1,'L');
                $this->Cell(0,10,utf8_decode('Firma y aclaracion del entrevistador: _________________________'),0,1,'L');
                $this->SetFont('Arial','I',8);
                $this->Cell(0,10,'Fecha: '.date('d/m/Y'),0,0,'L');
            }
        }
        
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        
        // Título con nro de hogar
        $pdf->Cell(0,10,utf8_decode("Entrevista Hogar Nº ".$idhogar),0,1,'C');
        $pdf->Ln(5);
        
        
        
        
        
        include 'secciones_pdf/common_func.pdf.php';
        include 'secciones_pdf/cp_integrantesImpresion.pdf.php'; ?>
    </div>

    <div class="bloque">
        <h3>Entrevista</h3>
        <p><strong>Composición Familiar:</strong><br><?= nl2br(htmlspecialchars($_GET['composFamiliar'] ?? '')) ?></p>
        <p><strong>Situación Económica:</strong><br><?= nl2br(htmlspecialchars($_GET['sitEconomica'] ?? '')) ?></p>
        <p><strong>Situación Habitacional:</strong><br><?= nl2br(htmlspecialchars($_GET['sitHabitacional'] ?? '')) ?></p>
        <p><strong>Situación Salud:</strong><br><?= nl2br(htmlspecialchars($_GET['sitSalud'] ?? '')) ?></p>
        <p><strong>Situación Educación:</strong><br><?= nl2br(htmlspecialchars($_GET['sitEducacion'] ?? '')) ?></p>
        <p><strong>Observaciones:</strong><br><?= nl2br(htmlspecialchars($_GET['observacion'] ?? '')) ?></p>
    </div>
<!-- Bloque de firmas -->
    <div class="firmas">
        <div class="firma">
            <div class="linea"></div>
            <p>Firma y aclaración del Entrevistado:___________________________________</p>
        </div>
        <br>
        <div class="firma">
            <div class="linea"></div>
            <p>Firma y aclaración del Entrevistador:__________________________________</p>
        </div>
    </div>

    <div class="fecha">
        Fecha: <?= date("d/m/Y") ?>
    </div>

    <button class="btn-print" onclick="window.print()">🖨️ Imprimir</button>

</body>
</html>
<?php
ob_end_flush();
