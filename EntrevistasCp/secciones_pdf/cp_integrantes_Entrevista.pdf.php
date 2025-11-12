<?php


//if (count($sh->getHogarCP()->getData())>0) 
if (count($sh->getHogarCP()->getData())>0) 
   /* echo '<pre>';
    print_r($sh->getHogarCP()->getData());
    echo '</pre>';*/
    //die();
    if (count($sh->getHogarCP()->getData())>0) 
    
{
    

    
?>
	<table cellspacing="5" style="width: 100%;">
		<tr>
			<td><h3 style='text-align:center}'>CONFORMACION DEL HOGAR </h3></td>
	
		</tr>
	</table>	
<table style="width: 100%; border: 1px solid black; border-collapse: collapse;" align="center">
    <tr>
        <th style="border: 1px solid black; text-align: center;">Apellido y Nombre</th>
        <th style="border: 1px solid black; text-align: center;">Nro.Doc.</th>
        <th style="border: 1px solid black; text-align: center;">Cuil.</th>
        <th style="border: 1px solid black; text-align: center;">Fech.Nac.</th>
        <th style="border: 1px solid black; text-align: center;">Edad actual.</th>
        <th style="border: 1px solid black; text-align: center;">Edad Insc.</th>
        <th style="border: 1px solid black; text-align: center;">Sec.</th>
        <th style="border: 1px solid black; text-align: center;">Baja</th>
    </tr>
    <?php foreach ($sh->getHogarCP()->getData() as $dataObject): ?>
    
        <tr>
            <td style="border: 1px solid black;"><?= htmlspecialchars($dataObject->getProperty('apellido') . ' ' . $dataObject->getProperty('nombre')) ?></td>
            <td style="border: 1px solid black; text-align: right;"><?= htmlspecialchars($dataObject->getProperty('nro_doc')) ?></td>
            <td style="border: 1px solid black; text-align: right;"><?= htmlspecialchars($dataObject->getProperty('cuil')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('fecha_nac')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('edadac')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('edadfi')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('secuencia')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('baja')) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
}
  
?>
