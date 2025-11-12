<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />


</head>
<?php
$observDO = $sh->getHogarCP()->getObservacionesHogar();


if (!$observDO->isEmpty())  
{ 
	$observaciones = $observDO->getData();
	$histURL='./hist_observ.php?&ntitu=' . $_GET['ntitu'] ;
?>

	<h3 style='text-align:left}'>OBSERVACIONES SINTYS <a href='<?php echo $histURL; ?>' target='_blank'>(Hist. Observaciones)</a></h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
		
			<th style="width: 7%;  text-align: left; border: solid 1px black"><i>Monto/Cant.</i></th>
			<th style="width: 48%; text-align: left; border: solid 1px black"><i>Observacion</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Detalle</i></th>
		</tr>
		<?php
		foreach($observaciones as $obrdo){
	
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('monto')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".getLimitedLengthString($obrdo->getProperty('OBSERVACION'),85)."</td>";
			echo "<td style='text-align: left; border: solid 1px black'>";
			$idpersonahogar = $obrdo->getProperty('idpersonahogar');
			$histoInmueble = './hist_inmueble.php?idpersonahogar=' . $obrdo->getProperty('idpersonahogar'). '&idtipo=' . $obrdo->getProperty('idtipo');
			echo "<a href='$histoInmueble' target='_blank'>Ver</a>";
			echo "</td></tr>";


	
}
		?>
	</table>
	<BR>

	<?php

}else {
    echo "";
}
?>