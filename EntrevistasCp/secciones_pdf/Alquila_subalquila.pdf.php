<?php
$alquila_subalquila = (empty($r->alquila_subalquila)) ? 'Sin Datos' : $r->alquila_subalquila;
?>
<h3 style='text-align:center}'>alquila</h3>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<td style="width: 100%; text-align: left; border: solid 1px black;"><?php echo $alquila_subalquila; ?></td>
	</tr>
</table>
