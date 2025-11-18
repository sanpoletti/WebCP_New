<?php 
$hogarRub = $sh->getHogarRub();
//print_r($hogarRub); die;

$clasiRub = $hogarRub->getClasiRub();	
$domiRub  = $hogarRub->getDomicilioRub();
$anioRub = '---';
$relRub  = '---';

if (is_object($domiRub)) {
    $unique = $domiRub->getUniqueData();
    if (is_object($unique)) {
        $propAnio = $unique->getProperty('anio');
        $propRel  = $unique->getProperty('rel');
        
        if ($propAnio !== null && $propAnio !== '') $anioRub = $propAnio;
        if ($propRel  !== null && $propRel  !== '') $relRub  = $propRel;
    }
}
if ( ! $clasiRub->isEmpty() || ! $domiRub->isEmpty() )
{	
?>	
<table cellspacing="5" style="width: 100%;">
	<tr>
	<td style="width: 10%; text-align: left;"><h3 style='width: 100%; text-align:center}'>DATOS DEL RUB</h3></td>
	<td style="width: 20%; text-align: left; vertical-align: bottom;"><u>Calificacion:</u><i><?php echo $clasiRub->getUniqueData()->getProperty('clasirub'); ?></i></td>
	<td style="width: 20%; text-align: left; vertical-align: bottom;"><u>Nro de Rub:</u><i><?php echo $hogarRub->getNRub(); ?></i></td>
	<td style="width: 20%; text-align: left; vertical-align: bottom;"><u>Codigo:</u><i><?php echo $clasiRub->getUniqueData()->getProperty('codigo'); ?></i></td>
	<td style="width: 15%; text-align: left; vertical-align: bottom;"><u>Año Rub:</u><i><?php echo $anioRub; ?></i></td>
	<td style="width: 15%; text-align: right; vertical-align: bottom;"><u>Relev:</u><i><?php  echo $relRub ; ?></i></td>
	</tr>
</table>

<?php
} else {
   // echo "Sin Datos";
}
?>
