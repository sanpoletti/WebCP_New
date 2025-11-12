<?php
/**
* -------------------------------------------------------------------------------------
* Verifico si el usuario tiene permisos
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if ( ! $user->tienePermiso('simplerub') ) {
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
<title>Consulta de hogar RUB</title>
<script src="js/validaciones.js"></script>
<script language="JavaScript">
function validar_form() {
	// antes de invocar la búsqueda valida que halla una expresión indicada
	var result = true;
	if (document.form.rub.value != '') {
		if (!IsNumeric(document.form.rub.value)) {
			alert("Ingrese un número de RUB válido.");
			result = false;
		}
	} else {
		alert("Ingrese un número de RUB.");
		document.form.rub.focus();		
		result = false;
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

<body bgcolor="#ffffff">

<h1>
    <p><em><strong><h1>CON TODO DERECHO   &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;    CIUDADANIA PORTE&Ntilde;A</h1></strong></em></p>
  </h1>
  
<center><h2>Impresi&oacute;n de Fichas</h2><br>

<form action="generar.php" name="form" method="GET"  target="_blank" >
<h3> Hogar RUB</h3>
Nro Rub: <input name="rubs" type="text">

<input type="submit" name="cons" value="Ingresar" onclick="javascript: return validar_form()">


</br>
</br>
</br>



<a href="javascript:history.back()">Volver</a>

</form>

</center>
<p>&nbsp;</p>
<p>&nbsp;</p>

<?php 
if (isset($_GET['links'])) 
{ ?>

       <tr>
        <td><table style="width:100%; border:0px;" cellspacing="0" cellpadding="0">
          <tr>
            <td style="width:122px; background-image:url(images/colorelement01.jpg); border:0px;" align="left" valign="top" >
            <table style="width:122px; border:0px; background-image:url(images/colorelement01.jpg);" cellpadding="0" cellspacing="0">
              <tr>
                <td style="height:30px;" align="center"><a href='simple.php' target='_self' style='font-size:9pt;'>Consulta Fichas rub</a></td>
              </tr>
			  <tr>
                <td style="height:30px;" align="center"><a href='Multiple.php' target='_self' style='font-size:9pt;'>Multiples fichas RUB</a></td>
              </tr>
              <tr>
                <td style="height:30px;" align="center"><a href='simple.php' target='_self' style='font-size:9pt;'>Sistema Integrado</a></td>
              </tr>
	    </tr>

            </table></td>
<?php
}
?>
</body>

</html>