<<script type="text/javascript">
location.reload();
</script>


<?php
$url = $_SERVER['HTTP_REFERER'];
$ar= $_GET["archivo"];
$path =dirname(__FILE__) . "/uploads/doc_adjuntada/" .$ar;
if (file_exists($path)){	
		if (unlink(dirname(__FILE__) . "/uploads/doc_adjuntada/" .$ar)) {
			header("LOCATION:$url");
		} else {
			echo "fail";
		}
} else {
echo "file does not exsist";
}
?>

