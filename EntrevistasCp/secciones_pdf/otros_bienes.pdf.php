<?php
$obienesDO = $sh->getHogarRub()->getotro_Bienes();
if ( ! $obienesDO->isEmpty() ) 
{
	$obienes_ = $obienesDO->getUniqueData();
?>
	<h3 style='text-align:center}'> Otros Bienes del hogar </h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 100%; text-align: center; border: solid 1px black;"><i></i></th>
		</tr>
		<?php
		foreach($obienes_->getProperties() as $otrobienDesc){
			if ( ! empty($otrobienDesc) ) {
				echo "<tr><td style='text-align: left; border: solid 1px black;'>$otrobienDesc</td></tr>";
				
			}
		}
		?>
	</table>
<?php
}
?>