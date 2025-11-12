<?php
require 'UsuarioDAO.php';
require 'UsuarioDAOImpl.php';
require 'UsuarioDTO.php';

//Recibo las variables
@$idu = $_POST['idu'];
@$nombre = $_POST['nombre']; 
@$apellido = $_POST['apellido'];
@$usuario = $_POST['usuario'];
@$passw = $_POST['passw'];
@$val = $_POST['1'];
@$id = $_GET['id'];
@$v2 = $_GET['v2'];
@$v3 = $_POST['v3'];

	if($val==1){
		//creo el objeto usuario y seteo los valores
		$udto = new UsuarioDTO();
		$udto->setNombre($nombre);
		$udto->setApellido($apellido);
		$udto->setUsuario($usuario);
		$udto->setPassw($passw);
		
		//instancio y inserto el usuario
		$udao = new UsuarioDAOImpl();
		$udao->insertUsuario($udto);
		
		header('Location: http://localhost/afamp/');
	}
	
	if($v2==2){
		//Borro el usuario por Id
		$udao = new UsuarioDAOImpl();
		$udao->deleteUsuario($id);
		
		header('Location: http://localhost/afamp/');
	}

	if($v3==3){
		//Actualizo usuario por Id
		//$udto->setId($iduser);
		$udtoUp = new UsuarioDTO();
		$udtoUp->setNombre($nombre);
		$udtoUp->setApellido($apellido);
		$udtoUp->setUsuario($usuario);
		$udtoUp->setPassw($passw);
	
		$udao = new UsuarioDAOImpl();
		$udao->updateUsuario($udtoUp, $idu);
	
		header('Location: http://localhost/afamp/');
	}

function getAllFuncionario(){
	$udao = new UsuarioDAOImpl();
	return $udao->getAllUsuario();
}

?>