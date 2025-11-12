<?php
$observsegHInt = $hoHInt->getObservacionesseguiHistoricoInt();


if (!$observsegHInt->isEmpty())  
{ 
	$observacionessegH = $observsegHInt->getData();
?>
	<h3 style='text-align:center}'>Historico Observaciones de INTEGRANTES</h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
			<th style="width: 10%;  text-align: center; border: solid 1px black;"><i>Nro doc.</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 10%;  text-align: left; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 30%;  text-align: left; border: solid 1px black"><i>Observacion</i></th>
			<th style="width: 10%;  text-align: center; border: solid 1px black"><i>Fecha Alta</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>	Area Alta</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>	Fecha Eliminacion</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Area Eliminacion</i></th>
		</tr>
		<?php
		$bg_css = "";
		foreach($observacionessegH as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('NRO_DOC')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('APELLIDO')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('NOMBRE')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('DESCRIP')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('FALTA')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('AREAALTA')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('FECHAELIM')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('AREAELIM')."</td></tr>";
		}
		?>
	</table>

	<?php

}
?>