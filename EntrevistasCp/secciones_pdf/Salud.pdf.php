<?php
$salud = $sh->getHogarCP()->getSalud();
if (count($salud->getData())>0) 
{
?>
	<h3 style='text-align:center}'>SALUD</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 40%; text-align: center; border: solid 1px black;"><i>Tipo</i></th>
			<th style="width: 40%; text-align: center; border: solid 1px black"><i>Descripcion</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
		</tr>
		<?php
		$bg_css = 'background-color: #FFFFCC;';
		foreach($salud->getData() as $rdo){
				echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('tipo')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('descripcion')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('nombre')."</td></tr>";
				$bg_css = '';
		}
		?>
	</table>
<?php
}
?>
