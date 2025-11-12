<?php
$observaseg = $sh->getHogarCP()->getObservacionesseguimiento();


if (!$observaseg->isEmpty())  
{ 
	$observa_seguimiento = $observaseg->getData();
?>
	<h3 style='text-align:center}'>OBSERVACIONES HOGAR</h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
			<th style="width: 20%;   text-align: center; border: solid 1px black;"><i>Apellido.</i></th>
			<th style="width: 15%;  text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 30%;  text-align: center; border: solid 1px black"><i>Observacion</i></th>
			<th style="width: 10%;  text-align: center; border: solid 1px black"><i>Fecha carga</i></th>
			<th style="width: 25%;  text-align: center; border: solid 1px black"><i>Usuario</i></th>
		</tr>
		<?php
		foreach($observa_seguimiento as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('APELLIDO')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('NOMBRE')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('DESCRIP')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('FCARGA')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'> ".$obrdo->getProperty('USUARIO')."</td></tr>";
		}
		?>
	</table>

	<?php
	echoFeetNotes();
}
?>