<?php 
/*
session_start();
$numeroConsulta  = $_SESSION['numeroConsulta'];
$carpeta='http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/doc_adjuntada/';
echo $carpeta;
die();
$total_imagenes = count(glob("$carpeta.$numeroConsulta.pdf",GLOB_BRACE));
echo "total_imagenes = ".$total_imagenes;
*/
$carpeta='http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/doc_adjuntada';
$total_imagenes = count(glob("carpeta/{*.pdf,*.gif,*.png}",GLOB_BRACE));
echo "total_imagenes = ".$total_imagenes;


?>