<?php
$bienesDO = $sh->getHogarRub()->getBienes();
if ( ! $bienesDO->isEmpty() )
{
    $bienes = $bienesDO->getUniqueData();
    ?>
	<h3 style='text-align:center}'>Bienes </h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 100%; text-align: center; border: solid 1px black;"><i></i></th>
		</tr>
		<?php
		foreach($bienes->getProperties() as $bienDesc){
			if ( ! empty($bienDesc) ) {
				echo "<tr><td style='text-align: left; border: solid 1px black;'>$bienDesc</td></tr>";
			}
		}
		?>
	</table>
<?php
}
?>