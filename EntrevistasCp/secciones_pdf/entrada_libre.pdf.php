<div class="break">&nbsp;</div>

<?php
	include 'secciones_pdf/header.pdf.php';
?>

<p>&nbsp;</p>

	<P><u><strong>COMPOSICION FAMILIAR</strong></u></P>

	<tr>
		<td style="width: 100%;font-size: 15px; text-align: left; border: solid 1px black;">Mantiene la composicion declarada en el hogar SI-NO:</td>
	</tr>


<p>&nbsp;</p>

<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
	<tr>
		<td style="width: 100%;font-size: 15px; text-align: left; border: solid 1px black;">En caso Negativo, Especificar las modificaciones:</td>
	</tr>
<?php
	for ($ln=0; $ln<25; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>	

<P><u><strong>SITUACION ECONOMICA</strong></u></P>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
<?php
	for ($ln=0; $ln<15; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>


<P><u><strong>SITUACION HABITACIONAL</strong></u></P>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
<?php
	for ($ln=0; $ln<15; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>


<P><u><strong>SITUACION DE SALUD</strong></u></P>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
<?php
	for ($ln=0; $ln<8; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>


<P><u><strong>SITUACION DE EDUCACION</strong></u></P>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
<?php
	for ($ln=0; $ln<8; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>




<P><u><strong>OBSERVACIONES</strong></u></P>
<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
<?php
	for ($ln=0; $ln<15; $ln++) {
		echo '<tr><td  style="border: solid 1px black;font-size: 20px ">&nbsp;</td></tr>';
	}
?>

</table>


<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
	include 'secciones_pdf/footer_firma.pdf.php';
	include 'secciones_pdf/footer.pdf.php';
?>