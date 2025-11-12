<?php
/**
* ------------------------------------------------------------------------------<	<	-------
* Verifico si el usuario tiene permisos
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if ( ! $user->tienePermiso('entrevistas') ) {
// El usuario no esta registrado o no tiene permisos
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/login/index.php');
}
/**
* -------------------------------------------------------------------------------------
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>REVISION DE CASOS</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="turno_table.css" rel="stylesheet" type="text/css" media="screen" />
<script type='text/JavaScript' src='js/jacs.js'></script>
<script type='text/JavaScript' src='js/jacsLanguages.js'></script>
<script type='text/JavaScript' src='js/jacsDemo.js'></script>

<script type='text/javascript'>
	function searchBy(divName) {
		document.getElementById('fechaDiv').style.visibility = 'hidden';
		document.getElementById('ndocDiv').style.visibility = 'hidden';
		document.getElementById('ntituDiv').style.visibility = 'hidden';
		
		document.getElementById(divName).style.visibility = '';
	}
	
	function ChangeColor(tableRow, highLight) {
		if (highLight) {
		  tableRow.style.backgroundColor = '#dcfac9';
		} else {
		  tableRow.style.backgroundColor = 'white';
		}
	}

	function DoNav(theUrl) {
		//document.location.href = theUrl;	//Open on the same window
		window.open(theUrl);				// Open on new window (popup)
	}

</script>

<?php
	$ntituStyle = '"visibility: hidden;"';
	$docStyle = '"visibility: hidden;"';
	$fechaStyle = '"visibility: hidden;"';
	if (!empty($_GET['ntitular'])) {
		$ntituChecked =  ' checked';
		$ntituStyle = '""';
	} else if (!empty($_GET['nrodoc'])) {
		$ndocChecked = ' checked';
		$docStyle = '""';
	} else {
		$fechaChecked = ' checked';
		$fechaStyle = '""';
	}
	
	if ($_GET['tipo'] == "inte" ) {
		$inteChecked =  ' checked';	
	}else if ($_GET['tipo'] == "lega" ) {
		$legaChecked =  ' checked';
	}else if ($_GET['tipo'] == "inteSalguero" ) {
			$inteSalgueroChecked =  ' checked';
	} else {
		$entreChecked =  ' checked';
	}
	
?>

</head>
<body bgcolor="#c9f8ff"">
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><a href="#">Revision de Casos</a></h1>
			
		</div>
		<div id="search">
			<form method="get" action=".">
				<fieldset>
					<div>
						<input type="radio" name="search_by" id="search-radio" value="fecha" onClick="searchBy('fechaDiv');" <?php echo $fechaChecked; ?> >Fecha</input>
						<input type="radio" name="search_by" id="search-radio" value="ntitu" onClick="searchBy('ntituDiv');" <?php echo $ntituChecked;?> >Ntitular</input>
						<input type="radio" name="search_by" id="search-radio" value="ndoc" onClick="searchBy('ndocDiv');" <?php echo $ndocChecked; ?> >Documento</input>
					</div>
					<div id="fechaDiv" style=<?php echo $fechaStyle; ?> class="searchByDiv">
						Fecha:
						<input type="text" name="fecha" id="search-text" size="10" value="" />
						<img src='images/jacs.gif' title='Seleccionar Fecha' alt='Seleccionar Fecha' onclick="JACS.show(document.getElementById('search-text'),event);";/>
					</div>
					<div id="ndocDiv" style=<?php echo $docStyle; ?> class="searchByDiv">
						Nro doc:
						<input type="text" name="nrodoc" id="search-text" size="10" value="" />
					</div>
					<div id="ntituDiv" style=<?php echo $ntituStyle; ?> class="searchByDiv">
						Ntitular:
						<input type="text" name="ntitular" id="search-text" size="10" value="" />
					</div> 
					
					<div>
						<hr/>
						
						<input  type="radio" name="tipo" id="search-radio" value="entre" <?php echo $entreChecked; ?> >Entrevistas</input><br>
						<input  type="radio" name="tipo" id="search-radio" value="inte"  <?php echo $inteChecked;?> >Baja Integrantes Curapa</input>	<br>
						<input  type="radio" name="tipo" id="search-radio" value="inteSalguero"  <?php echo $inteSalgueroChecked;?> >Baja Integrantes Salguero</input><br>	
						<input  type="radio" name="tipo" id="search-radio" value="lega"  <?php echo $legaChecked;?> >Legales</input><br>
					
					</div>
					
					
					<input type="submit" id="search-submit" value="Buscar" /><br/><br/>
				</fieldset>
			</form>		
		</div>
	</div>
	<!-- end #header -->
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="menu">

				</div>
				<div id="post">
					&nbsp;
				</div>
				
				<?php
				// Incluyo PHP segun opcion elegida x menu
				if($_GET['option']=='sugerencias'){
					include 'sugerencias.php';
				}else{
					include 'RevisionEntrevistas.php'; 
				}	
				?>
				<!-- end #menu -->
				<div id="content">
					
					<div style="clear: both;">&nbsp;</div>
				</div>
				<!-- end #content -->

				<!-- end #sidebar -->
				<div style="clear: both;">&nbsp;</div>
			</div>
		</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer-wrapper">
	<div id="footer">
		<p>Copyright (c) 2018 Ciudadania Porteña. All rights reserved. Design by <a href="http://www.buenosaires.gob.ar/">GCBA</a>.</p>
	</div>
</div>
<!-- end #footer -->
</body>
</html>
