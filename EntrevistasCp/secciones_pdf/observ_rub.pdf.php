<?php

$observDO = $sh->getHogarRub()->getObservaciones();
if (! $observDO->isEmpty() ) 
{
	$observaciones = $observDO->getData();
?>
	<h3 style='text-align:center}'>Observaciones del Hogar RUB</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse;table-layout:fixed" align="center">
		<tr>
			<th style="width: 40%; text-align: center; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 40%; text-align: center; border: solid 1px black"><i>Observación</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Monto</i></th>
		</tr>
		
		<?php
		$bg_css = 'background-color: #FFFFCC;';
		foreach($observaciones as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('apenom')."</td>";
			echo "<td style='text-align: center; border: solid 1px black;$bg_css'>".getLimitedLengthString($obrdo->getProperty('obser'), 45)."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('monto')."</td></tr>";
		}
		?>
	</table>
<?php
	echoFeetNotes();
}
?>
