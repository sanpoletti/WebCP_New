
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
			var divSelector = '#' + seccion + 'Div';
			$(divSelector).toggle();
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



include_once 'entrevistas.class.php';

?>
<link type="text/css" href="secciones_pdf/pdf.css" rel="stylesheet">
<page backtop="35mm" backbottom="10mm">

	<?php
	
		include_once 'secciones_pdf/common_func.pdf.php';
		include 'secciones_pdf/header.pdf.php';
		include 'secciones_pdf/desplegable.php';
		include 'secciones_pdf/resolucion.pdf.php';
		include 'secciones_pdf/cp_integrantes.pdf.php';
		
		//pagos
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='pagos'>Ver Pagos</a>"."<BR>";
		echo "<div id='pagosDiv' style='display:none'>";
		include 'secciones_pdf/pagosCP.pdf.php';
		echo "</div>";
		
		
		
		//include 'secciones_pdf/domicilios.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='domicilio'>Ver Domicilio</a>"."<BR>";
		echo "<div id='domicilioDiv' style='display:none'>";
		include 'secciones_pdf/domicilios.pdf.php';
		echo "</div>";
		
		//include 'secciones_pdf/rub.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='rub'>Ver Rub</a>"."<BR>";
		echo "<div id='rubDiv' style='display:none'>";
		include 'secciones_pdf/rub.pdf.php';
		echo "</div>";

		
		
		
		//include 'secciones_pdf/ingresos.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='ingresos'>Ver Ingresos</a>"."<BR>";
		echo "<div id='ingresosDiv' style='display:none'>";
		include 'secciones_pdf/ingresos.pdf.php';
		echo "</div>";
		
			
		//include 'secciones_pdf/observ_hogar.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='obserHogar'>Observaciones Sintys</a>"."<BR>";
		echo "<div id='obserHogarDiv' style='display:none'>";
		include 'secciones_pdf/observ_hogar.pdf.php';
		echo "</div>";
		
	
		
		//include 'secciones_pdf/observa_seguimiento.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='observaTitu'>Observaciones Titular</a>"."<BR>";
		echo "<div id='observaTituDiv' style='display:none'>";
		include 'secciones_pdf/observa_seguimiento.pdf.php';
		echo "</div>";
		
		
		
		//include 'secciones_pdf/observa_seguimientoInt.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='observaInte'>Observaciones Integrantes</a>"."<BR>";
		echo "<div id='observaInteDiv' style='display:none'>";
		include 'secciones_pdf/observa_seguimientoInt.pdf.php';
		echo "</div>";
		
		
		
		
		//include 'secciones_pdf/eet_inscriptos.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='inscpEet'>Ver Inscripcion EET</a>"."<BR>";
		echo "<div id='inscpEetDiv' style='display:none'>";
		include 'secciones_pdf/eet_inscriptos.pdf.php';
		echo "</div>";
		
				
		//Turnos
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='turnos'>Ver Turnos</a>"."<BR>";
		echo "<div id='turnosDiv' style='display:none'>";
		include 'secciones_pdf/turnos.pdf.php';
		echo "</div>";
		
		
		//include 'secciones_pdf/adeuda_doc.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='educ'>Ver Educacion</a>"."<BR>";
		echo "<div id='educDiv' style='display:none'>";
		include 'secciones_pdf/adeuda_doc.pdf.php';
		echo "</div>";		
		
		//include 'secciones_pdf/SituacionEntrevista.pdf.php';
		echo "<BR>"."<a href='#' class='switchVisible' data-seccion='entre'>Ver Situacion entrevista</a>"."<BR>";
		echo "<div id='entreDiv' style='display:none'>";
		include 'secciones_pdf/SituacionEntrevista.pdf.php';
		echo "</div>";
		
		
		
		//include 'secciones_pdf/EvaluacionEntrevista.pdf.php';
		

		
		
		
		
		include 'secciones_pdf/footer_firma.pdf.php';
?>



<p></p>





