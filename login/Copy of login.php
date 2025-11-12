<?php
require_once 'phpUserClass/access.class.php';



$user = new flexibleAccess();
if ( isset($_GET['logout']) && $_GET['logout'] == 1 ) 
	$user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
if ( !$user->is_loaded() )
{
	//Login stuff:
	if ( isset($_POST['uname']) && isset($_POST['pwd'])){
	  if ( !$user->login($_POST['uname'],$_POST['pwd'],$_POST['remember'] )){//Mention that we don't have to use addslashes as the class do the job
	    echo 'Usuario y/o contrase&ntilde;a inv&aacute;lido';
	  }else{

	  	
	    //user is now loaded
		header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

	  }
	}
	
	
	
	echo '<h2>Usuario</h2>
          <div class="clr"></div>
		  <form method="post" action="'.$_SERVER['PHP_SELF'].'" />
			<ol>
              <li>
                <label for="uname">Nombre:</label>
                <input id="uname" name="uname" class="text" type="text" />
              </li>
			  <li>
                <label for="uname">Contrase&ntilde;a:</label>
                <input id="pwd" name="pwd" class="text" type="password"/>
              </li>
			  <li>
                Recordar Usuario?
                <input id="remember" name="remember" type="checkbox"/>
              </li>
			  <li>
               <input type="submit" value="Acceder" class="send"/>
			   <div class="clr"></div>
              </li>
			 </ol>
			
		  </form>';
}else{
  //User is loaded
  
	
	session_start();
	$uname = $_SESSION['uname'];
	//print_r($usuario);
	//die();
	
	echo '<a href="'.$_SERVER['PHP_SELF'].'?logout=1">logout  <br></a>';
	
	echo 'Usuario: '.$uname;
	echo '<h2 class="star"><span>Acceso a </span> Programas</h2><div class="clr"></div><ul class="sb_menu">';
	

	
	if ($user->tienePermiso('simplerub')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/rub/simple.php">Consulta Ficha RUB</a>';
	}
	if ($user->tienePermiso('superbase')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/base_ticket/index.php">Base Ticket</a>';
	}
	
	if ( $user->tienePermiso('seguimiento')) {
	    echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/seguimientohogares/index.php">Seguimiento Hogares</a>';
	    
	}
	
	if ($user->tienePermiso('superbase')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/superbase/index.php">SuperBase</a>';
	}
	
	if ($user->tienePermiso('multiplerub')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/rub/multiple.php">Impresi&oacute;n m&uacute;ltiples Ficha RUB</a>';
	}
	if ($user->tienePermiso('hogarrub')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/rub/solorub.php">Consultar Hogar RUB</a>';
	}
	
	if ($user->tienePermiso('canasta')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/canasta/index.php">Calculo de Canasta Familiar</a>';
	}
	if ($user->tienePermiso('gerencia')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/supervision_entrevistas/index.php">Supervision Entrevistas</a>';
	}
	if ($user->tienePermiso('infoeet')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/infoeet/index.php">Informacion EET</a>';
	}
	if ($user->tienePermiso('admin')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/login/admin.php">Administraci&oacute;n de Usuarios</a>';
	}
	if ($user->tienePermiso('reportes_Ticket')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/reportes_Ticket/power.php">Reporte</a>';
	}

	if ($user->tienePermiso('coordinacion')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/coordinacion/index.php">Coordinacion</a>';
	}

	if ($user->tienePermiso('educacion')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/educacion/index.php">Educacion</a>';
	}


	if ($user->tienePermiso('archivo')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/archivo/index.php">Archivo</a>';
	}

	if ($user->tienePermiso('entrevistas')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/desarrollo_informes_entrevistas/index.php">Informes Entrevistas</a>';
	}
	if ($user->tienePermiso('revision')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/test_Desarrollo_Informes_entrevistas/index.php">Revision</a>';
	}
	
	
	if ($user->tienePermiso('reporte')) {
		echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/reportes/power.php">Reportes</a>';
	}
	
	if ($user->tienePermiso('padrones')) {
	    echo '<li><a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/padrones/index.php">Auditoria</a>';
	}
	
	echo '</ul></div></div>';
}
?>