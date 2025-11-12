<?php
$certificado = $sh->getHogarCP()->getEstadoCertificadoActual();
if (!$certificado->isEmpty()) 
{

?>
	<h3 style='text-align:left}'>SITUACION ACTUAL CERTIFICADOS EDUCACION (en escolaridad)</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 18%; text-align: left; border: solid 1px black"><i>Apellido y Nombre</i></th>
			<th style="width: 19%; text-align: left; border: solid 1px black"><i>Escuela</i></th>
			<th style="width: 3%; text-align:  left; border: solid 1px black"><i>Grado</i></th>
			<th style="width: 3%; text-align:  left; border: solid 1px black"><i>Curso</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Cert Escol</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Motivo</i></th>
			<th style="width: 17%; text-align: left; border: solid 1px black"><i>Edad Escolar</i></th>
			<th style="width: 8%; text-align: left; border: solid 1px black"><i>F.Carga</i></th>		
			<th style="width: 8%; text-align:  left; border: solid 1px black"><i>Tipo Actualizacion.</i></th>
		</tr>
	<?php
		foreach($certificado->getData() as $falt) {		
		echo '<tr><td style="text-align: left; border: solid 1px black">' . $falt->getProperty('apellido') .','. $falt->getProperty('nombre').'</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('escuela') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('grado') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('curso') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('acredita') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('motivo') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('enesco') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('fcarga') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $falt->getProperty('t_actua') . '</td></tr>';
		}
	?>
	</table>
		<BR>

<?php
}else {
    echo "Sin Datos";
}
?>