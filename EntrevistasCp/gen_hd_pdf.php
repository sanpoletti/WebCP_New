<?php

// Protejo que este seteado el NTITUAR
if (!isset($hd)){
	echo "ERROR";
	die();
}


include_once 'entrevistas.class.php'

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet" >
<page backtop="35mm" backbottom="10mm">

<?php
		include_once 'secciones_pdf/common_func.pdf.php';
		include 'secciones_pdf/historicoDomi.pdf.php';
		//include 'secciones_pdf/footer.pdf.php';
?>	
</page>