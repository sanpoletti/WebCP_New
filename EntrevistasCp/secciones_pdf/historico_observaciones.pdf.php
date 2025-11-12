<?php
$observH =  $ho->getHistoricoObserva();


if (!$observH->isEmpty())  
{ 
	$observaciones = $observH->getData();
?>
	<h3 style='text-align:left}'>OBSERVACIONES EN EL HOGAR CP SINTYS</h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
			<th style="width: 5%;  text-align: left; border: solid 1px black;"><i>Cod.</i></th>
			<th style="width: 30%; text-align: left; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 7%;  text-align: left; border: solid 1px black"><i>Monto</i></th>
			<th style="width: 58%; text-align: left; border: solid 1px black"><i>Observaci�n</i></th>
		</tr>
		<?php
		$bg_css = "";
		foreach($observaciones as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('codigo')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".getApeNom($obrdo)."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('monto')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".getLimitedLengthString($obrdo->getProperty('OBSERVACION'),85)."</td></tr>";
		}
		?>
	</table>

	<?php

}
?>