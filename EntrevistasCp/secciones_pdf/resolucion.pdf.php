<?php
header('Content-Type: text/html; charset=UTF-8');
$categoria = $sh->getHogarCP()->getTitularProperty('CATEGORIA');
$resolucion = $sh->getHogarCP()->getTitularProperty('RESOLUCION');
$calif = $sh->getHogarCP()->getTitularProperty('calificacion');
$tipoinscrip = $sh->getHogarCP()->getTitularProperty('tipoinscrip');
$fchainscrip = $sh->getHogarCP()->getTitularProperty('fechainscrip');
$codigocalifi = $sh->getHogarCP()->getTitularProperty('codigocalifi');


if ( !empty($categoria) || !empty($resolucion) || !empty($calif) || !empty($tinscrip) || !empty($fchainscrip) || !empty($codigocalifi) ) 
{
?>
	<table  style="width: 100%;">
			<tr>
			
				<td class="resolucion">Categoria :<?php echo $categoria ?></td>
				<td class="resolucion">Resolucion:<?php echo $resolucion ?></td>
				<td class="resolucion">Fecha Inscr:<?php echo $fchainscrip ?></td>

			</tr>
			<tr>
			<!--<td style="text-align: left;	width: 25%"><u>Calificaci�n:</u><i><?php echo $calif ?></i></td>
				<td style="text-align: left;	width: 25%"><u>C�digo:</u><i><?php echo $codigocalifi ?></i></td>-->
			</tr>
	</table>
<?php
}
?>