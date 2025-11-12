<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
		$(function(){

			$('#abmFormUsuario').submit(function(){
				$('#submitButton').attr("disabled", "disabled");
			});
			
		});
	</script>

<Html>
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
			<td><h3 style="text-align:left;color: red;">Adjuntar Entrevista Escaneada</h3></td>
			<td><h4 style="text-align:left;color: black;">"Recordar Que siempre debe haber una Situacion de entrevista cargada"</h3></td>
		
			
		</div>
<form id="abmFormUsuario" action="<?php print $PHP_SELF?>" enctype="multipart/form-data" method="post">
   <br /> <input type="file" name="classnotes" value="" /><br />
   <p><input type="submit" name="submit" id="submitButton" value="Guardar" /></p>
</form>

<?php


$name = $_GET[numeroConsulta];
$hora = $_GET['hora'];

session_start();
$var    = date('Y-m-d');
$ntitu  = $_SESSION['ntitu'];
$idhogar= $_SESSION['idhogar'];
$nrorub = $_SESSION['nrorub'];
$uname  = $_SESSION['uname'];


$numeroConsulta  = $name;

$tipo = $_GET['tipo'];


include_once 'entrevistas.class.php';
   

switch ($tipo) {
	case 'e'://entrevistas
	    
	    //fijarnos si ya tiene una entrevista.
	    
	   include 'busca_Entrevista.php';
	   $type="Entrevista"; 
	   define ("FILEREPOSITORY","./uploads/entrevista");
	   if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {
	      if ($_FILES['classnotes']['type'] != "application/pdf") {
	         echo "<p>Formato incorrecto, debe ser PDF.</p>";
	      } else {	       
	         $result = move_uploaded_file($_FILES['classnotes']['tmp_name'], FILEREPOSITORY."/$name.pdf");
	         include_once 'entrevistas.class.php';
	         $documentacionEntrevista  = new AbmEntrevista(0, $name,$type, $uname);
	         
	         
	         
	         if ($result == 1) {
	         	
	         	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	           	header('Location: http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/generar.php?numeroConsulta=' .$numeroConsulta.
	         	"&idpersonahogar=$idpersonahogar&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub&hora=$hora");
	         	exit;

	         }else{ 
	       //  echo "<p>There was a problem uploading the file.</p>";
	         }
	      } #endIF
	   } #endIF
	
	case 'a': //documentacion adjuntada
		define ("FILEREPOSITORY","./uploads/doc_adjuntada/");
		if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {
			if ($_FILES['classnotes']['type'] != "application/pdf") {
				echo "<p>Formato incorrecto, debe ser PDF.</p>";
			} else {
		    	
				
				$result = move_uploaded_file($_FILES['classnotes']['tmp_name'],
				FILEREPOSITORY.$numeroConsulta.'-'.$_FILES['classnotes']['name']);
				include_once 'entrevistas.class.php';
				$documentacion  = new AbmDocumentacionEntrevista(0, $name, $_FILES['classnotes']['name'], $uname);
				if ($result == 1) {
						
					//guardo datos del usuario que registro la documentacion

				echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
					header('Location: http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/generar.php?numeroConsulta=' .$numeroConsulta.
					"&idpersonahogar=$idpersonahogar&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub&hora=$hora");
					exit;				 
			}else{
					echo "<p>There was a problem uploading the file.</p>";
			} #endIF
			}
		} #endIF	
		
		case 't': //documentacion adjuntada
		
		
			define ("FILEREPOSITORY","./uploads/tramites/");
		
			if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {
				if ($_FILES['classnotes']['type'] != "application/pdf") {
					echo "<p>Formato incorrecto, debe ser PDF.</p>";
				} else {
					//$name = $_POST[$name];
		
					//$result = move_uploaded_file($_FILES['classnotes']['tmp_name'], FILEREPOSITORY."/$name.pdf");
		
					$result = move_uploaded_file($_FILES['classnotes']['tmp_name'],
					FILEREPOSITORY.$numeroConsulta.'-'.$_FILES['classnotes']['name']);
					include_once 'entrevistas.class.php';
					$documentacion  = new AbmDocumentacionEntrevista(0, $name, $_FILES['classnotes']['name'], $uname);
					if ($result == 1) {
		
						//guardo datos del usuario que registro la documentacion
		
						echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
						header('Location: http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/generar.php?numeroConsulta=' .$numeroConsulta.
						"&idpersonahogar=$idpersonahogar&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub&hora=$hora");
						exit;
					}else{
						echo "<p>There was a problem uploading the file.</p>";
					} #endIF
				}
			} #endIF
		
		case 'd': //documentacion declaracion jurada
		    
		    define ("FILEREPOSITORY","./uploads/doc_adeudada");
		    if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {
		        
		        if ($_FILES['classnotes']['type'] != "application/pdf") {
		            echo "<p>Class notes must be uploaded in PDF format.</p>";
		        } else {
		            //$name = $_POST['name'];
		            $result = move_uploaded_file($_FILES['classnotes']['tmp_name'], FILEREPOSITORY."/$name.pdf");
		            if ($result == 1) {
		                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		                header('Location: http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/generar.php?numeroConsulta=' .$numeroConsulta.
		                    "&idpersonahogar=$idpersonahogar&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub");
		                exit;
		                
		            }else{
		                echo "<p>There was a problem uploading the file.</p>";
		            }
		        } #endIF
		    } #endIF
		
		
		
		
}
 ?>
 
 
 
