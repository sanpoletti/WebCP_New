<?php 

include 'entrevistas.class.php';

$consultas = $_POST['consultas'];
$usuario = $_POST['usuario']; 

echo "Asignando: " . implode(" - ", $consultas) . " al usuario: " . $usuario;

foreach ($consultas as $nroConsulta) {
	new AsignaUsuarioUpdate($nroConsulta, $usuario);
}

?>