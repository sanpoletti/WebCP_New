<?php
if (count($sh->getInscripcionEET()->getData())>0) 
{
?>
	<h3 style='text-align:center}'>INSCRIPCIONES EN EET</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 40%; text-align: center; border: solid 1px black;"><i>Apellido</i></th>
			<th style="width: 40%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 20%; text-align: center; border: solid 1px black"><i>Categoria</i></th>
		</tr>
		<?php
		$bg_css = '';
		foreach($sh->getInscripcionEET()->getData() as $rdo){
				echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('apellido')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('nombre')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$rdo->getProperty('categoria')."</td></tr>";
				$bg_css = '';
		}
		?>
	</table>
<?php
}else 
{
    echo "";
}
?>
