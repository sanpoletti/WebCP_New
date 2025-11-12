<?php
if (count($detalleIngresos->getData()) > 0) 
{
?>
	<h3 style='text-align:left}'> DETALLE INGRESOS LABORALES</h3>
	<table style="width: 100%;border: solid 2px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: left;  border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 80%; text-align: left; border: solid 1px black"><i>Monto</i></th>
		</tr>
	<?php
	  foreach($detalleIngresos->getData() as $DOC) {
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOC->getProperty('apellido') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('nombre') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('laboral') .'</td></tr>';
	 }
	?>
	</table>
	
	
	<h3 style='text-align:left}'> DETALLE INGRESOS NO LABORALES</h3>
	<table style="width: 100%;border: solid 2px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: left;  border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 7%; text-align: left; border: solid 1px black"><i>Jubilacion y/o pension</i></th>
			<th style="width: 7%; text-align: left; border: solid 1px black"><i>Cuotas de alimentos</i></th>
			<th style="width: 8%; text-align: left; border: solid 1px black"><i>Aportes de personas que no viven en el hogar</i></th>
			<th style="width: 8%; text-align: left; border: solid 1px black"><i>Becas de estudio</i></th>
			
			
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>AUH</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>AUH-Embar.</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>AUH-Discap.</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>UAF</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>Ticket</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>CP</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>Sub.Hab</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>Otro Subsidio</i></th>
			
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Sub. estatales</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Sub. No estatales</i></th>
			<th style="width: 7%; text-align: left; border: solid 1px black"><i>Alquileres, rentas o intereses</i></th>
			<th style="width: 8%; text-align: left; border: solid 1px black"><i>Seguro de desempleo</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Indemnizacion por despido o accidente</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Utilidades, beneficios o dividendos</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Otros ingresos..</i></th>
			
			.
			
			
		</tr>
	<?php
	  foreach($detalleIngresos->getData() as $DOC) {
		echo '<td style="text-align: LEFT; border: solid 1px black">' . $DOC->getProperty('apellido') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('nombre') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('jubilatorios') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('alimentos') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('aportesNoVivenHogar') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('becas') .'</td>';
		
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('auh_monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('AUH_Embarazo_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('AUH_Discapacidad_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('UAF_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('TicketSocial_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('CiudaniaPortena_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('SubsidioHabitacional_Monto') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('I6b_OtroSubsidio_Especificar') .'</td>';
		
		
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('subsidiosEsta') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('subsidiosNoEsta') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('alquileres') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('seguroDesempleo') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('indemnizacion') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('utilidades') .'</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $DOC->getProperty('prestamos') .'</td></tr>';
		
		
	 }
	?>
	</table>
	

<?php
}
?>