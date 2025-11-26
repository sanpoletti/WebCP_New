<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/path.php';
require_once APP_ROOT . '/login/phpUserClass/access.class.php';


// ===== Recibo parámetros =====
$numeroConsulta   = $_GET['numeroconsulta'] ?? '';
$idhogar          = $_GET['idhogar'] ?? '';
$identrevista     = $_GET['identrevista'] ?? '';
$ntitu            = $_GET['ntitu'] ?? '';
$nroDoc           = $_GET['nroDoc'] ?? '';
$nrorub           = $_GET['nrorub'] ?? '';
$idpersonahogar   = $_GET['idpersonahogar'] ?? '';

$composFamiliar   = $_GET['composFamiliar'] ?? '';
$sitEconomica     = $_GET['sitEconomica'] ?? '';
$sitHabitacional  = $_GET['sitHabitacional'] ?? '';
$sitEducacion     = $_GET['sitEducacion'] ?? '';
$observacion      = $_GET['observacion'] ?? '';
$sitSalud         = $_GET['sitSalud'] ?? '';

// ===== Clase principal =====
include_once 'entrevistas.class.php';
$sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);

// ===== PDF =====
require_once(__DIR__ . "/libs/fpdf.php");

class PDF extends FPDF {

    function Header() {
        $logo = __DIR__ . "/img/logo.png"; // Ruta real absoluta
        
        if (file_exists($logo)) {
            $this->Image($logo, 15, 10, 30);
        }
        
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,utf8_decode('Entrevista Hogar'),0,1,'C');
        $this->Ln(5);
    }
    

    function Footer() {
        $this->SetY(-35);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,utf8_decode('Firma y aclaración del entrevistado: __________________________'),0,1,'L');
        $this->Cell(0,10,utf8_decode('Firma y aclaración del entrevistador: _________________________'),0,1,'L');
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Fecha: '.date('d/m/Y'),0,0,'L');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

$pdf->Cell(0,10,utf8_decode("Entrevista Hogar Nº ".$idhogar),0,1,'C');
$pdf->Ln(5);

// ===== INTEGRANTES =====
include 'secciones_pdf/common_func.pdf.php';
include 'secciones_pdf/cp_integrantesImpresionEntrevista.pdf.php';

// ===== ENTREVISTA =====
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode(""),0,1);
$pdf->Ln(3);

$pdf->SetFont('Arial','',11);

function addBlock($pdf, $titulo, $texto) {
    if (trim($texto) !== "") {
        $pdf->SetFont('Arial','B',11);
        $pdf->MultiCell(0,6, utf8_decode($titulo));
        $pdf->SetFont('Arial','',11);
        $pdf->MultiCell(0,6, utf8_decode($texto));
        $pdf->Ln(3);
    }
}

addBlock($pdf, "Composición Familiar:", $composFamiliar);
addBlock($pdf, "Situación Económica:", $sitEconomica);
addBlock($pdf, "Situación Habitacional:", $sitHabitacional);
addBlock($pdf, "Situación de Salud:", $sitSalud);
addBlock($pdf, "Situación Educativa:", $sitEducacion);
addBlock($pdf, "Observaciones:", $observacion);

// ===== OUTPUT =====
$pdf->Output();
exit;
