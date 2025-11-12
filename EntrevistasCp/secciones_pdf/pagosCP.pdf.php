<?php
/**
* -------------------------------------------------------------------------------------
* Verifico si el usuario tiene permisos
*/
require_once(__DIR__ . "/../../login/phpUserClass/access.class.php");

$user = new flexibleAccess();
//if ( ! $user->tienePermiso('pagos') ) {
//	return 1;	// No muestro pagos
//}
/**
* -------------------------------------------------------------------------------------
*/

$pagos = $sh->getHogarCP()->getPagos();
if (count($pagos->getData()) > 0) 
{
	
	
?>
	<h3 style='text-align:left}'>PAGOS</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 18%; text-align: left; border: solid 1px black"><i>Apellido y Nombre</i></th>
			<th style="width: 4%; text-align: left; border: solid 1px black"><i>Origen</i></th>
			<th style="width: 10%; text-align: left; border: solid 1px black"><i>Fecha</i></th>	
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Monto</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Retro</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Deposito</i></th>
			<th style="width: 35%; text-align: left; border: solid 1px black"><i>Motivo Calculo Monto</i></th>
			<th style="width: 40%; text-align: left; border: solid 1px black"><i>Calificacion Hogar</i></th>
			<th style="width: 5%; text-align: left; border: solid 1px black"><i>Hist.Pagos</i></th>
		</tr>
	<?php
		foreach($pagos->getData() as $pago) {
			 echo '<tr><td style="text-align: left; border: solid 1px black">' . getApeNom($pago) .'</a></td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('ORIGEN') . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('MESPAGO') . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . getMoneyString($pago->getProperty('MONTO')) . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('RETRO') . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . getMoneyString($pago->getProperty('DEPOSITO')) . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('MOTIVO') . '</td>';
			 echo '<td style="text-align: left; border: solid 1px black">' . $pago->getProperty('calificacion') . '</td>';
		// Muestro link a historico de pagos si tiene pagos
			echo "<td style='text-align: left; border: solid 1px black'>";
			$sec = $pago->getProperty('SEC');
			$idpersona = $pago->getProperty('idpersonaHogar');
			if (isset($idpersona) ) {
				$pagos = './hist_pagos.php?NroDoc=' . ($_GET['NroDoc'] ?? '') . '&ntitu=' . $_GET['ntitu'] . '&sec=' . $pago->getProperty('SEC') . '&org=' . $pago->getProperty('ORIGEN'). '&idpersona=' . $pago->getProperty('IDPERSONAHOGAR');
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
}else{
    echo "Sin datos";
}
?>