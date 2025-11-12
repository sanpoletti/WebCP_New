<!DOCTYPE html>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
		$(function(){

			$('#abmFormUsuario').submit(function(){
				$('#submitButton').attr("disabled", "disabled");
			});
			
		});
	</script>
<head>
    <meta charset="utf-8">
    <title>Subir una o varias imagenes al servidor</title>
</head>
 	<table style="width: 100%;">
			<tr>
				<td style="align:left;width: 60%"><img src="./img/logo.gif" alt=""/></td>
				
				
			</tr>
		</table>
<hr>		
<title>Subir Documentacion</title>
<Head>

<Body>
		<div id="logo">
			<td><h3 style="text-align:left;color: red;">Adjuntar documentacion Escaneada (Seleccione uno o multiples Pdf.)</h3></td>
			<td><h4 style="text-align:left;color: black;">"Recordar Que siempre debe haber una Situacion de entrevista cargada"</h3></td>
</div>
<br>
    <form id="abmFormUsuario"  action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" name="inscripcion">
        <input type="file" name="archivo[]" multiple="multiple">
        <input type="submit" value="Enviar"  class="trig">
    </form>
<body>
    <?php
    
    include 'funciones.php';
    session_start();
    $numeroConsulta  = $_SESSION['numeroConsulta'];
    $name= $numeroConsulta;
    session_start();
    $var=date('Y-m-d');
    $ntitu= $_SESSION['ntitu'];
    $idhogar= $_SESSION['idhogar'];
    $nrorub= $_SESSION['nrorub'];
    $uname= $_SESSION['uname'];
    $tipo = $_GET['tipo'];
    $hora = $_GET['hora'];
    
    //$fechaCarga= date_format($date, 'd/m/Y H:i:s');
   
    $fechaCarga=date('d/m/Y');
    
   // switch ($tipo) {
  //  	case 'a'://documentacion Adjuntada

    # definimos la carpeta destino
    $carpetaDestino="uploads/doc_adjuntada/";
 
    # si hay algun archivo que subir
    
    $archivo_permitido1 = "image/jpeg";  // tipo 1 de archivo permitido
    $archivo_permitido2 = "image/gif";       // tipo 2 de archivo permitido
    $archivo_permitido3 = "application/octet-stream";       // tipo 3 de archivo permitido, .zip
    $archivo_permitido4 = "application/pdf";
    
    
    
    
    if(isset($_FILES["archivo"]) && $_FILES["archivo"]["name"][0])
    {
 
        # recorremos todos los arhivos que se han subido
        for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
        {
 
            # si es un formato de imagen
            //if($_FILES["archivo"]["type"][$i]=="image/pdf" || $_FILES["archivo"]["type"][$i]=="image/jpg" || $_FILES["archivo"]["type"][$i]=="image/gif" || $_FILES["archivo"]["type"][$i]=="image/png")
            if ($_FILES["archivo"]["type"][$i]== "$archivo_permitido4" or $_FILES["archivo"]["type"][$i]== "$archivo_permitido1" ) 
            {
 
                # si exsite la carpeta o se ha creado
                if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
                {
                    $origen=$_FILES["archivo"]["tmp_name"][$i];
                    $destino=$carpetaDestino.$numeroConsulta.'-'.limpia_espacios($_FILES["archivo"]["name"][$i]);
                    $nombrePdf=$numeroConsulta.'-'.limpia_espacios($_FILES["archivo"]["name"][$i].'-'.$fechaCarga);                    
                    
                    # movemos el archivo
                    if(@move_uploaded_file($origen, $destino))
                    {
                        
                        include_once 'entrevistas.class.php';
                        $documentacion  = new AbmDocumentacionEntrevista(0, $name, $nombrePdf, $uname);
                        if ($result == 1) {
                            
                            //guardo datos del usuario que registro la documentacion
                            
                            echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
                            header('Location: http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/generar.php?numeroConsulta=' .$numeroConsulta.
                                "&idpersonahogar=$idpersonahogar&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub&hora=$hora");
                            exit;
                        }else{
                           // echo "<p>There was a problem uploading the file.</p>";
                        } #endIF
                            
                    }else{
                        echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo"]["name"][$i];
                    }
                
                   
                }else{
                    echo "<br>No se ha podido crear la carpeta: ".$carpetaDestino;
                }
                //include_once 'PostlistarPdf2.php';
                //echo "<script type=\"text/javascript\">alert(\"Adjuntado con Exito\");</script>";
            }else{
                echo "<br>".$_FILES["archivo"]["name"][$i]." - NO es imagen pdf";
            }
         
        }
    }else{
        echo "<br>"."<br>";
        "<br>";
    }
    include_once 'PostlistarPdf2.php';
    
    //include_once "PostlistarPdf2.php?hora='$hora'";

    ?>


</body>
</html>
