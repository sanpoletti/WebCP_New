<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Detalle de Ingresos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>

<?php

//echo $_GET['numpersona'];
//die;


// Seteo variables enviadas por POST
if( isset($_GET['rub']) && !empty($_GET['rub']) ) {
	$rub = $_GET['rub'];
	
} else {
	echo "No tiene permisos para acceder a este m�dulo";
	die();
}

// Seteo como indefinido el tiempo de timeout
set_time_limit(0); 

$content = '';


ob_start();
include 'entrevistas.class.php';
$detalleIngresos = new detalleIngresos($rub);
ob_end_clean();
ob_start();
echo "<body bgcolor='#e7e8ed'>";
include "gen_detalle_Ingresos.php";
$content .= ob_get_clean();

echo $content;


?>
</html>