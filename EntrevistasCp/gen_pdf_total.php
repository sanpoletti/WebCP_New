<?php

// Protejo que este seteado el NTITUAR
if (!isset($sh)){
	echo "ERROR";
	die();
}

include_once 'seguimientoHogar.class.php';

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet" >
<page backtop="35mm" backbottom="10mm">

	<?php
		include_once 'secciones_pdf/common_func.pdf.php';
		include 'secciones_pdf/header.pdf.php';
		include 'secciones_pdf/resolucion.pdf.php';
		include 'secciones_pdf/cp_integrantes.pdf.php';
		include 'secciones_pdf/eet_inscriptos.pdf.php';
		include 'secciones_pdf/pagosCP.pdf.php';
		include 'secciones_pdf/observ_hogar.pdf.php';
		include 'secciones_pdf/turnos.pdf.php';
		include 'secciones_pdf/Educacion.pdf.php';
		include 'secciones_pdf/salud.pdf.php';
		//include 'secciones_pdf/Doc_faltante.pdf.php';
		include 'secciones_pdf/domicilios.pdf.php';
	//	include 'secciones_pdf/domicilios_historicos.pdf.php';
		include 'secciones_pdf/Tarjetas_programas.pdf.php';
		include 'secciones_pdf/historicoPagos.class.php';
		include 'secciones_pdf/historicodomi.pdf.php';
		include 'secciones_pdf/footer.pdf.php';
?>
	
</page>




