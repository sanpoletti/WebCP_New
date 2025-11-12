<?php
/**
* -------------------------------------------------------------------------------------
* Verifico si el usuario tiene permisos
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if (!$user->tienePermiso('seguimiento')) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/index.php');
    exit;
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
<title>ICHA RUB</title>
<script src="js/validaciones.js"></script>
<script language="JavaScript">
function validar_form() {
	// antes de invocar la b�squeda valida que halla una expresi�n indicada
	var result = true;
	if (document.form.dni.value != '') {
		if (!IsNumeric(document.form.dni.value)) {
			alert("Ingrese un n�mero de DNI v�lido.");
			result = false;
		}
	} else  {
		if (document.form.ntitu.value != ''){
			if (!IsNumeric(document.form.ntitu.value)) {
				alert("Ingrese un n�mero de NTITULAR v�lido.");
				result = false;
			}
		} else {
			alert("Ingrese un n�mero de DNI o NTITULAR.");
			document.form.dni.focus();		
			result = false;
		}
	}
	return result;
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

<body backgroud ="img/contododerecho.gif" >

  
<center><h2>Impresi&oacute;n de Ficha RUB</h2><br>

<form action="#" name="form" method="post"  target="_self" >
Nro doc:<input name="dni" type="text">
Ntitular:<input name="ntitu" type="text">

<input type="submit" name="cons" value="Buscar" onclick="javascript: return validar_form()">

<pre><?php

// Seteo variables enviadas por POST
if( isset($_POST['ntitu']) && !empty($_POST['ntitu']) ) {
	$ntitu = $_POST['ntitu'];
} else if( isset($_POST['dni']) && !empty($_POST['dni'])) {
	$dni = $_POST['dni'];
}

// Busco personas por DNI
if(!empty($ntitu) || !empty($dni)){
	include "entrevistas.class.php";
	$personas = new Personas($ntitu, $dni);
	
	if ( ! $personas->isEmpty() ) {
		echo "<h1>Inscriptos</h1><br>";
		foreach ($personas->getData() as $rdo) {
			echo '<a href=generar.php?NroDoc='.$rdo->getProperty('dni').'&ntitu='.$rdo->getProperty('nrotitular').'&rubs='.$rdo->getProperty('nrorub')." target='_blank'>".$rdo->getProperty('APELLIDO').', '.$rdo->getProperty('NOMBRE').', '.$rdo->getProperty('nrotitular').'</a></br>';
		}	
	} else {
		echo "No se encuentran inscriptos con los datos ingresados.";
	}
}

?>  </pre></center>
</body >
</html>
       <tr>
        <td><table style="width:100%; border:0px;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="width:122px; background-image:url(images/colorelement01.jpg); border:0px;" align="left" valign="top" >
            <table style="width:122px; border:0px; background-image:url(images/colorelement01.jpg);" cellpadding="0" cellspacing="0">
              <tr>
                <td style="height:30px;" align="center"><a href='multiple.php' target='_self' style='font-size:9pt;'>Multiples Fichas rub</a></td>
              </tr>
			  <tr>
                <td style="height:30px;" align="center"><a href='solorub.php?links' target='_self' style='font-size:9pt;'>Buscar por RUB</a></td>
              </tr>
              <tr>
                <td style="height:30px;" align="center"><a href='simple.php' target='_self' style='font-size:9pt;'>Sistema Integrado</a></td>
              </tr>
	    </tr>

            </table></td>
			