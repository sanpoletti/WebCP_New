<?php
$ultimo_pago = $sh->getHogarCP()->getUltimopago();
if (count($ultimo_pago->getdata())>0) 
{
?>
	<h3 style='text-align:center}'>OBSERVACIONES DEL PAGO</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 30%; text-align: center; border: solid 1px black"><i>Excluido segun RUB</i></th>
			<th style="width: 40%; text-align: center; border: solid 1px black"><i>Resuelto</i></th>
		</tr>
	<?php
		foreach($ultimo_pago->getdata() as $pago) {
			echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$pago->getProperty('descripcion')."</td>";
			echo "<td style ='text-align: center; border: solid 1px black'>".$pago->getProperty('resuelto')."</td></tr>";
		}
	?>
	</table>
<?php
}
?>