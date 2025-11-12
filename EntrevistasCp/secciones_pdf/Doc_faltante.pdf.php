<?php
$doc_faltante = $sh->getHogarCP()->getFaltante();

if (count($doc_faltante->getData()) > 0) 
{

?>
	<h3 style='text-align:center}'>Documentacion Faltante</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Desnutrido</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Cert_desnu</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Embarazo</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Cert embara</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Discapacidad</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Certi discapa.</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Cuil.</i></th>
		</tr>
	<?php
		foreach($doc_faltante->getData() as $falt) {
			echo '<tr><td style="text-align: left; border: solid 1px black">' . $falt->getProperty('APELLIDO') .'</td>';
			echo '<td style="text-align: left; border: solid 1px black">'  . $falt->getProperty('NOMBRE') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('DESNUTRIDO') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('CERT_DESNUT') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('EMBARAZO') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('CERT_EMBAR') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('DISCAPACIDAD') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('CERT_DISCAP') . '</td>';
			echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('CUIL') . '</td></tr>';
		}
	?>
	</table>
<?php
}
?>