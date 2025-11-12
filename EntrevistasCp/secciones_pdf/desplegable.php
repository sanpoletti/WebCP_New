<html>
	<head>
		<title>Menu</title>
		<style type="text/css">
			
			* {
				margin:0px;
				padding:0px;
			}
			
			#header {
				margin:auto;
				width:1000px;
				font-family:Arial, Helvetica, sans-serif;
			}
			
			ul, ol {
				list-style:none;
			}
			
			.nav {
				width:1090px; /*Le establecemos un ancho*/
				 /*Centramos automaticamente*/
			}
 
			.nav > li {
				float:right;
			}
			
			.nav li a {
				background-color:#000;
				color:#fff;
				text-decoration:none;
				padding:10px 10px;
				display:block;
			}
			
			.nav li a:hover {
				background-color:#434343;
			}
			
			.nav li ul {
				display:none;
				position:absolute;
				
			}
			
			.nav li:hover > ul {
				display:block;
			}
			
			.nav li ul li {
				position:relative;
			}
			
			.nav li ul li ul {
				left:0px;
				top:0px;
			}
			
		</style>
	</head>
	
	
	<?php 
	
	
	
	
	//parametros donde guardar documentacion
	$entrevista='e';
	$declaracion='d';
	$adjunta='a';
	$tramites='t';
	//$evaluada= $_GET[evaluada];
	$filecmn = $_GET[numeroConsulta]. '.pdf';
	$hora    =   $_GET[hora];
	$numeroConsulta = $_GET[numeroConsulta];
	$evaluada= $_GET[evaluada];
	
	//subimos los pdf a los rutas designadas a continuacion dependiendo el tipo (Entrevistas, Tipo Tramite, Adjuntar documentacion)
	$uploadsEntreURL      = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/upload-manager.php?tipo=' .$entrevista.'&numeroConsulta='.$numeroConsulta.'&hora='.$hora;
	$uploadsDeclaURL      = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/upload-manager.php?tipo=' .$declaracion.'&numeroConsulta='.$numeroConsulta.'&hora='.$hora;
	//$uploadsDocAdjURL     = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/upload-manager.php?tipo=' .$adjunta;
	$uploadsDocMultAdjURL = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/multiplesPdf.php?tipo=' .$adjunta.'&numeroConsulta='.$numeroConsulta.'&hora='.$hora;
	$uploadsDocTraURL     = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/multiplesTramitesPdf.php?tipo=' .$tramites.'&numeroConsulta='.$numeroConsulta.'&hora='.$hora;
	
	
	//$server_file = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/entrevista/';
	//$filenameEntrevista = $server_file . $filecmn;
	
	$server_file_ = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/doc_adeudada/';
	$filenameDocFaltante = $server_file_ . $filecmn;
	
	
	
	$server_file_ = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/listarEntrevista.php?eva=' .$evaluada;
	$filenameEntrevista = $server_file_;
		
	
	$server_file_ = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/listarpdf2.php?numeroConsulta=' .$numeroConsulta;
	;
	$filenameDocAdjuntada = $server_file_;
	
	$server_file_t = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/listarTramitesPdf2.php?numeroConsulta=' .$numeroConsulta;
	$filenameTramites = $server_file_t;
	?>
	
	
	<body>

		<div id="header">
			<nav> <!-- Aqui estamos iniciando la nueva etiqueta nav -->
				<ul class="nav">
					
					<li><a href="">Documentacion</a>
						<ul>
							<li><a href='<?php echo $uploadsEntreURL;  ?>'>Adjuntar Entrevista</a></li>
				<!--		<li><a href='<?php echo $uploadsDeclaURL;  ?>'>Adjuntar DJ Doc. Faltante</a></li> -->
				<!-- 	    <li><a href='<?php echo $uploadsDocAdjURL; ?>'>Adjuntar Documentacion Recibida</a></li>	 -->		
							<li><a href='<?php echo $uploadsDocMultAdjURL; ?>'>Adjuntar Documentacion Recibida</a></li>	
							<li><a href='<?php echo $uploadsDocTraURL; ?>'>Adjuntar Tramite Realizado</a></li>		
						
							</li>
						</ul>
					</li>		
					
					<li><a href="">Ver</a>
						<ul>
							<li><a href='<?php echo $filenameEntrevista;   ?>'target='_blank'>Entrevista escaneada</a></li>
					<!--	<li><a href='<?php echo $filenameDocFaltante;  ?>'target='_blank'>DJ doc Faltante</a></li> -->
							<li><a href='<?php echo $filenameDocAdjuntada; ?>'target='_blank'>Documantacion Adjuntada</a></li>
							<li><a href='<?php echo $filenameTramites;     ?>'target='_blank'>Tramites Adjuntados</a></li>		
							</li>
						</ul>
					</li>			
				</ul>
			</nav><!-- Aqui estamos cerrando la nueva etiqueta nav -->
		</div>

		
	</body>
</html>