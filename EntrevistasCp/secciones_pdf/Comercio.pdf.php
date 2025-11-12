<?php
$comercio = (empty($r->descripcion)) ? 'Sin Datos' : $r->descripcion;
?>
<h3 style='text-align:center}'>Comercio</h3>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<td style="width: 100%; text-align: left; border: solid 1px black;"><?php echo $comercio; ?></td>
	</tr>
</table>
