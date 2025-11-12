<?php
$categoria = $sh->getHogarCP()->getTitularProperty('CATEGORIA');
$resolucion = $sh->getHogarCP()->getTitularProperty('RESOLUCION');
$calif = $sh->getHogarCP()->getTitularProperty('calificacion');
$tipoinscrip = $sh->getHogarCP()->getTitularProperty('tipoinscrip');
$fchainscrip = $sh->getHogarCP()->getTitularProperty('fechainscrip');
$codigocalifi = $sh->getHogarCP()->getTitularProperty('codigocalifi');


if ( !empty($categoria) || !empty($resolucion) || !empty($calif) || !empty($tinscrip) || !empty($fchainscrip) || !empty($codigocalifi) ) 
{
?>
	<table cellspacing="5" style="width: 100%;">
			<tr>
			
				<td><u><font size="18" color="red"> Categoría:</font></u><i><b> <?php echo $categoria ?></b></i></td>
				<td><u><font size="18" color="red">Resolución:</font></u><i>;<?php echo $resolucion ?></i></td>
				<td><u><font size="4" color="red">Fecha Inscrip:</font></u><i><?php echo $fchainscrip ?></i></td>

			</tr>
			<tr>
			<!--<td style="text-align: left;	width: 25%"><u>Calificación:</u><i><?php echo $calif ?></i></td>
				<td style="text-align: left;	width: 25%"><u>Código:</u><i><?php echo $codigocalifi ?></i></td>-->
			</tr>
	</table>
<?php
}
?>