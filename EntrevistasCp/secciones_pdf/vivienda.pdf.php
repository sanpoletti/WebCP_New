<?php

$descrip_vivienda = (empty($r->descrip_vivienda)) ? 'Sin Datos' : $r->descrip_vivienda;
?>
<h3 style='text-align:center}'>Vivienda</h3>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<th style="width:100%; text-align: center; border: solid 1px black"><i>Tipo Vivienda</i></th>
	</tr>
	<tr>
		<td style="text-align: left; border: solid 1px black;"><?php echo $descrip_vivienda; ?></td>
	</tr>
</table>
