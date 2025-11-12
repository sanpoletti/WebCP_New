<?php
$adeuda = $sh->getHogarCP()->getAdeuda();
/*
echo "<pre>";
print_r($adeuda->getData());
echo "</pre>";
if (!$adeuda->isEmpty()) 
*/
{

?>
	<h3 style='text-align:center}'>EDUCACION</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Apellido</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Adeuda Certif.Educ</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>No Apo/Esco</i></th>
			<th style="width: 40%; text-align: center; border: solid 1px black"><i>Tipo Actualizacion.</i></th>
		</tr>
	<?php
	foreach($adeuda->getData() as $falt) {
	    // Mostramos todos los que NO acreditan
	    if ($falt->getProperty('acredita') == 'NO ACREDITA') {
	        echo '<tr><td style="text-align: left; border: solid 1px black">' . $falt->getProperty('apellido') .'</td>';
	        echo '<td style="text-align: left; border: solid 1px black">' . $falt->getProperty('nombre') .'</td>';
	        echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('acredita') . '</td>';
	        echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('enesco') . '</td>';
	        echo '<td style="text-align: center; border: solid 1px black">' . $falt->getProperty('t_actua') . '</td></tr>';
	    }
	}
	
	?>
	</table>
<?php
}{
    echo "";
}
?>