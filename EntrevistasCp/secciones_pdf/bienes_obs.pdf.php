<?php
$bienes_obs = $sh->getHogarRub()->getBienes_obs();
if ( ! $bienes_obs->isEmpty() ) 
{
	$bienesOB = $bienes_obs->getUniqueData();
?>
	<h3 style='text-align:center}'>Bienes por Observacion </h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 100%; text-align: center; border: solid 1px black;"><i></i></th>
		</tr>
		<?php
		foreach($bienesOB->getProperties() as $bienDesc){
			if ( ! empty($bienDesc) ) {
				echo "<tr><td style='text-align: left; border: solid 1px black;'>$bienDesc</td></tr>";
			}
		}
		?>
	</table>
<?php
}
?>