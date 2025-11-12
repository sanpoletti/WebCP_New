<!DOCTYPE html>
<html lang="es">
<head>
<title>Listar Archivos</title>
<meta charset="UTF-8">
</head>
<body>
<?php



function contarFicheros($carpeta,$numeroConsulta){
$carpeta=dirname(__FILE__) . "/uploads/doc_adjuntada/";

$total_ficheros = 0;

	//La función recibira como parametro un carpeta
	


	if (is_dir($carpeta)) { //Comprobamos que sea un carpeta Valida
		if ($dir = opendir($carpeta)) {//Abrimos el carpeta
			
			  while (($archivo = readdir($dir)) !== false ){  	 
				if ($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' ){
					$comparacion = substr($archivo, 0, 6);
				     if ($numeroConsulta == $comparacion){
						$total_ficheros = $total_ficheros+1;
						 ?>
						
					<?php }?>
					
					<?php //Abrimos un elemento de lista
			 //Cerramos el item actual y se inicia la llamada al siguiente archivo
				}
			} //finaliza
			echo  '</ul>';//Se cierra la lista
			closedir($dir);//Se cierra el archivo
		} //echo  "Totala: ".$total_ficheros;
		return $total_ficheros;
	}else{//Finaliza el If de la linea 12, si no es un carpeta valido, muestra el siguiente mensaje
		echo 'No Existe la carpeta';
	}
}//Fin de la Función
//Llamamos a la función
//lista_archivos("./uploads/doc_adjuntada/");
//contarFicheros("/revision/uploads/doc_adjuntada/", $numeroConsulta);

?>
</body>
</html>