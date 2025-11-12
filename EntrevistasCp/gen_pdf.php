<?php

// Protejo que este seteado el NTITUAR
if (!isset($sh)){
	echo "ERROR";
	die();
}




include_once 'entrevistas.class.php';

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet" >
<page backtop="35mm" backbottom="10mm">

	<?php
	
		include_once 'secciones_pdf/common_func.pdf.php';
		
		include 'secciones_pdf/header.pdf.php';
		/*
		include 'secciones_pdf/desplegable.php';
		*/
		include 'secciones_pdf/resolucion.pdf.php';
		
		include 'secciones_pdf/cp_integrantes.pdf.php';
		include 'secciones_pdf/pagosCP.pdf.php';
		include 'secciones_pdf/domicilios.pdf.php';
		include 'secciones_pdf/rub.pdf.php';
		include 'secciones_pdf/ingresos.pdf.php';
		include 'secciones_pdf/observ_hogar.pdf.php';
		include 'secciones_pdf/observa_seguimiento.pdf.php';
		include 'secciones_pdf/observa_seguimientoInt.pdf.php';
		include 'secciones_pdf/eet_inscriptos.pdf.php';
		include 'secciones_pdf/turnos.pdf.php';
		include 'secciones_pdf/adeuda_doc.pdf.php';
		
		include 'secciones_pdf/SituacionEntrevista.pdf.php';
		
		include 'secciones_pdf/footer_firma.pdf.php';
		
		
?>
	
</page>




