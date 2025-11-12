<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html"; charset=utf-8"/> 
</head>
<h3 style='text-align:center}'>SITUACION ENTREVISTA </h3>
	<br>
<?php

$hora=$_GET['hora'];
session_start();
$uname  = $_SESSION['uname'];
header("Content-Type: text/html;charset=utf-8");
$server_file = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/entrevista/';
$filecmn = $_GET[numeroConsulta]. '.pdf';
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
	
	    echo '<td  bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('composFamiliar')) . '</td>';
	    echo '<td  bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('estado')) . '</td>';
        echo "<td style='text-align: left; border: solid 1px black'>";	
			
			$identrevista    =  $situacionEntre->getProperty('identrevista');
			$numeroConsulta  =  $situacionEntre->getProperty('numeroconsulta'); 
			$composFamiliar  =  utf8_encode($situacionEntre->getProperty('composFamiliar'));
			$sitEconomica    =  utf8_encode($situacionEntre->getProperty('siteconomica'));
			$sitHabitacional =  utf8_encode($situacionEntre->getProperty('sithabitacional'));
			$sitSalud        =  utf8_encode($situacionEntre->getProperty('sitsalud'));
			$sitEducacion    =  utf8_encode($situacionEntre->getProperty('siteducacion'));
			$idpersonahogar  =  utf8_encode($situacionEntre->getProperty('idpersonahogar'));
			$idhogar         =  utf8_encode($situacionEntre->getProperty('idhogar'));
			$ntitu           =  utf8_encode($situacionEntre->getProperty('nrotitular'));
			$nrorub           =  utf8_encode($situacionEntre->getProperty('nro_rub'));
			$idcaso          =  utf8_encode($situacionEntre->getProperty('idcaso'));
			$mantienecompo   =  utf8_encode($situacionEntre->getProperty('mantienecompo'));
			$completa        =  utf8_encode($situacionEntre->getProperty('completa'));
			$estado          =  utf8_encode($situacionEntre->getProperty('estado'));
			$observacion     =  utf8_encode($situacionEntre->getProperty('observacion'));
			$evaluada        =  utf8_encode($situacionEntre->getProperty('evaluada'));
			$registrado_eva  =  utf8_encode($situacionEntre->getProperty('registrado_eva'));
			
			
			/*		
			$EditSituacion = './FormEditSituacionEntrevista.php?numeroconsulta=' .$numeroConsulta."&composFamiliar=$composFamiliar&identrevista=$identrevista&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub
			&sitEconomica=$sitEconomica&sitHabitacional=$sitHabitacional&sitSalud=$sitSalud
			&sitEducacion=$sitEducacion&idpersonahogar=$idpersonahogar&mantienecompo=$mantienecompo&observacion=$observacion&completa=$completa&evaluada=$evaluada&registrado_eva=$registrado_eva&hora=$hora";
		
			if($registrado_eva == 'SI'){
			   echo "<a href=''>Ver</a>";			
			}else {
			   echo "<a href='$EditSituacion'>Ver</a>";
			}
			*/
			
			
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

<script>
function abrirFormularioInferior(url) {
    const width = screen.availWidth;
    const height = 400; // Altura deseada
    const top = screen.availHeight - height; // Posición inferior

    window.open(url, 'popupInferior', `width=${width},height=${height},top=${top},left=0,resizable=no,scrollbars=yes`);
}
</script>

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
	    echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('mantienecompo')) . '</td>';
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
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('sitEconomica')) . '</td>';
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
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('sitHabitacional')) . '</td>';
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
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('sitSalud')) . '</td>';
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
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('sitEducacion')) . '</td>';
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
	
	        echo '<td bgcolor="#FFFFCC" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('observacion')) . '</td>';
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
	        
	        echo '<td bgcolor="#ffcceb" style="text-align: left; border: solid 1px black">' . utf8_encode($situacionEntre->getProperty('registrado_eva')) . '</td>';
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
  $agregarSituacion = './FormInsertSituacionEntrevista.php?numeroconsulta=' .$numeroConsulta."&composFamiliar=$composFamiliar&identrevista=$identrevista&idpersonahogar=$idpersonahogar&uname=$uname&hora=$hora&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub";

  ?>

  <table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
       <tr>
			<th style="width: 80%; text-align: left; border: solid 1px black"><i>Composicion Familiar</i></th>
			<th style="width: 50%; text-align: left; border: solid 1px black"><i>Acciones</i></th>
	   </tr>
 	   <tr>
	        <th style="width: 80%; text-align: left; border: solid 1px black"><i></i></th>
			<th style="width: 50%; text-align: left; border: solid 1px black"><i></i><?php echo  "<a href='$agregarSituacion' > Agregar.</a>"; ?> </th>
	  																  
 </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
 
 <?php  



}

function sanear_string($string)
{
	$string = trim($string);

	
	return $string;
}

?>
</html>
