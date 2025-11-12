<?php
$Historico = $sh->getHogarCP()->getHistoricoPagos();


if (!$Historico->isEmpty())  
{ 
	$Historico_pagos = $Historico->getData();
?>
	<h3 style='text-align:center}'>HISTORICO </h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
			<th style="width: 5%; text-align: center; border: solid 1px black;"><i>MES</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>MONTO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>RETRO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>DEPOSITO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>SANCION</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>CAMBIO</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>RESOLUCION</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>CATEGORIA</i></th>
		</tr>
		<?php
		foreach($Historico_pagos as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('codigo')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".getApeNom($obrdo)."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('mespago')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('monto')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('retro')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('deposito')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('sancion')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('cambio')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('resolucion')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('categoria')."</td></tr>";
		}
		?>
	</table>

	<?php
	echoFeetNotes();
}
?>