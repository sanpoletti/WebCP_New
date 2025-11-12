<?php
$inmueble = (empty($r->inmueble)) ? 'Sin Datos' : $r->inmueble;
?>
<h3 style='text-align:center}'> Otro inmueble de algun miembro del hogar</h3>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<td style="width: 100%; text-align: left; border: solid 1px black;"><?php echo $inmueble; ?></td>
	</tr>
</table>
