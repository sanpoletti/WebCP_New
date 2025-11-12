<?php
require 'conUser.php';
$result = getAllFuncionario();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Amb Form - Usuarios</title>
<script type="text/javascript" src="js.js">
</script>
</head>
<body>

<div align="center" id="formAbmUsuario">
<form action="conUser.php" method="post" id="abmFormUsuario" name="abmFormUsuario" >
<input type="hidden" name="1" value="1">
	<table border="1" >
		<thead>
			<tr>
     			<th colspan="2">CRUD - Usuarios</th>
  			</tr>
		</thead>
		<tr>
			<th>Nombre:</th><th><input type="text" id="nombre" name="nombre" tabindex="1"></th>
		</tr>
		<tr>
			<th>Apellido:</th><th><input type="text" id="apellido" name="apellido" tabindex="2"></th>
		</tr>
		<tr>
			<th>Usuario:</th><th><input type="text" id="usuario" name="usuario" tabindex="3"></th>
		</tr>
		<tr>
			<th>Password:</th><th><input type="password" id="passw" name="passw" tabindex="4"></th>
		</tr>
		<tr>
			<th colspan="2"><input type="submit" value="Save" ></th>
		</tr>
	</table>
</form>
</div>


<br><br>
<div id="abmUsuario" align="center">

<table border="1" >
		<thead>
			<tr>
     			<th>Id</th><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Password</th><th>Editar</th><th>Borrar</th>
  			</tr>
		</thead>
		<?php 
			while ($fila = mysql_fetch_array($result, MYSQL_NUM)) {
			//printf("Nombre: %s  Apellido: %s", $fila[0], $fila[1]); 	
			?>
		<tr>
			<td id="id"><?php echo $fila[0]; ?></td>
			<td><?php echo $fila[1]; ?></td>
			<td><?php echo $fila[2]; ?></td>
			<td><?php echo $fila[3]; ?></td>
			<td><?php echo $fila[4]; ?></td>
			<td align="center">
				<a href="FormEditUsuario.php?id=<?php echo $fila[0];?>&v2=3">
					<img alt="" src="images/editar.png" width="15" height="15">
				</a>
			</td>
			<td align="center">
				<a href="conUser.php?id=<?php echo $fila[0];?>&v2=2"> 
					<img alt="" src="images/delete.png" width="15" height="15">
				</a>
			</td>
		</tr>
		<?php } ?>
</table>

</div>
</body>
</html>