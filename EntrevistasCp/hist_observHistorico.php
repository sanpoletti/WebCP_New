<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Hist&oacute;rico de observaciones titular</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>

<?php

// Seteo variables enviadas por POST
if( isset($_GET['ntitu']) && !empty($_GET['ntitu']) ) {
	
	$numeroconsulta  = $_SESSION['numeroConsulta'] ?? '';
	$nrorub          = $_GET['nrorub'] ?? '';
	$idhogar         = $_GET['idhogar'] ?? '';
	$ntitu           = $_GET['ntitu'] ?? '';
	$idpersonahogar  = $_GET['idpersonahogar'] ?? '';
	
	
	
	
} else {
	echo "No tiene permisos para acceder a este m�dulo";
	die();
}

// Seteo como indefinido el tiempo de timeout
set_time_limit(0); 

$content = '';


ob_start();
include 'entrevistas.class.php';
$hoH = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroconsulta, $idpersonahogar);
//($ntitular, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar
ob_end_clean();
ob_start();
echo "<body bgcolor='#e7e8ed'>";
include "gen_hoH_pdf.php";
$content .= ob_get_clean();

echo $content;


// conversion HTML => PDF
/*require_once(dirname(__FILE__).'\html2pdf\html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4', 'es');
$html2pdf->WriteHTML($content);
$html2pdf->Output('hd.pdf');
*/
?>
</html>