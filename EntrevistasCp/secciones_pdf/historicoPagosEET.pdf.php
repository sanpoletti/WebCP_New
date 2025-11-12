<?php
if (count($hp->getData()) > 0) 
{
?>
	<h3 style='text-align:center}'> HISTORICO DE PAGOS DE EET</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 15%; text-align: center; border: solid 1px black;"><i>FECHA</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>PAGO</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>DEPOSITO</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>CATEGORIA</i></th>
		</tr>
	<?php
	  foreach($hp->getData() as $pago) {
		echo '<td style="text-align: LEFT; border: solid 1px black">' . getDateString($pago->getProperty('fecha')) . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . getMoneyString($pago->getProperty('pago')) . '</td>';
		echo '<td style="text-align: LEFT; border: solid 1px black">' . getMoneyString($pago->getProperty('deposito')) . '</td>';
		echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('categoria') .'</td></tr>';
		}
	?>
	</table>
<?php
}
?>