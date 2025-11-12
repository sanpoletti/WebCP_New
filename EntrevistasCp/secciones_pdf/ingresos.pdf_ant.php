<?php
if (count($r->hogarRub) > 0) 
{
?>
	<h3 style='text-align:center}'>Ingresos</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 31%; text-align: center; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 12%; text-align: center; border: solid 1px black"><i>Nivel Educ.</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Laborales</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Segu. Desemp.</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Indem.x Despido</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>Jubilatorios</i></th>
			<th style="width: 9%; text-align: center; border: solid 1px black"><i>No Laborales</i></th>
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
		foreach($r->hogarRub as $k){
			if (!$k->es_hogar()) {
				echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$k->apenom."</td>";
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$k->niveli."</td>";
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($k->laborales)."</td>";
				$sumLab += $k->laborales;
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($k->seg_desemp)."</td>";
				$sumSeg += $k->seg_desemp;
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($k->indem_desp)."</td>";
				$sumIndem += $k->indem_desp;
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($k->jub)."</td>";
				$sumJub += $k->jub;
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($k->nlaborales)."</td>";
				$sumNlab += $k->nlaborales;
				echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".getMoneyString($nada)."</td></tr>";
				$sumTotal += $k->Total;
				
				$bg_css = '';
			}
		}
	// Imprimo TOTALES
		echo "<tr><td style='text-align: center; border: solid 1px black;background-color: #e3dbdb;'>TOTALES</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>&nbsp;</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumLab)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumSeg)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumIndem)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumJub)."</td>";
		echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumNlab)."</td>";
		if ($nrorub >= 148649){
		   echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($sumTotal)."</td></tr>";
		}
		if ($nrorub <= 148649){
		   $sumTotal= 0;
		   echo "<td style='text-align: right; border: solid 1px black;background-color: #e3dbdb;'>".getMoneyString($k->Total)."</td></tr>";
		}
		
		?>
	</table>
<?php
}
?>
