<?php
if (count($hp->getData()) > 0) 
{
?>
	<h3 style='text-align:left}'> HISTORICO DE RESOLUCIONES DE PAGOS CP  </h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 7%; text-align: left; border: solid 1px black;"><i>Mes</i></th>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Monto</i></th>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Retro</i></th>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Deposito</i></th>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Detalle Deposito</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Categoria</i></th>
			<th style="width: 26%; text-align: left; border: solid 1px black"><i>Resolucion</i></th>
			<th style="width: 33%; text-align: left; border: solid 1px black"><i>Motivo Calculo Monto</i></th>
			<th style="width: 16%; text-align: left; border: solid 1px black"><i>Califica Hogar</i></th>
			<th style="width: 20%; text-align: left; border: solid 1px black"><i>Hist.Estado Acreditacion Escolar</i></th>
		</tr>
	
	<form method='post' action='./gen_excel.php'>
	<?php
		foreach($hp->getData() as $pago) {
			echo '<td style="text-align: ; border: solid 1px black">' . $pago->getProperty('mespago') . '</td>';
			echo '<td style="text-align: right; border: solid 1px black">' . getMoneyString($pago->getProperty('monto')) . '</td>';
			echo '<td style="text-align: right; border: solid 1px black">' . getMoneyString($pago->getProperty('retro')) . '</td>';
			echo '<td style="text-align: right; border: solid 1px black">' . getMoneyString($pago->getProperty('deposito')) . '</td>';
			
			
			echo "<td style='text-align: left; border: solid 1px black'>";
				
			$histoDetalleLiq = './hist_detalle_liquidacion.php?idhogar=' . $pago->getProperty('idhogar'). '&idcatego=' . $pago->getProperty('idcatego').'&mes=' . $pago->getProperty('mespago');
			
			if($pago->getProperty('idcatego')>20160401)
			{
				echo "<a href='$histoDetalleLiq' target='_blank'>Ver.</a>";
			}
			echo '</td>';
				
			echo '<td style="text-align: LEFT; border: solid 1px black">' . $pago->getProperty('categoria') . '</td>';
			echo '<td style="text-align: LEFT; border: solid 1px black">' . $pago->getProperty('resolucion') . '</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('motivo') .'</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('calificacion') .'</td>';
			
			
			echo "<td style='text-align: left; border: solid 1px black'>";
			
			$histoAdeudaDoc = './hist_adeuda_educacion.php?idhogar=' . $pago->getProperty('idhogar'). '&idcatego=' . $pago->getProperty('idcatego').'&mes=' . $pago->getProperty('mespago');
				
			if($pago->getProperty('idcatego')>20160401)
			{
				echo "<a href='$histoAdeudaDoc' target='_blank'>Ver.</a>";
			}
			echo "</td></tr>";
		
		
		
		
		
		}
	?>
	</table>
	<div>
		<input type='submit' value='EXCEL' />
	</div>
	</form>
<?php
}
?>