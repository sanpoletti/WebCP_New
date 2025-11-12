<?php


//if (count($sh->getHogarCP()->getData())>0) 
if (count($sh->getHogarCP()->getData())>0) 
   /* echo '<pre>';
    print_r($sh->getHogarCP()->getData());
    echo '</pre>';*/
    //die();
    if (count($sh->getHogarCP()->getData())>0) 
    
{
    

    
    
    $fichaRubURL = 'http://' . $_SERVER['SERVER_NAME'] . '/EntrevistasCp/generar_rub.php?NroDoc=' . $_GET['NroDoc']
    . '&ntitu=' . $_GET['ntitu']
    . '&rubs=' . ($_GET['nrorub'] ?? '');?>
   
    
	<table cellspacing="5" style="width: 100%;">
		<tr>
			<td><h3 style='text-align:center}'>CONFORMACION DEL HOGAR    <a href='<?php echo $fichaRubURL; ?>' target='_blank'>(Ver Ficha RUB)</a></h3></td>
	
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
        <th style="border: 1px solid black; text-align: center;">Parentesco</th>
        <th style="border: 1px solid black; text-align: center;">Presenta DNI</th>
        <th style="border: 1px solid black; text-align: center;">Presenta Cert.tutela</th>
        <th style="border: 1px solid black; text-align: center;">Presenta Part.Nac</th>
        <th style="border: 1px solid black; text-align: center;">Presenta DesviConyugal</th>
        <th style="border: 1px solid black; text-align: center;">Discapacitado</th>
        <th style="border: 1px solid black; text-align: center;">Presenta Cert. Discapa</th>
        <th style="border: 1px solid black; text-align: center;">Duplicado</th>
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
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('parentesco')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('copidni')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('tutela')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('partida')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('separado')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('disca')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('discafecha')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('dupli')) ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= htmlspecialchars($dataObject->getProperty('baja')) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
}
  
?>
