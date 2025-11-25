<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta charset="utf-8">

<?php
// PRIMERO: Cargar config.php para tener APP_ROOT

require_once __DIR__ . '/../config/path.php';

// AHORA SÍ puedo usar APP_ROOT
require_once APP_ROOT . '/login/phpUserClass/access.class.php';

$user = new flexibleAccess();
if (!$user->tienePermiso('seguimiento')) {
    header('Location: /login/index.php');
    exit;
}

// Ruta absoluta para los PDF
$pdfPath = APP_ROOT . '/EntrevistasCp/secciones_pdf/';
          

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

    $('.button').click(function(){
        var clickBtnValue = $(this).val();
        $.post('ajax.php', {action: clickBtnValue}, function (response) {
            alert("action performed successfully");
        });
    });

    $('a.switchVisible').click(function(ev){
        ev.preventDefault();
        var seccion = $(this).data('seccion');
        var divSelector = $('#' + seccion + 'Div');
        if (!divSelector.hasClass('ajaxloaded')) {			
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

// Validación del parámetro $sh
if (!isset($sh)) {
    echo "ERROR";
    die();
}

$InicioCarga = date("H-i-s");

// Cargar clases
//require_once APP_ROOT . '/login/phpUserClass/access.class.php';
//include_once 'entrevistas.class.php';
require_once APP_ROOT . '/EntrevistasCp/entrevistas.class.php';
?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet">

<page backtop="35mm" backbottom="10mm">

<?php

// INCLUDES CON RUTA ABSOLUTA
include $pdfPath . 'common_func.pdf.php';
include $pdfPath . 'header.pdf.php';
include $pdfPath . 'resolucion.pdf.php';
include $pdfPath . 'retiro_tarjeta.pdf.php';
include $pdfPath . 'amparo.pdf.php';
include $pdfPath . 'Resol_rub.pdf.php';
include $pdfPath . 'cp_integrantes.pdf.php';
include $pdfPath . 'eet_inscriptos.pdf.php';
include $pdfPath . 'PlanCuidarEmbarazo_inscriptos.pdf.php';
include $pdfPath . 'adeuda_doc.pdf.php';
include $pdfPath . 'estadoCertificadoActual.pdf.php';
include $pdfPath . 'observ_hogar.pdf.php';
include $pdfPath . 'observa_seguimiento.pdf.php';
include $pdfPath . 'observa_seguimientoInt.pdf.php';
include $pdfPath . 'turnos.pdf.php';
include $pdfPath . 'discapacidad.pdf.php';
include $pdfPath . 'domicilios.pdf.php';

if ($user->tienePermiso('entrevistas') || $user->tienePermiso('revision')) {
    include $pdfPath . 'SituacionEntrevista.pdf.php';
}

$FinCarga = date("H-i-s");

function SegundosDiferencia($horaini, $horafin) {
    $ini = (substr($horaini,0,2)*3600)+(substr($horaini,3,2)*60)+substr($horaini,6,2);
    $fin = (substr($horafin,0,2)*3600)+(substr($horafin,3,2)*60)+substr($horafin,6,2);
    return $fin - $ini;
}

echo "<br>";

?>
<p></p>




