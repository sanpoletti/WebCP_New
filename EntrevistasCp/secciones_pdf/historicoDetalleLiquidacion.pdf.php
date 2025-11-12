<?php
if (count($hHistoricoDetalleLiquidacion->getData()) > 0) 
{
?>
	<h3 style='text-align:left}'> DETALLE DE LIQUIDACION DE PAGO: <?php echo  $_GET['mes'] ?></h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 13%; text-align: left; border: solid 1px black"><i>Apellido y Nombre</i></th>
			<th style="width: 19%; text-align: left; border: solid 1px black"><i>Monto</i></th>
			<th style="width: 19%; text-align: left; border: solid 1px black"><i>Acredita</i></th>
			<th style="width: 3%; text-align: left; border: solid 1px black"><i>enesco</i></th>
			<th style="width: 3%; text-align: left; border: solid 1px black"><i>Edad</i></th>
			</tr>
			
	<?php
	  foreach($hHistoricoDetalleLiquidacion->getData() as $DOC) {

        $mes= $DOC->getProperty('mes');
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('apenom').'</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('montoindividual') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('acredita') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('enesco') . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">'   . $DOC->getProperty('edad') . '</td></tr>';
	 }
	?>
	</table>
<?php
}
?>