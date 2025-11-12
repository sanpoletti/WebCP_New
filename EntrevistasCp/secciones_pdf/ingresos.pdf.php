<?php

$rub = $sh->getHogarRub()->getIngresos()->getData();
if (count($rub) > 0) 
{
?>
	<h3 style='text-align:center}'>Ingresos Hogares RUB</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 31%; text-align: center; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 12%; text-align: center; border: solid 1px black"><i>Nivel Educ.</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Laborales</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Segu. Desemp.</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Indem.x Despido</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Jubilatorios</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>No Laborales</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Detalle Ingresos</i></th>
			<th style="width: 12%; text-align: center; border: solid 1px black"><i>Total</i></th>
		</tr>
		<?php
		$bg_css = 'background-color: #FFFFCC;';
		$sumLab = 0;
		$sumSeg = 0;
		$sumIndem = 0;
		$sumJub = 0;
		$sumNlab = 0;
		$sumTotal = 0;
		$nada = 0;
		foreach($rub as $rdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".getApeNom($rdo)."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>". $rdo->getProperty('NI')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($rdo->getProperty('laboral'))."</td>";
			$sumLab += $rdo->getProperty('laboral');
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($rdo->getProperty('seg_desemp'))."</td>";
			$sumSeg += $rdo->getProperty('seg_desemp');
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($rdo->getProperty('indem_desp'))."</td>";
			$sumIndem += $rdo->getProperty('indem_desp');
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($rdo->getProperty('JUBILATORIOS'))."</td>";
			$sumJub += $rdo->getProperty('JUBILATORIOS');
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($rdo->getProperty('INGR_NLAB'))."</td>";
			$sumNlab += $rdo->getProperty('INGR_NLAB');
		
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>"."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'></td></tr>";
			$sumTotal += $rdo->getProperty('TOTAL');
			
			$bg_css = '';
		}
	// Imprimo TOTALES
		echo "<tr><td style='text-align: center; border: solid 1px black;background-color: #e3dbdb;'>TOTALES</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>&nbsp;</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumLab)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumSeg)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumIndem)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumJub)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumNlab)."</td>";
		
		echo "<td style='text-align: left; border: solid 1px black'>";
		$rub = $rdo->getProperty('numero_de_rub');
		$ingresoRub = './DetalleIngresosRub.php?rub=' . $rdo->getProperty('rub');
		echo "<a href='$ingresoRub' target='_blank'>Ver</a>";
		echo "</td>";
		
		if ($nrorub >= 148649){
		   echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumTotal)."</td></tr>";
		}
		if ($nrorub <= 148649){
		   $sumTotal= 0;
		   echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($rdo->getProperty('TOTAL'))."</td></tr>";
		}
		
		?>
	</table>
<?php
}
?>
