<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Hist&oacute;rico de Pagos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>

<?php
$idpersonahogar = $_GET['idpersona'];
// Seteo variables enviadas por POST
if( isset($_GET['ntitu']) && !empty($_GET['ntitu']) && !empty($_GET['ntitu'])&& isset($_GET['idpersona']) && !empty($_GET['idpersona']) && !empty($_GET['idpersona']) && isset($_GET['sec']) && isset($_GET['org'])) {
	$ntitu = $_GET['ntitu'];
	
	$idpersonahogar = $_GET['idpersona'];
	
	$sec = $_GET['sec'];
	$org = $_GET['org'];
} else {
	echo "No tiene permisos para acceder a este módulo";
	die();
}

// Seteo como indefinido el tiempo de timeout
set_time_limit(0); 

$content = '';


ob_start();
include 'historicoPagos.class.php';
$isCp = ($org=='CP');
$isPc = ($org=='RED');
$IsEet =($org=='EET');

if ($isCp) {
	$hp = new HistoricoPagos($ntitu);
 	}else if ($isPc){
		$hpPlan =  new HistoricoPagosPlanCp($idpersonahogar);
 	}else if ($IsEet){
 		$hp =new HistoricoPagosEET($ntitu, $sec);
 	}


//$hp = $isCp ? new HistoricoPagos($ntitu) : new HistoricoPagosEET($ntitu, $sec);

ob_end_clean();
ob_start();
echo "<body bgcolor='#e7e8ed'>";

if ($isCp) {
	include "gen_hp_pdf.php";
} else if ($IsEet){
	include "gen_hp_eet_pdf.php";
} else if ($isPc){

	include "gen_hp_Plan_pdf.php";
}

$content .= ob_get_clean();

echo $content;


// conversion HTML => PDF
/*require_once(dirname(__FILE__).'\html2pdf\html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4', 'es');
$html2pdf->WriteHTML($content);
$html2pdf->Output('rub.pdf');
*/
?>
</html>

