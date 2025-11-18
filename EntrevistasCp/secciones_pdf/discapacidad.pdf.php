<?php
$discapacidad = $sh->getHogarCP()->getDiscapacidad();
if (count($discapacidad->getData())>0) 
{
?>
	<h3 style='text-align:left}'>DISCAPACIDAD</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 20%; text-align: left; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black;"><i>Grupo</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black;"><i>Tipo</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black;"><i>Diagnostico</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Fecha Vencimiento</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Estado Certif</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Certificado</i></th>

		</tr>
		<?php
	
		foreach($discapacidad->getData() as $rdo){
	
				echo '<tr><td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('apenom') .'</td>';
				echo '<td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('grupo') . '</td>';
				echo '<td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('tipo') . '</td>';
				echo '<td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('diagnostico') . '</td>';
				echo '<td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('fechavencimiento') . '</td>';
				echo '<td style="text-align: left; border: solid 1px black">' . $rdo->getProperty('estado') . '</td>';
				echo "<td style='text-align: left; border: solid 1px black'>";
				
				$certificado = './DetalleCertificado.php?certificado=' . $rdo->getProperty('idcertificado');
				echo "<a href='$certificado' target='_blank'>Ver.</a>";
				echo "</td></tr>";	
		}
		?>
	</table>
		<BR>

<?php
}else {
    echo "";
}
?>
