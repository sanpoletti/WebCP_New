<?php
include("funciones.php");
if ($_SESSION['User'] != ""){ //Aca controlamos si han iniciado sesion, mas adelante voy a explicar el manejo de sesiones, permisos, etc
if (isset($_POST['action']) && $_POST['action'] == "Nuevo")
{
$ArtiCodiLoca = $_POST['ArtiCodiLoca'];
$ArtiNomb = $_POST['ArtiNomb'];
$ArtiPrec = $_POST['ArtiPrec'];
$ArtiImag = $_POST['ArtiImag'];
$ArtiImagLarg1 = $_POST['ArtiImagLarg1'];
$ArtiStock = $_POST['ArtiStock'];
$ArtiMarc = $_POST['ArtiMarc'];
$RubrCodi = $_POST['RubrCodi'];
if ($ArtiCodiLoca == NULL | $ArtiNomb == NULL | $ArtiPrec == NULL | $ArtiStock == NULL | $ArtiMarc == NULL | $RubrCodi == NULL)
{
echo "Debe completar los datos";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
if($ArtiImag == NULL){$ArtiImag='sinfoto';}
if($ArtiImagLarg1 == NULL){$ArtiImagLarg1='sinfotoLarg';}
$link2 = mysql_connect("localhost","root","root");
mysql_select_db("mi_base",$link2);
$query=mysql_query("INSERT INTO articulos(ArtiCodiLoca, ArtiNomb, ArtiDesc, ArtiPrec, ArtiImag, ArtiImagLarg1, ArtiStock, ArtiMarc, RubrCodi) VALUES ('{$_POST['ArtiCodiLoca']}', '{$_POST['ArtiNomb']}', '{$_POST['ArtiDesc']}', '{$_POST['ArtiPrec']}', '$ArtiImag', '$ArtiImagLarg1', '{$_POST['ArtiStock']}', '{$_POST['ArtiMarc']}', '{$_POST['RubrCodi']}')",$link2);
$my_error = mysql_error($link2);

if(!empty($my_error))
{
echo "Ha habido un error al insertar los valores. $my_error";
}
else
{
echo "Los datos han sido introducidos satisfactoriamente";
}
@mysql_close($link2);
}
}
elseif (isset($_POST['actualizar']) && $_POST['actualizar'] == "Actualizar")
{
$ArtiCodiLoca = $_POST['ArtiCodiLoca'];
$ArtiNomb = $_POST['ArtiNomb'];
$ArtiDesc = $_POST['ArtiDesc'];
$ArtiPrec = $_POST['ArtiPrec'];
$ArtiImag = $_POST['ArtiImag'];
$ArtiImagLarg1 = $_POST['ArtiImagLarg1'];
$ArtiStock = $_POST['ArtiStock'];
$ArtiMarc = $_POST['ArtiMarc'];
$RubrCodi = $_POST['RubrCodi'];
if ($ArtiCodiLoca == NULL | $ArtiNomb == NULL | $ArtiPrec == NULL | $ArtiStock == NULL | $ArtiMarc == NULL | $RubrCodi == NULL)
{
echo "Debe completar los datos";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
if($ArtiImag == NULL){$ArtiImag='sinfoto.jpeg';}
if($ArtiImagLarg1 == NULL){$ArtiImagLarg1='sinfotoLarg.jpeg';}
$link2 = mysql_connect("localhost","root","root");
mysql_select_db("mi_base",$link2);
$query=mysql_query("UPDATE articulos SET articulos.ArtiNomb='$ArtiNomb', articulos.ArtiDesc='$ArtiDesc', articulos.ArtiPrec='$ArtiPrec', articulos.ArtiImag='$ArtiImag', articulos.ArtiImagLarg1='$ArtiImagLarg1', articulos.ArtiStock='$ArtiStock', articulos.ArtiMarc='$ArtiMarc', articulos.RubrCodi='$RubrCodi' WHERE ArtiCodiLoca='$ArtiCodiLoca'",$link2);
$my_error = mysql_error($link2);
if(!empty($my_error))
{
echo "Ha habido un error al insertar los valores. $my_error";
}
else
{
echo "Los datos han sido introducidos satisfactoriamente";
}
@mysql_close($link2);
}

}
elseif (isset($_POST['borrar']) && $_POST['borrar'] == "Borrar")
{
$ArtiCodiLoca = $_POST['ArtiCodiLoc'];
if ($ArtiCodiLoca == NULL)
{
echo "Debe completar los datos";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
$mySQL2="SELECT * FROM articulos WHERE ArtiCodiLoca = '".$ArtiCodiLoca."'";
$selected2 = mysql_query($mySQL2);
$numDatos2 = mysql_num_rows($selected2); //Aca controlamos que halla escrito un codigo valido de algun producto
if ($numDatos2 <= 0)
{
echo "Error: Codigo incorrecto.<br>";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
$mySQL="DELETE FROM articulos WHERE ArtiCodiLoca = '".$ArtiCodiLoca."'";
$selected = mysql_query($mySQL);
if (!$selected)
{
echo "Error MySQL: ".mysql_error();
exit;
}
else
{echo 'El registro se borro satisfactoriamente!';}
}
}
}
elseif (isset($_POST['cargar']) && $_POST['cargar'] == "Cargar")
{
$ArtiCodiLoca = $_POST['ArtiCodiLoc'];
if ($ArtiCodiLoca == NULL)
{
echo "Debe completar los datos";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
$mySQL="SELECT * FROM articulos WHERE ArtiCodiLoca = '".$ArtiCodiLoca."'";
$selected = mysql_query($mySQL);
if (!$selected)
{
echo "Error MySQL: ".mysql_error();
exit;
}
else {
$numDatos = mysql_num_rows($selected);
if ($numDatos <= 0)
{
echo "Error: Codigo incorrecto.<br>";
$estapag="abm_producto.php";
volver($estapag);
}
else
{
$row_Arti = mysql_fetch_array( $selected );
rellenarform($row_Arti);
}
}
}
}
else
{
$selectedb="";
$row_Arti = @mysql_fetch_array( $selectedb );
rellenarform($row_Arti);
}
?>
<form method="POST" action="abm_producto.php">
<fieldset><legend style="color: #0033FF;">Ingrese el Código</legend>
<table>
<tr>
<td>
Ingrese el codigo del producto:
</td>
<td>
<input type="text" name="ArtiCodiLoc">
</td>

</tr>
<tr>
<td></td>
<td>
<input type="submit" name="borrar" value="Borrar">
<input type="submit" name="cargar" value="Cargar">
</td>
</tr>
</table>
</fieldset>
</form>
<?php
}
else
{
echo'
<form action="login.php" method="post">
Error: Debe loguearse.<br />
<input type="submit" value="Login" />
</form>
';
}
?>