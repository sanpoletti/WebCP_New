<?php
//require de la coneccion a mysql
require 'ConnMySql.php';

class UsuarioDAOImpl implements UsuarioDAO{
	
	public function insertUsuario(UsuarioDTO $udto){
		$cnn = new ConnMySql();
		$con = $cnn->getConexion();
				
		$nom = $udto->getNombre();
		$ape = $udto->getApellido();
		$usu = $udto->getUsuario();
		$pass = $udto->getPassw();
		
		$query="INSERT INTO usuarios(nombre, apellido, usuario, passw) 
				VALUES('$nom', '$ape', '$usu', '$pass');";
		$result = mysql_query($query,$con);
		
		if ($result) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $result . "<br>" . mysql_error($result);
		}
		
	}
	
	public function deleteUsuario($id){
		$cnn = new ConnMySql();
		$con = $cnn->getConexion();
		
		$query = "DELETE FROM usuarios WHERE iduser = $id;";
		mysql_query($query,$con);
	}
	
	public function updateUsuario(UsuarioDTO $udto, $iduser){
		$cnn = new ConnMySql();
		$con = $cnn->getConexion();
		
		$nom = $udto->getNombre();
		$ape = $udto->getApellido();
		$usu = $udto->getUsuario();
		$pass = $udto->getPassw();
		
		$query = "UPDATE usuarios 
				SET nombre 	 = '$nom', 
		            apellido = '$ape',  
		            usuario  = '$usu',
					passw    = '$pass'
				WHERE iduser = $iduser; ";
		mysql_query($query,$con);
		echo mysql_errno($con) . ": " . mysql_error($con) . "\n";
	}
	
	public function getAllUsuario(){
		$cnn = new ConnMySql();
		$con = $cnn->getConexion();
		
		$query = "SELECT * FROM usuarios ORDER BY iduser DESC;";
		$result = mysql_query($query);
		/*while ($fila = mysql_fetch_array($result, MYSQL_NUM)) {
    		echo ".l,: ".$fila[0];
			printf("ID: %s  Nombre: %s", $fila[0], $fila[1]);  
		}*/
		return $result;		
	}
	
	public function getUsuarioById($iduser){
		$cnn = new ConnMySql();
		$con = $cnn->getConexion();
	
		$query = "SELECT * FROM usuarios 
				  WHERE iduser = $iduser;";
		$result = mysql_query($query);
		return $result;
	}
}
?>