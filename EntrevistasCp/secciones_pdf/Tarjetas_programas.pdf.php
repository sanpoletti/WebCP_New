<?php
$tarjetas = $sh->getHogarCP()->getTarjetas();

if (count($tarjetas->getData()) > 0) 
{

?>
	<h3 style='text-align:center}'>DATOS DE LA TARJETA</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Origen</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nrodoc</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Nro tarjeta</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Nro Cuenta</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Retirada</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Habilitada.</i></th>
		</tr>
	<?php
		foreach($tarjetas->getData() as $tarj) {
			echo '<tr><td style="text-align: left; border: solid 1px black">' . $tarj->getProperty('origen') .'</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $tarj->getProperty('nro_doc') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('apellido') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('nombre') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('nrotarjeta') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('numerodecuenta') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('fechaentrega') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $tarj->getProperty('habilitada') . '</td></tr>';
		}
	?>
	</table>
<?php
}else {
    echo "Sin datos";
}
?>