<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html"; charset=utf-8"/> 
</head>
<script>
function abrirFormularioInferior(url) {
  const width = screen.availWidth;
  const height = 400;
  const top = screen.availHeight - height;
  window.open(url, "popupInferior", `width=${width},height=${height},top=${top},left=0,resizable=no,scrollbars=yes`);
}
</script>




<h3 style='text-align:center}'>SITUACION ENTREVISTA </h3>
	<br>
<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';
$numeroConsulta = isset($_GET['numeroConsulta']) ? $_GET['numeroConsulta'] : '';
$uname = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'Desconocido';
//echo "👤 Usuario: $uname";
header("Content-Type: text/html;charset=utf-8");
$server_file = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/entrevista/';
$filecmn = $numeroConsulta. '.pdf';
$filenameEntrevista = $server_file . $filecmn;

$server_file_ = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/doc_adeudada/';
$filenameDocFaltante = $server_file_ . $filecmn;


?>
	
<?php


$situacionEntrevista = $sh->getHogarCP()->getSituacionEntrevista();
if (!$situacionEntrevista->isEmpty())
 {
?>

    <table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
	    	<th style="width:85%; text-align: left; border: solid 1px black"><i>Composicion Familiar</i></th>
	    	<th style="width: 7%; text-align: left; border: solid 1px black"><i>Estado</i></th>
	    	<th style="width: 8%; text-align: left; border: solid 1px black">Acciones<i></i></th>
		</tr>
		
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	
	    echo '<td  bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' .$situacionEntre->getProperty('composFamiliar') . '</td>';
	    echo '<td  bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('estado') . '</td>';
        echo "<td style='text-align: left; border: solid 1px black'>";	
			
			$identrevista    =  $situacionEntre->getProperty('identrevista');
			$numeroConsulta  =  $situacionEntre->getProperty('numeroconsulta'); 
			$composFamiliar  =  $situacionEntre->getProperty('composFamiliar');
			$sitEconomica    =  $situacionEntre->getProperty('siteconomica');
			$sitHabitacional =  $situacionEntre->getProperty('sithabitacional');
			$sitSalud        =  $situacionEntre->getProperty('sitsalud');
			$sitEducacion    =  $situacionEntre->getProperty('siteducacion');
			$idpersonahogar  =  $situacionEntre->getProperty('idpersonahogar');
			$idhogar         =  $situacionEntre->getProperty('idhogar');
			$ntitu           =  $situacionEntre->getProperty('nrotitular');
			$nrorub           = $situacionEntre->getProperty('nro_rub');
			$idcaso          =  $situacionEntre->getProperty('idcaso');
			$mantienecompo   =  $situacionEntre->getProperty('mantienecompo');
			$completa        =  $situacionEntre->getProperty('completa');
			$estado          =  $situacionEntre->getProperty('estado');
			$observacion     =  $situacionEntre->getProperty('observacion');
			$evaluada        =  $situacionEntre->getProperty('evaluada');
			$registrado_eva  =  $situacionEntre->getProperty('registrado_eva');
			
		
			
			$EditSituacion = './FormEditSituacionEntrevista.php?numeroconsulta=' . urlencode($numeroConsulta) .
			"&composFamiliar=" . urlencode($composFamiliar) .
			"&identrevista=" . urlencode($identrevista) .
			"&ntitu=" . urlencode($ntitu) .
			"&idhogar=" . urlencode($idhogar) .
			"&nrorub=" . urlencode($nrorub) .
			"&sitEconomica=" . urlencode($sitEconomica) .
			"&sitHabitacional=" . urlencode($sitHabitacional) .
			"&sitSalud=" . urlencode($sitSalud) .
			"&sitEducacion=" . urlencode($sitEducacion) .
			"&idpersonahogar=" . urlencode($idpersonahogar) .
			"&mantienecompo=" . urlencode($mantienecompo) .
			"&observacion=" . urlencode($observacion) .
			"&completa=" . urlencode($completa) .
			"&evaluada=" . urlencode($evaluada) .
			"&registrado_eva=" . urlencode($registrado_eva) .
			"&hora=" . urlencode($hora);
			?>



<?php
if ($registrado_eva == 'SI') {
    echo "<a href='#' onclick='alert(\"Formulario ya registrado.\"); return false;'>Ver</a>";
} else {
    echo "<a href='#' onclick=\"abrirFormularioInferior('$EditSituacion'); return false;\">Ver</a>";
}

			
			
			
			
			echo "<td style='text-align: left; border: solid 1px black'>";
			echo "</td></tr>";

			
		} ?>
	    
	    
	    <tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	    
	    
	    
	    <tr>
	    	<th style="width: 90%; text-align: left; border: solid 1px black"><i>Mantiene Conformacion del Hogar:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	    echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('mantienecompo') . '</td>';
		    echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
		    echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";			
		} ?>
	    
	    
	   <tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	    
	   	<tr>
	    	<th style="width: 95%; text-align: left; border: solid 1px black"><i>Situacion Socio Economica:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('sitEconomica') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
		
		<tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>

	
		<tr>
	    	<th style="width: 95%; text-align: left; border: solid 1px black"><i>Situacion Habitacional:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('sitHabitacional') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
		
		<tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	
	    <tr>
	    	<th style="width: 90%; text-align: left; border: solid 1px black"><i>Situacion Salud:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('sitSalud') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
		
		<tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
		
		<tr>
	    	<th style="width: 90%; text-align: left; border: solid 1px black"><i>Situacion Educacion:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('sitEducacion') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
	
	    
	   <tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	
	
	
	
	
		<tr>
	    	<th style="width: 90%; text-align: left; border: solid 1px black"><i>Observaciones:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('observacion') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
	
		  <tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	
		<tr>
	    	<th style="width: 90%; text-align: left; border: solid 1px black"><i>Evaluada:</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black "><i></i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i></i></th>
		</tr>
	    <?php foreach($situacionEntrevista->getData() as $situacionEntre) { //edit
	        
	        echo '<td bgcolor="#ffcceb" style="text-align: left; border: solid 1px black">' . $situacionEntre->getProperty('registrado_eva') . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>"."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>"."</td></tr>";	
			
		} ?>
	
	    <tr>
	    	<th style="width:90%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
	    	<th style="width: 5%; text-align: left; border: solid 1px black">&nbsp;<i></i></th>
		</tr>
	
	
	
	
	
	
	
	</table>
	<BR>

<?php
} else {   //insert
    
    
        
    

  $idpersonahogar   = $_GET['idpersonahogar'];
  $identrevista=0;
  
  
  $numeroConsulta = $_GET['numeroConsulta'] ?? 0;
  $composFamiliar = ''; // o lo que corresponda
  $ntitu = $_GET['ntitu'] ?? '';
  $idhogar = $_GET['idhogar'] ?? '';
  $nrorub = $_GET['nrorub'] ?? '';
  
  
  $agregarSituacion = './FormInsertSituacionEntrevista.php?numeroconsulta=' .$numeroConsulta."&composFamiliar=$composFamiliar&identrevista=$identrevista&idpersonahogar=$idpersonahogar&uname=$uname&hora=$hora&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub";

  ?>

  <table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
       <tr>
			<th style="width: 80%; text-align: left; border: solid 1px black"><i>Composicion Familiar</i></th>
			<th style="width: 50%; text-align: left; border: solid 1px black"><i>Acciones</i></th>
	   </tr>
 	   <tr>
	        <th style="width: 80%; text-align: left; border: solid 1px black"><i></i></th>
			<th style="width: 50%; text-align: center; border: solid 1px black"><i></i><?php  echo "<a href='#' onclick=\"abrirFormularioInferior('$agregarSituacion'); return false;\">➕ Agregar Situacion</a>"; ?> </th>
																						   
	  																  
 </table>

 
 <?php  



}

function sanear_string($string)
{
	$string = trim($string);

	
	return $string;
}

?>
</html>
