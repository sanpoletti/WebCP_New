<div id="post">
	<h2 class="title">SUGERENCIAS</h2>
	<div style="clear: both;">&nbsp;</div>
	<div class="entry">
</div>

<?php

if (isset($_POST['from']) && isset($_POST['mensaje'])) {
	$recipient='secanepa@gmail.com';
	$subject='Informes Entrevista';	
	$msg=$_POST['mensaje'];
	$from=$_POST['from'];

	
	require("phpmailer\phpmailer.inc.php");

	$mail = new phpmailer;
	
// Configuro GMAIL como servidor de correo saliente (SMTP)
	$mail->IsSMTP();
	$mail->Mailer = "smtp";  
	$mail->Host = "ssl://smtp.gmail.com";  
	$mail->Port = 465;  
	$mail->SMTPAuth = true; // turn on SMTP authentication  
	$mail->Username = "sistemasdecp@gmail.com"; // SMTP username  
	$mail->Password = "maildesistemas"; // SMTP password  
	
// Armo Correo electronico
	$mail->From = "sistemasdecp@gmail.com";
	$mail->FromName = $from;
	$mail->AddAddress("secanepa@gmail.com", "Sebastián Cánepa");
	$mail->Subject = "Informes Entrevista";
	$mail->Body = $msg;

// Envio el mail
	$envioOK = $mail->Send(); // send message
	
	
	if($envioOK) {
		echo "<p>Se ha enviado su sugerencia satisfactoriamente.<br/>Muchas Gracias.</p>";
	}
	else {
		echo "<p>Mail Error: " . $mail->ErrorInfo . '</p>';
	}
		
} else {
?>
	<form method="post" action="#">
		<div id="user">De:<input type='text'name='from'/></div>
		<div id="msj">Sugerencia:<textarea name='mensaje' rows ='10' cols ='100'></textarea></div>
		<input type="submit" value="Enviar" /><br/><br/>
	</form>	
<?php
}
?>


