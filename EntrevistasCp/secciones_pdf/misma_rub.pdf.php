<?php
if ($r->samerub && count($r->hogar) > 0) 
{
?>
	<h3 style='text-align:center}'>Integrantes del hogar que comparten RUB</h3>
	<table style="width: 650;border: solid 1px black; border-collapse: collapse;table-layout:fixed" align="center">
		<tr>
			<th style="width: 25%; text-align: center; border: solid 1px black;"><i>Apellido y Nombre</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Nro. Doc.</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Fecha Nac.</i></th>
			<th style="width: 15%; text-align: center; border: solid 1px black"><i>Parentesco</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Desn.</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Cert.</i></th>
			<th style="width: 30%; text-align: center; border: solid 1px black"><i>Obs.</i></th>
		</tr>
		<?php
		$bg_css = 'background-color: #FFFFCC;';
		foreach($r->hogar as $k){
			if (!$k->es_hogar()) {
				echo "<tr><td style='text-align: left; border: solid 1px black;$bg_css'>".$k->apenom."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$k->nrodoc."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$k->fecha_nac."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$k->paren."</td>";
				echo "<td style='text-align: center; border: solid 1px black;$bg_css'>".$k->desnutrido."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$k->cert."</td>";
				echo "<td style='text-align: left; border: solid 1px black;$bg_css'>".$k->obs."</td></tr>";
				$bg_css = '';
			}
		}
		?>
	</table>
<?php
}
?>

