<?php

 echo "<pre>";

 
// Protejo que este seteado el NTITUAR
if (!isset($sh)){
	echo "ERROR: No esta seteado el objeto principal de datos";
	die();
}


?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet" >
<page backtop="35mm" backbottom="10mm">

	<?php
		include_once 'secciones_pdf/common_func.pdf.php';
		//include 'secciones_pdf/header.pdf.php';
		include 'secciones_pdf/cp_integrantes.pdf.php';
		include 'secciones_pdf/rub.pdf.php';
		include 'secciones_pdf/indices.pdf.php';
		include 'secciones_pdf/domicilios.pdf.php';	
		//}
	//  Datos de RUB
	
	   
		include 'secciones_pdf/ingresos.pdf.php';
		include 'secciones_pdf/vivienda.pdf.php';
		include 'secciones_pdf/alquila_subalquila.pdf.php';
		include 'secciones_pdf/Comercio.pdf.php';
		include 'secciones_pdf/Propietario.pdf.php';
		include 'secciones_pdf/Salud.pdf.php';
		include 'secciones_pdf/bienes.pdf.php';
		include 'secciones_pdf/bienes_obs.pdf.php';
		include 'secciones_pdf/otros_bienes.pdf.php';
		include 'secciones_pdf/vivienda2.pdf.php';
		
		
		//	include 'secciones_pdf/observ_hogar.pdf.php';
		
		include 'secciones_pdf/observ_rub.pdf.php';
		//include 'secciones_pdf/misma_rub.pdf.php';
		include 'secciones_pdf/footer.pdf.php';
	?>
	
</page>


