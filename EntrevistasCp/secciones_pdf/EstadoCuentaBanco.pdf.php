<?php
$estadoCuentas = $sh->getHogarCP()->getEstadoCuenta();

if (count($estadoCuentas->getData()) > 0) 
{

?>
	<h3 style='text-align:left}'>ESTADO CUENTA BANCARIA</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Origen</i></th>
			<th style="width: 5%;  text-align: left; border: solid 1px black"><i>Nrodoc</i></th>
			<th style="width: 15%; text-align: left; border: solid 1px black"><i>Apellido y nombre</i></th>
			<th style="width: 8%; text-align:  left; border: solid 1px black"><i>Estado</i></th>
			<th style="width: 8%; text-align:  left; border: solid 1px black"><i>Hist Cuenta</i></th>

			
			
			
			
		</tr>
	<?php
		foreach($estadoCuentas->getData() as $tarj) {
			echo '<tr><td style="text-align: left; border: solid 1px black">' . $tarj->getProperty('programa') .'</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $tarj->getProperty('docu') . '</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . $tarj->getProperty('ape_y_nom') . '</td>';
			echo '<td style="text-align: left; border: solid 1px black">' . 'EN PROCESO DE EXPORTACION' . '</td>';
			echo "<td style='text-align: left; border: solid 1px black'>";
			$ntitu = $tarj->getProperty('ntitular');
			if (isset($ntitu) ) {
				$pagos = './hist_cuentas_cp.php?ntitu=' . $_GET[ntitu];
				echo "<a href='$pagos' target='_blank'>Ver</a>";
			}
			else {
			echo "---";
			}
			echo "</td></tr>";
			
			
		}
	?>
	</table>
		<BR>

<?php
}else {
    echo "Sin Datos";
}
?>