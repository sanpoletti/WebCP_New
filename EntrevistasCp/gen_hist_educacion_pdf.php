 <?php

// Protejo que este seteado el NTITUAR
if (!isset($sh)){
	echo "ERROR";
	die();
}

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet" >
<page backtop="35mm" backbottom="10mm">

	<?php
		include_once 'secciones_pdf/common_func.pdf.php';
		include 'secciones_pdf/HistoricoEducacion.pdf.php';
		include 'secciones_pdf/footer.pdf.php';
?>
	
</page>




