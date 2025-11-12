<?php
interface UsuarioDAO{
	//metodos de la interface
	public function insertUsuario(UsuarioDTO $udto);
	public function deleteUsuario($id);
	public function updateUsuario(UsuarioDTO $udto, $id);
	public function getAllUsuario();
	
}
?>