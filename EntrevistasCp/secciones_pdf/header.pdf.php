<?php 
//session_start();



$hora = isset($_SESSION['hora']) ? "Entrevista: ".$_SESSION['hora'] : '';
?>


<page_header>
		<table style="width: 100%;">

		</table>
		
		<table style="width: 100%; border: solid 1px black;">
			<tr>
				<?php
				$fr_width = 100;
				$left_content = '';
				$right_content = '';
				$nTitular = $sh->getHogarCP()->getNTitular();
				if (! empty($nTitular) ) {
					$left_content = "<td style='text-align: left;	width: 33%'><i>Nro. Titular:</i><b>" . $nTitular .'</b></td>';
					$fr_width -= 33;
				} 
				$tInsc = $sh->getHogarCP()->getTitularProperty('tipoinscrip');
				//$tInsc = 'INSCRIPCION_DE_PRUEBA';
				//var_dump($tInsc);
				if (! empty($tInsc) ) {
					$right_content = "<td style='text-align: right;	width: 33%'><i>Inscripcion:</i><b>" . $tInsc .'</b></td>';
					$fr_width -= 33;
				}
				
				echo $left_content;
				echo "<td style='text-align: center;	width: $fr_width%'><b></b></td>";
				echo $right_content;
				?>
			</tr>
		</table>
</page_header>