<?php
if (count($hd->getData()) > 0) 
{
?>
	<h3 style='text-align:center}'> HISTORICO DE DOMICILIOS DE CP</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 15%; text-align: center; border: solid 1px black;"><i>CALLE</i></th>
			<th style="width: 5%; text-align: center;  border: solid 1px black"><i>ALTURA</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>VILLA</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>TIRA</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>PISO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>DTO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>BARRIO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>MANZ</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>HAB</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>CASA</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>TELEF</i></th>
		</tr>
	<?php
	  foreach($hd->getData() as $DOMI) {
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('CALLE') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('numero') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('villa') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('piso') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('departamento') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('barrio') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('manzana') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('Habitacion') . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOMI->getProperty('nro_casa') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOMI->getProperty('') .'</td></tr>';
		}
	?>
	</table>
<?php
}
?>