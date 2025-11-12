<Html>

<Head>

<Body>

<form action="<?php print $PHP_SELF?>" enctype="multipart/form-data" method="post">
   <br /> <input type="file" name="classnotes" value="" /><br />
   <p><input type="submit" name="submit" value="Guardar" /></p>
</form>

<?php

session_start();
session_start();
$var=date('Y-m-d');
$ntitu= $_SESSION['ntitu'];
$idhogar= $_SESSION['idhogar'];
$nrorub= $_SESSION['nrorub'];
$uname= $_SESSION['uname'];
$numeroConsulta  = $_SESSION['numeroConsulta'];

$tipo = $_GET['tipo'];



   

switch ($tipo) {
	case 'e'://entrevistas
	   define ("FILEREPOSITORY","./uploads/entrevista");
	   if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {
	
	      if ($_FILES['classnotes']['type'] != "application/pdf") {
	         echo "<p>Class notes must be uploaded in PDF format.</p>";
	      } else {
	       
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
	

	

	case 'a': //documentacion adjuntada

		$dir_subida = 'dirname(__FILE__) . "/uploads/entrevista/"';
		$fichero_subido = basename($_FILES['fichero_usuario']['name']);
		echo $fichero_subido; die();
		echo '<pre>';
		if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
		    echo "El fichero es válido y se subió con éxito.\n";
		} else {
		    echo "¡Posible ataque de subida de ficheros!\n";
		}
		
		echo 'Más información de depuración:';
		print_r($_FILES);
		
		print "</pre>";
			



	
}
 ?>
 
 
 
