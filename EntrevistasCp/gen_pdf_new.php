<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta charset="utf-8">
<?php 
//require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
require_once __DIR__ . '/../login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if (  !$user->tienePermiso('seguimiento') ) {
// El usuario no esta registrado o no tiene permisos
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/login/index.php');
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("button").click(function(){

            $.ajax({
                type: 'POST',
                url: 'pagosCp.pdf.php',
                success: function(data) {
                    alert(data);
                    $("p").text(data);

                }
            });
   });
});


    $(document).ready(function(){
        $('.button').click(function(){
            var clickBtnValue = $(this).val();
            var ajaxurl = 'ajax.php',
            data =  {'action': clickBtnValue};
            $.post(ajaxurl, data, function (response) {
                // Response div goes here.
                alert("action performed successfully");
            });
        });

        $('a.switchVisible').click(function(ev){
            ev.preventDefault();
			var seccion  = $(this).data('seccion');
			var divSelector = $('#' + seccion + 'Div');
			if (divSelector.hasClass('ajaxloaded') == false ) {			
				var pdf = $(this).data('pdf');
				var params = window.location.search + '&include=' + pdf;
				divSelector.load('generar_ajax.php' + params);
				divSelector.addClass('ajaxloaded');
			}		
			divSelector.toggle();
            return false;
        });

    });


    
</script>

<?php

// Protejo que este seteado el NTITUAR
if (!isset($sh)){
	echo "ERROR";
	die();
}
$InicioCarga=date("H-i-s");
//require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
require_once __DIR__ . '/../login/phpUserClass/access.class.php';
$user = new flexibleAccess();

include_once 'entrevistas.class.php';

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet">
<page backtop="35mm" backbottom="10mm">

	<?php
	
		include_once 'secciones_pdf/common_func.pdf.php';
		include 'secciones_pdf/header.pdf.php';
		include 'secciones_pdf/resolucion.pdf.php';	
		include 'secciones_pdf/retiro_tarjeta.pdf.php';
		include 'secciones_pdf/amparo.pdf.php';	
		include 'secciones_pdf/Resol_rub.pdf.php';	
		include 'secciones_pdf/cp_integrantes.pdf.php';
		include 'secciones_pdf/eet_inscriptos.pdf.php';
		include 'secciones_pdf/PlanCuidarEmbarazo_inscriptos.pdf.php';
		
		include 'secciones_pdf/pagosCP.pdf.php';
		include 'secciones_pdf/adeuda_doc.pdf.php';
		include 'secciones_pdf/estadoCertificadoActual.pdf.php';
		
		
		include 'secciones_pdf/observ_hogar.pdf.php';
		include 'secciones_pdf/observa_seguimiento.pdf.php';
		include 'secciones_pdf/observa_seguimientoInt.pdf.php';
		


		include 'secciones_pdf/turnos.pdf.php';
		include 'secciones_pdf/discapacidad.pdf.php';
		include 'secciones_pdf/domicilios.pdf.php';	
		if (  $user->tienePermiso('entrevistas')  || $user->tienePermiso('revision')) {
		 include 'secciones_pdf/SituacionEntrevista.pdf.php';
		}
//		if (  $user->tienePermiso('revision') ) {
//		  include 'secciones_pdf/EvaluacionEntrevista.pdf.php';
//		}
		$FinCarga=date("H-i-s");
		$resultado=SegundosDiferencia($InicioCarga,$FinCarga);
		echo "<BR>";	
		function SegundosDiferencia($horaini,$horafin)
		{
			$horai=substr($horaini,0,2);
			$mini=substr($horaini,3,2);
			$segi=substr($horaini,6,2);
		
			$horaf=substr($horafin,0,2);
			$minf=substr($horafin,3,2);
			$segf=substr($horafin,6,2);
		
			$ini=((($horai*60)*60)+($mini*60)+$segi);
			$fin=((($horaf*60)*60)+($minf*60)+$segf);
		
			$dif=$fin-$ini;
		
			return $dif;
				}
?>
<p></p>





