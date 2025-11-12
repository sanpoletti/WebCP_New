<?php
class UsuarioDTO{
	private $id;
	private $nombre;
	private $apellido;
	private $usuario;
	private $passw;
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getNombre(){
		return $this->nombre;
	}
	
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	
	public function getApellido(){
		return $this->apellido;
	}
	
	public function setApellido($apellido){
		$this->apellido = $apellido;
	}
	
	public function getUsuario(){
		return $this->usuario;
	}
	
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}
	
	public function getPassw(){
		return $this->passw;
	}
	
	public function setPassw($passw){
		$this->passw = $passw;
	}
//Sitio web que genera los setter y getters	
//http://mikeangstadt.name/projects/getter-setter-gen/
}