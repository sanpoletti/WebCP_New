<?php
$vivienda2   = $sh->getHogarRub()->getVivienda2()->getUniqueData();
$vigilancia  = $vivienda2->getProperty('vigilancia');
$cochera     = $vivienda2->getProperty('cochera');
$portero     = $vivienda2->getProperty('porteroVisor');
$otros       = $vivienda2->getProperty('otros');

?>


<h3 style='text-align:center}'>La vivienda posee:</h3>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<th style="width: 33%; text-align: center; border: solid 1px black;"><i></i></th>
		<th style="width: 33%; text-align: center; border: solid 1px black"><i></i></th>
		<th style="width: 33%; text-align: center; border: solid 1px black"><i></i></th>
	</tr>	
	
	<tr>
		<td style="text-align: left; border: solid 1px black;"><?php echo $vigilancia; ?></td>
		<td style="text-align: left; border: solid 1px black;"><?php echo $portero; ?></td>
		<td style="text-align: left; border: solid 1px black;"><?php echo $otros; ?></td>
	</tr>
	

</table>


