
<?php

require_once("conexion.php");

$nombre = $_FILES['imagen']['name'];
$nombrer = strtolower($nombre);
$cd=$_FILES['imagen']['tmp_name'];
$ruta = "img/" . $_FILES['imagen']['name'];
$destino = "img/".$nombrer;
$resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);

if (!empty($resultado)){

	@mysqli_query($conexion,"INSERT INTO fotos VALUES ('". $nombre."','" . $destino . "')");
	echo "el archivo ha sido movido exitosamente";

}else{

	echo "Error al subir el archivo";

}
?>