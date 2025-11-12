<?php
/**
* -------------------------------------------------------------------------------------
* Verifico si el usuario tiene permisos
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if ( ! $user->tienePermiso('multiplerub') ) {
// El usuario no esta registrado o no tiene permisos
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/login/index.php');
}
/**
* -------------------------------------------------------------------------------------
*/
?>
<html>
<head>
  <td valign="middle">&nbsp;
				<img src="img/logo.gif" />
   </td>
<title>Impresion multiples fichas RUB</title>
<script language="JavaScript">
function validar_form() {
	// antes de invocar la búsqueda valida que halla una expresión indicada
	
	if (document.form.csvfile.value == '') {
		alert("Ingrese un archivo con los NTITULARES separados por comas a buscar.");
		document.form.csvfile.focus();		
		return false; 
		}
	else 
		return true;
	}
	
	</script>
	
	<style type="text/css">
	h1 {color: white; font: bold 20px Times, serif; 
         }
  BODY{   
     background-repeat: no-repeat;
    
    }
	</style>
</head>

<body bgcolor="#ffffff">

<form enctype="multipart/form-data" action="#" name="form" method="POST" target="_self">
<h3>Impresi&oacute;n de M&uacute;ltiples Fichas RUB</h3>
Archivo CSV de NTITULARES
<input name="csvfile" type="file">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<br/>
<br/>
<input type="submit" name="cons" value="Enviar" onclick="javascript: return validar_form()">

</form>
<br>
<br>
<br>

<?php

if(!empty($_FILES)){
	$fileName = $_FILES['csvfile']['name'];
	$file = $_FILES['csvfile']['tmp_name'];
	$ntitulares;
	
// Abro CSV y parseo el archivo
	$fp = fopen ( $file , "r" );
	while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
		foreach($data as $row) {
			$ntitulares[] = $row;
		}
	}
	fclose ( $fp );
		
// Muestro los NTITULARES encontrados en el CSV
	echo "<p>Se han encontrado los siguientes NTITULARES en el archivo:</p><p>";
	$url_get = "generar.php?";
	foreach ($ntitulares as $ntitu) {
		$nrub = getNroRun($ntitu);
		echo "[$ntitu] ";
		$url_get .= "ntitulares[]=$ntitu&rubs[]=$nrub&"; 
	}
	
// Link para ver FICHAS RUB de los NTITULARES
	$url_get = substr($url_get, 0, strlen($url_get) -1 );
	echo "</p><br/><a href='$url_get' target='_blank'>Ver Fichas RUB</a></br>";
	
}
?>
</center>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

       <tr>
        <td><table style="width:100%; border:0px;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="width:122px; background-image:url(images/colorelement01.jpg); border:0px;" align="left" valign="top" >
            <table style="width:122px; border:0px; background-image:url(images/colorelement01.jpg);" cellpadding="0" cellspacing="0">
              <tr>
                <td style="height:30px;" align="center"><a href='simple.php' target='_self' style='font-size:9pt;'>Consulta Fichas rub</a></td>
              </tr>
			  <tr>
                <td style="height:30px;" align="center"><a href='solorub.php?links' target='_self' style='font-size:9pt;'>Buscar por RUB</a></td>
              </tr>
              <tr>
                <td style="height:30px;" align="center"><a href='simple.php' target='_self' style='font-size:9pt;'>Sistema Integrado</a></td>
              </tr>
	    </tr>

            </table></td>

</html>

<?php 
function getNroRun($ntitu) {
	include "config.php";
	$rep=odbc_exec($conn, "_nrorub $ntitu");
	$rsresol=odbc_fetch_object($rep);
	odbc_close($conn);
	return $rsresol->rub;
}
?>
