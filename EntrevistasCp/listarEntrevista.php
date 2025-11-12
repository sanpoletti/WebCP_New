<script type="text/javascript">

    
    // alert(evaluadaJ);
    

function Delete(archivo,evaluada) {
	
     if (confirm('Esta seguro de Eliminar la Entrevista, Compruebe que no haya sido Evaluada por Revision?')){
    	 
         document.location='borrarEntrevista.php?archivo='+archivo;
    }
}


function preguntar(fichero){
   eliminar=confirm("¿Deseas eliminar este registro?");
   if (eliminar)
   //Redireccionamos si das a aceptar
 
     window.location.href="borrar.php?fichero=<?php echo $archivo; ?>";
else
  //Y aquí pon cualquier cosa que quieras que salga si le diste al boton de cancelar
    alert('No se ha podido eliminar La Entrevista..')
}

</script>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Entrevista</title>
<script type='text/JavaScript' src='js/jacs.js'></script>
<script type='text/JavaScript' src='js/jacsLanguages.js'></script>
<script type='text/JavaScript' src='js/jacsDemo.js'></script>
		<table style="width: 100%;">
			<tr>
				<td style="align:left;width: 60%"><img src="./img/logo.gif" alt=""/></td>
				
				
			</tr>
		</table>
<body>
<hr>
	<table cellspacing="5" style="width: 100%;">
		<tr>
			<td><h3 style="text-align:center;color: red;">Entrevista Adjuntada</h3></td>
	
		</tr>
	</table>
<?php
$evaluada= $_GET['eva'];

session_start();
$numeroConsulta  = $_SESSION['numeroConsulta'];

$carpeta = $_SERVER['DOCUMENT_ROOT'] . "/desarrollo_informes_entrevistas/uploads/entrevista/";

//Creamos Nuestra Función
function lista_archivos($carpeta,$numeroConsulta,$evaluada){ 
$total_ficheros = 0;

$server  = 'http://' .$_SERVER['HTTP_HOST'] . "/desarrollo_informes_entrevistas/uploads/entrevista/";
$carpeta = $_SERVER['DOCUMENT_ROOT'] . "/desarrollo_informes_entrevistas/uploads/entrevista/";
	//La función recibira como parametro un carpeta
	


	if (is_dir($carpeta)) { //Comprobamos que sea un carpeta Valida
		if ($dir = opendir($carpeta)) {//Abrimos el carpeta
			
			  while (($archivo = readdir($dir)) !== false ){  	 
				if ($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' ){
				    $comparacion = substr($archivo, 0, 6);
				     	     	
				   
			        // die();
					 if ($numeroConsulta == $comparacion){
						$total_ficheros = $total_ficheros+1;
						$pdfFechaCreacion= $_SERVER['DOCUMENT_ROOT'] . "/desarrollo_informes_entrevistas/uploads/entrevista/".$archivo;
						?>
						 
						  <table cellspacing="5" style="width: 100%;">
						  <tr>
						    
						    <th style="width: 10%;font-size: 20px; text-align: center; border: solid 0.2px black"><?php echo  'Entrevista'.'---'.date("d/m/Y",filemtime($pdfFechaCreacion)) ?> &nbsp;&nbsp;<a href='<?php echo $server . $archivo; ?>' target='_blank'>Ver</a>&nbsp;&nbsp;&nbsp;
						    <?php if ($evaluada=='NO'){?>    
						    		<a href=javascript:Delete(['<?php echo $archivo; ?>'])>Eliminar</a>
						     <?php 
						    }
						    ?>
						    </th>
					      
					      </tr>
					       </table>
					       <?php 
					 }
					
					
					?>
					
					<?php //Abrimos un elemento de lista
			 //Cerramos el item actual y se inicia la llamada al siguiente archivo
				}
			} //finaliza
			echo  '</ul>';//Se cierra la lista
			closedir($dir);//Se cierra el archivo
		} 
		//echo  "Total Archivos: ".$total_ficheros;
	
	}else{//Finaliza el If de la linea 12, si no es un carpeta valido, muestra el siguiente mensaje
		echo 'No Existe la carpeta';
	}
}//Fin de la Función
//Llamamos a la función

lista_archivos("./uploads/entrevista/", $numeroConsulta,$evaluada);
?>
</body>
</html>