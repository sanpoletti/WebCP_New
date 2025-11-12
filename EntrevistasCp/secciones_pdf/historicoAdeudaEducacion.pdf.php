<?php
if (count($hAdeudaEducacion->getData()) > 0) 
{
?>
	<h3 style='text-align:left}'> ESTADO SITUACION AL CIERRE DEL PAGO DEL MES: <?php echo  $_GET['mes'] ?></h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 13%; text-align: left; border: solid 1px black"><i>Apellido y Nombre</i></th>
			<th style="width: 19%; text-align: left; border: solid 1px black"><i>Escuela</i></th>
			<th style="width: 3%; text-align: left; border: solid 1px black"><i>Grado</i></th>
			<th style="width: 3%; text-align: left; border: solid 1px black"><i>Curso</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Cert Escol</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Motivo</i></th>
			<th style="width: 17%; text-align: left; border: solid 1px black"><i>Edad Escolar</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Tipo Actualizacion.</i></th>
		</tr>
	<?php
	  foreach($hAdeudaEducacion->getData() as $DOC) {

        $mes= $DOC->getProperty('estadosituacion');
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('apellido') .','. $DOC->getProperty('nombre').'</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('escuela') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('grado') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('curso') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('acredita') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('motivo') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('enesco') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('t_actua') . '</td></tr>';
	 }
	?>
	</table>
<?php
}
?>