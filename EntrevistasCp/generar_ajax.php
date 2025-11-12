<?php 
/**
* -------------------------------------------------------------------------------------
* Verifico si el usuario tiene permisos
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if ( ! $user->tienePermiso('seguimiento') ) {
// El usuario no esta registrado o no tiene permisos
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/login/index.php');
}
/**
* -------------------------------------------------------------------------------------
*/
session_start();

$ntitu  = $_SESSION['ntitu'];
$idhogar= $_SESSION['idhogar'];
$nrorub = $_SESSION['nrorub'];
$uname  = $_SESSION['uname'];
$numeroConsulta  = $_SESSION['numeroConsulta'];
$idpersonahogar  = $_SESSION['idpersonahogar'];


// Seteo variables enviadas por POST
if( isset($_GET['ntitulares']) && !empty($_GET['ntitulares']) && isset($_GET['nrorub']) && !empty($_GET['nrorub']) && !empty($_GET['include']) ) {
    $ntitulares = $_GET['ntitulares'];
    $nrorub = $_GET['nrorub'];
    $include = $_GET['include'];
} else if( isset($_GET['ntitu']) && !empty($_GET['ntitu']) && isset($_GET['nrorub']) && !empty($_GET['include']) ) {
    $ntitu = $_GET['ntitu'];
    $nrorub = $_GET['nrorub'];
    $include = $_GET['include'];
} else if( isset($_GET['nrorub']) && !empty($_GET['nrorub']) && !empty($_GET['include']) ) {
    $nrorub = $_GET['nrorub'];
    $include = $_GET['include'];
} else {
    echo "No tiene permisos para generar el modelo";
    die();
}

	//echo "ntitular:".$ntitu;
	//die();

// ID Hogar (Solo usado en conformacion hogar anterior [cambiosntitular.php])
$idHogar = $_GET['idhogar'];
	// Solo se pasa este parametro cuando viene desde index.php
$numpersona = $_GET['numpersona'];

$idpersonahogar = $_GET['idpersonahogar'];

// Seteo como indefinido el tiempo de timeout
set_time_limit(0); 

$content = '';


ob_start();
include "gen_modelo.php";
include_once 'secciones_pdf/common_func.pdf.php';
ob_end_clean();
ob_start();
include '/secciones_pdf/' . $include . '.pdf.php';
$content .= ob_get_clean();

echo $content;

?>
</html>

