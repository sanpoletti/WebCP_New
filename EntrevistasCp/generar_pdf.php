<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Entrevistas</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>

<?php

session_start();


$hora  = $_SESSION['hora'];


// Seteo variables enviadas por parametro
if( isset($_GET['ntitulares']) && !empty($_GET['ntitulares']) && isset($_GET['numeroConsulta']) && !empty($_GET['numeroConsulta']) && isset($_GET['idpersonahogar']) &&isset($_GET['rubs']) && !empty($_GET['rubs'])&& !empty($_GET['idhogar'])) {
	$ntitulares = $_GET['ntitulares'];
	$rubs = $_GET['rubs'];
	$numeroConsulta = $_GET['numeroConsulta'];
	$idpersonahogar = $_GET['idpersonahogar'];
	$horas = $_GET['horas'];
	$hora = $_GET['hora'];
	$idhogares = $_GET['idhogar'];
} else if( isset($_GET['ntitu']) && !empty($_GET['ntitu']) && isset($_GET['nrorub'])) {
	$ntitu = $_GET['ntitu'];
	$hora = $_GET['hora'];
	$numeroConsulta = $_GET['numeroConsulta'];
	$idpersonahogar = $_GET['idpersonahogar'];
	$idhogar = $_GET['idhogar'];
	$nrorub = $_GET['nrorub'];

	session_start();
	$_SESSION['ntitu']=$_GET['ntitu'];
	$_SESSION['idhogar']=$_GET['idhogar'];
	$_SESSION['nrorub']=$_GET['nrorub'];



} else {
	echo "Error al visualizar hogar ";
	die();
}


// Seteo como indefinido el tiempo de timeout
set_time_limit(0);

$content = '';


if (isset($ntitulares)) {		// Multiples fichas
	for($i=0; $i < count($ntitulares); $i++) {
		$ntitu = $ntitulares[$i];
		$nrorub = $rubs[$i];
		$numeroConsulta = $numeroConsulta[$i];
		$idpersonahogar = $idpersonahogar[$i];
		$hora = $horas[$i];
		$idhogar = $idhogares[$i];
		ob_start();
		include "gen_modelo.php";
		ob_end_clean();
		ob_start();
		echo "<body bgcolor='#e7e8ed'>";
		include "gen_pdf.php";
		echo '<div style ="page-break-after:alwals"></div>';
		$content .= ob_get_clean();
		$content .= '<p class="break">&nbsp;</p>';
	}
} else {			// FIcha simple
	ob_start();
	include "gen_modelo.php";
	ob_end_clean();
	ob_start();
	echo "<body bgcolor='#e7e8ed'>";
	include "gen_pdf.php";

	$content .= ob_get_clean();
}
//echo $content;




// conversion HTML => PDF
require_once(dirname(__FILE__).'\html2pdf\html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4', 'es');
$html2pdf->WriteHTML($content);
$html2pdf->Output('entrevista.pdf');

?>
</html>

