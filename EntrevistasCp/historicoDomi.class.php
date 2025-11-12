<?php

include_once 'entrevistas.class.php';

/**
* Clase Principal del Historico de Domicilios
* Modela los datos del Programa
*/
class DomicilioHogarCPHistorico extends SHDO {	
		
	public function __construct($ntitu, $arrAliasNames=null) {
		parent::__construct("Historico_domi_cp $ntitu");
		$this->autoPopulate($arrAliasNames);
	}
}
?>