<?php
$cambios = $sh->getHogarCP()->getCambiostitular();
if (count($cambios->getData()) > 0) 
{
	$cambio = $cambios->getUniqueData();
	$titucam='./hogar.php?idhogar='. $sh->getHogarCP()->getIdHogar() . '&ntitular=' . $sh->getHogarCP()->getNTitular();
?>
<h3 style='text-align:left}'>CAMBIOS DE TITULARIDAD <a href='<?php echo $titucam; ?>' target='_blank'>(Ver Hogar anterior)</a></h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 20%; text-align: left; border: solid 1px black"><i>Hogar Anterior</i></th>
			<th style="width: 20%; text-align: left; border: solid 1px black"><i>Fecha de cambio</i></th>
			
		</tr>
	<?php
		$bg_css = 'background-color: #FFFFCC;';
		echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$cambio->getProperty('nrotitular')."</td>";
		echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$cambio->getProperty('fecha')."</td></tr>";			
	?>
	</table>
		<BR>

<?php
}else {
    echo "Sin Datos";
}
?>