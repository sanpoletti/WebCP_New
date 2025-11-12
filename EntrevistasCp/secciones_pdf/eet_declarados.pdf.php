<?php
$declarados_eet = $sh->getHogarCP()->getDeclaradosEET();

if (count($declarados_eet->getData()) > 0) 
{

?>
	<h3 style='text-align:center}'>DECLARADOS EET</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Nro doc</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Sueldo.</i></th>
		</tr>
	<?php
		foreach($declarados_eet->getData() as $DECLA) {
			echo '<tr><td style="text-align: left; border: solid 1px black">' . $DECLA->getProperty('nro_doc') .'</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $DECLA->getProperty('apellido') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $DECLA->getProperty('nombre') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $DECLA->getProperty('sueldo') . '</td></tr>';
		}
	?>
	</table>
<?php
}
?>