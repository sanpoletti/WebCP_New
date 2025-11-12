<?php
$server_file = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/entrevista/';
$filecmn = $_GET[numeroConsulta]. '.pdf';
$filename = $server_file . $filecmn;
echo $filename;

//if ( file_exists ( $filename ) ) {
	
?>
	<h3 style='text-right'> <a href='http://172.17.58.121/desarrollo_informes_entrevistas/uploads/entrevista/<?php echo $filecmn; ?>' target='_blank'>Ver Escaneo</a></h3>
<?php
//}else {
//	//echo "no imprime";
//}
?>