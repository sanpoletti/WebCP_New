<?php
$observsegI = $sh->getHogarCP()->getObservacionesseguiI();


if (!$observsegI->isEmpty())  
{ 
	$observacionessegI = $observsegI->getData();
?>
	<h3 style='text-align:center}'>OBSERVACIONES DE INTEGRANNTES DEL HOGAR</h3>
	<table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
		<tr>
			<th style="width: 10%;  text-align: center; border: solid 1px black;"><i>Nro doc.</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 10%;  text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 30%;  text-align: center; border: solid 1px black"><i>Observacion</i></th>
			<th style="width: 20%;  text-align: center; border: solid 1px black"><i>Fecha Carga</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Area</i></th>
		</tr>
		<?php
		foreach($observacionessegI as $obrdo){
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('NRO_DOC')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('APELLIDO')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('NOMBRE')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('DESCRIP')."</td>";
			echo "<td style='text-align: right; border: solid 1px black;$bg_css'>".$obrdo->getProperty('FCARGA')."</td>";
			echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$obrdo->getProperty('USUARIO')."</td></tr>";
		}
		?>
	</table>

	<?php

}else
{
    echo "Sin datos";
}
?>