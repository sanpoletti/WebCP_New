<?php

include 'entrevistas.class.php';

/**
* Clase Principal del Historico de Pagos
* Modela los datos del Programa
*/
class HistoricoPagos extends SHDO {	
		
	public function __construct($ntitu, $arrAliasNames=null) {
		parent::__construct("_pagos $ntitu");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
* Clase Principal del Historico de Pagos de EET
* Modela los datos del Programa
*/
class HistoricoPagosEET extends SHDO {	
		
	public function __construct($ntitu, $sec, $arrAliasNames=null) {
		parent::__construct("historicopagoseet $ntitu, $sec");
		$this->autoPopulate($arrAliasNames);
	}
}
?>