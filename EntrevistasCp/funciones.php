<?php
function volver($pagina)
{
echo'
<form method="POST" action="'.$pagina.'">
<input type="submit" value="Volver" name="volver" />
</form>
';
}
function limpia_espacios($cadena){
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
}

function rellenarform($row_Arti)
{
echo '<form method="POST" action="abm_producto.php" enctype="multipart/form-data">';
echo '
<fieldset><legend style="color: #0033FF;">Ingrese los datos</legend>
<table cellspacing="5" cellpading="4">
<tr>
<td><input type="hidden" name="ArtiCodiLoca" value="'.$row_Arti['ArtiCodiLoca'].'" size="20" maxlength="100" /></td>
</tr>
<tr>
<td><span class="abm">ArtiNomb(150)</span></td>
<td><input type="text" name="ArtiNomb" value="'.$row_Arti['ArtiNomb'].'" size="20" maxlength="150" /></td>
</tr>
<tr>
<td><span class="abm">Descripcion(1000)</span></td>

<td>
<textarea cols=50 rows=8 name="ArtiDesc">'.$row_Arti['ArtiDesc'].'</textarea>
</td>

</tr>
<tr>
<td><span class="abm">Precio</span></td>
<td><input type="text" name="ArtiPrec" value="'.$row_Arti['ArtiPrec'].'" size="20" maxlength="250" /></td>
</tr>
<tr>
<td><span class="abm">Imagen Pequeña</span></td>
<td><input type="text" name="ArtiImag" value="'.$row_Arti['ArtiImag'].'" size="20" maxlength="250" /></td>
</tr>
<tr>
<td><span class="abm">Imagen Grande 1</span></td>
<td><input type="text" name="ArtiImagLarg1" value="'.$row_Arti['ArtiImagLarg1'].'" size="20" maxlength="250" /></td>
</tr>
<tr>
<td><span class="abm">Stock</span></td>
<td><input type="text" name="ArtiStock" value="'.$row_Arti['ArtiStock'].'" size="4" maxlength="4" /></td>
</tr>
<tr>
<td><span class="abm">Marca</span></td>
<td><input type="text" name="ArtiMarc" value="'.$row_Arti['ArtiMarc'].'" size="20" maxlength="30" /></td>
</tr>
<tr>
<td><span class="abm">Codigo del Rubro</span></td>
<td><input type="text" name="RubrCodi" value="'.$row_Arti['RubrCodi'].'" size="20" maxlength="250" /></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="actualizar" value="Actualizar" /></td>
</tr>

</table>
</fieldset>
</form>';
}
?> 