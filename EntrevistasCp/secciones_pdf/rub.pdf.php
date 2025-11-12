<?php 
 
$hogarRub = $sh->getHogarRub();

if (!$hogarRub->isEmpty())
{
	$clasiRub = $hogarRub->getClasiRub();	
	$domiRub =  $hogarRub->getDomicilioRub();
	
	
	$anioRub = '---';
	$relRub  = '---';
	
	if (is_object($domiRub)) {
	    $unique = $domiRub->getUniqueData();
	    if (is_object($unique)) {
	        $propAnio = $unique->getProperty('anio');
	        $propRel  = $unique->getProperty('rel');
	        
	        if ($propAnio !== null && $propAnio !== '') $anioRub = $propAnio;
	        if ($propRel  !== null && $propRel  !== '') $relRub  = $propRel;
	    }
	}
		
?>	
	<table cellspacing="5" style="width: 100%;">
			<tr>
				<td style="width: 10%; text-align: left;"><h3 style='width: 100%; text-align:center}'>CONFORMACION HOGAR RUB</h3></td>
				
				<td style="width: 20%; text-align: left; vertical-align: bottom;"><u>Nro de Rub:</u><i><?php echo $hogarRub->getNRub(); ?></i></td>
				
				<td style="width: 15%; text-align: center; vertical-align: bottom;"><u>Fecha RUB:</u><i><?= htmlspecialchars($anioRub) ?></i></td>
				<td style="width: 15%; text-align: right; vertical-align: bottom;"><u>Rel:</u><i><?= htmlspecialchars($relRub) ?></i></td>
			</tr>
	</table>

	<table style="width: 100%;border: solid 1px black; border-collapse: collapse;table-layout:fixed" align="center">
		<tr>
			<th style="width: 25%; text-align: center; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Nro. Doc.</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Fecha Nac.</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Parentesco</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Ocupacion</i></th>
			<th style="width: 25%; text-align: center; border: solid 1px black"><i>Actividad</i></th>
		</tr>
		
		<?php
		$bg_css = 'background-color: #FFFFCC;';
		foreach($hogarRub->getData() as $hrdo){
				echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".getApeNom($hrdo)."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$hrdo->getProperty('nro_doc')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$hrdo->getProperty('fecha_nac')."</td>";
				echo "<td style='text-align: center; border: solid 1px black;$bg_css'>".$hrdo->getProperty('paren')."</td>";
				echo "<td style='text-align: center; border: solid 1px black;$bg_css'>".$hrdo->getProperty('con_ocup')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$hrdo->getProperty('actividad')."</td></tr>";
				$bg_css = '';
		}
		?>
	</table>
<?php
}else {
    echo "";
}
?>