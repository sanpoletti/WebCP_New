<<script type="text/javascript">
location.reload();
</script>


<?php

session_start();
$uname= $_SESSION['uname'];

$url = $_SERVER['HTTP_REFERER'];
$ar= $_GET["archivo"];
$registro=substr($ar, 0, 6);
$path =dirname(__FILE__) . "/uploads/entrevista/" .$ar;
if (file_exists($path)){	

		if (unlink(dirname(__FILE__) . "/uploads/entrevista/" .$ar)) {
	
		    include_once 'entrevistas.class.php';

		    $deleteEntrevista  = new DeleteEntrevista($registro, $uname);
		       
			header("LOCATION:$url");
		} else {
			echo "fail";
		}
} else {
echo "file does not exsist";
}
?>

