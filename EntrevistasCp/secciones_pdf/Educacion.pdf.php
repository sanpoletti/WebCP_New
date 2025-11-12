<?php
$educacion = $sh->getHogarCP()->getAdeuda();
if (count($educacion->getData())>0) 
{
?>
	<h3 style='text-align:center}'>EDUCACION</h3>
	<table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
		<tr>
			<th style="width: 10%; text-align: center; border: solid 1px black;"><i>Apellido</i></th>
			<th style="width: 10%; text-align: center; border: solid 1px black"><i>Nombre</i></th>
			<th style="width: 30%; text-align: center; border: solid 1px black"><i>Establecimiento</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Grado</i></th>
			<th style="width: 5%; text-align: center; border:  solid 1px black"><i>Division</i></th>
			<th style="width: 5%; text-align: center; border: solid 1px black"><i>Fecha Cert</i></th>
			<th style="width: 15%; text-align: center; border:  solid 1px black"><i>Certificado</i></th>
			<th style="width: 5%; text-align: center; border:  solid 1px black"><i>cuota</i></th>
			<th style="width: 5%; text-align: center; border:  solid 1px black"><i>Privado</i></th>
			<th style="width: 10%; text-align: center; border:  solid 1px black"><i>Tipo Actualiz.</i></th>
		</tr>
		<?php
		
		foreach($educacion->getData() as $rdo){
				echo "<tr><td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('APELLIDO')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('NOMBRE')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('ENTIDAD')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('GRADO')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('DIVISION')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('FECHAALTA')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('CERTIFICADO')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('MONTO')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('PRIVADO')."</td>";
				echo "<td style='text-align: left; border: solid 1px black;'>".$rdo->getProperty('ACTUALIZA')."</td></tr>";
			
		}
		?>
	</table>
<?php
}else{
    echo "Sin datos";
}
?>
