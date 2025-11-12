< <?php

include 'rdo.class.php';
include 'adodb5/adodb.inc.php';

/**
* Clase que modela los datos devueltos en consultas a la 
* base de datos de Seguimiento del Hogar
*/
abstract class FRDO extends DataObject {
	//const __CACHE_1_DIA = 86400;
	const __CACHE_DESHABILITADO = -1;
	const _DEBUG = FALSE;
	const _CACHE_TIMEOUT = self::__CACHE_DESHABILITADO;
	//const _CACHE_TIMEOUT = self::__CACHE_1_DIA; //__CACHE_DESHABILITADO;
	const _DNS = 	"Driver={SQL Server};
					Server=10.22.0.253;
					Database=rubs;
					Integrated Security=SSPI;
					Persist Security Info=False;
					trusted_connection=no";
	
	private $cacheTimeout;
	private $spWithParams;

	public function __construct($spWithParams, $timeout=self::_CACHE_TIMEOUT) {
		$this->spWithParams = $spWithParams;
		$this->cacheTimeout = $timeout;
	}
	
	
	/**
	* Llena su matriz de valores utilizando JDBC de su propia conexion DNS
	* @param $arrFieldNames Campos a seleccionar de la consulta
	* @param $arrAliasNames (Opcional) Alias de las properties a 
	* usar en la matriz de datos
	*/
	protected function autoPopulateFields($arrFieldNames, $arrAliasNames=null) {
		$db = $this->getConnection();
		$rs = $this->getRecordset($db);
		parent::autoPopulateFields($rs, $arrFieldNames, $arrAliasNames);
	}
	
	/**
	* Llena su matriz de valores utilizando JDBC de su propia conexion DNS
	* @param $arrAliasNames (Opcional) Alias de las properties a 
	* usar en la matriz de datos
	*/
	protected function autoPopulate($arrAliasNames=null) {
		$db = $this->getConnection();
		$rs = $this->getRecordset($db);
		parent::autoPopulate($rs, $arrAliasNames);
	}
	
	protected function autoPopulateTest($arrAliasNames=null) {
	    $db = $this->getConnection();
	    $rs = $this->getRecordset($db);
	    print_r($rs); die;
	    parent::autoPopulate($rs, $arrAliasNames);
	}
	
	private function isCacheEnabled() {
		return $this->cacheTimeout > 0;
	}
	
	/**
	* Abre una conexion JDBC
	*/
	private function getConnection() {
		$db =& ADONewConnection('odbc_mssql');
		
		$db->debug = self::_DEBUG;
		$db->Connect(self::_DNS,'ficharub', 'colo');
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		
		if (!$db) die("Connection failed");
		   
		return $db;
	}

	/**
	* Abre un recordset
	* @param db DB Conection
	*/
	private function getRecordset($db) {
		if ($this->isCacheEnabled()) {
			global $ADODB_CACHE_DIR;
			$ADODB_CACHE_DIR = 'cache';
			$rs = $db->CacheExecute($this->cacheTimeout, $this->spWithParams);	
		} else {
			$rs = $db->Execute($this->spWithParams);
		}
		
		return $rs;
	}
	
	
	/**
	* Mezcla las propiedades de la 1ra fila del objeto con las pasadas por parametro.
	* @param $arrProperties Array de 1 dimension con las propiedades de la 1ra fila.
	* @param $dataRowNumber Numero de fila donde mergear las properties. Por defecto es la 1ra.
	*/
	protected function mergeUniqueData($arrProperties, $dataRowNumber=0) {
		$mergedValues = array_merge ($this->data[$dataRowNumber]->getProperties(), $arrProperties);			
		$this->data[$dataRowNumber]->populate($mergedValues);
	}
}

/**
 * Clase para buscar las personas por Nro. de titular o DNI
 */
class Personas extends FRDO {
	public function __construct($ntitu, $nrodoc, $timeout=self::_CACHE_TIMEOUT) {
		parent::__construct(Personas::getSPString($ntitu, $nrodoc), $timeout);
		$this->autoPopulate();
	}
	
	private static function getSPString($ntitu, $nrodoc) {
		if (empty($ntitu)) {
			return "_personas 0, $nrodoc";	
		} else {
			return "_personas $ntitu, 0";
		}
	}
}

/**
* Clase Principal de Ficha Rub
* Modela los datos del Programa Rub
*/
class Ficharub {
	private $nTitular;
	private $nRub;
	private $hogarCP;
	private $hogarRub;
	private $clasificacion;

	public function __construct($ntitu, $nrorub) {
		$this->nTitular = $ntitu;
		$this->nRub = $nrorub;	
	}
	
	/**
	* Devuelve el Hogar CP (con los integrantes del mismo)
	*/
	public function getHogarCP() {
		if (!isset($this->hogarCP)) {
			$this->hogarCP = new HogarCP($this->nTitular);
		}
		return $this->hogarCP;
	}
	
	/**
	 * Devuelve el numero del titular
	 */
	public function getNTitular() {
		return $this->nTitular;
	}
	
	/**
	 * Verifica si tiene los datos suficientes para cargar los datos del hogar CP.
	 * El dato necesario es el NRO DE TITULAR (pasado al construir el objeto)
	 */
	public function hasDataToPopulateCP() {
		return isset($this->nTitular);
	}
	
	/**
	* Devuelve el Hogar Rub (con los integrantes del mismo)
	*/
	public function getHogarRub() {
		if (!isset($this->hogarRub)) {
			$this->hogarRub = new HogarRub($this->nRub);
		}
		return $this->hogarRub;
	}
	
	public function getClasificacion() {
		if (!isset($this->clasificacion)) {
			$this->clasificacion = new ClasificacionHogarCP($this->nroRub);
		}
		return $this->clasificacion;
	}
	
	/**
	* Obtiene el valor de una propiedad de la calificacion de CP
	* param $propName Nombre de la propiedad (nombre del campo)
	*/
	public function getCalifCPProperty($propName) {
		return $this->califCP->getProperty($propName);
	}
	
	public function __toString() {
		$result = '<hr/>Ficharub Object<br/><ul>';
		$result .= '<li>HogarCP: ' . $this->getHogarCP();
		$result .= '<li>Titular HogarCP: ' . $this->getHogarCP()->getTitular();
		$result .= '<li>Domicilio CP: ' . $this->getHogarCP()->getDomicilio();
		$result .= '<li>Tarjetas: ' . $this->getHogarCP()->getTarjetas();
		$result .= '<li>Pagos: ' . $this->getHogarCP()->getPagos();
		$result .= '<li>HogarRub: ' . $this->getHogarRub();
		$result .= '</ul><hr/>';
		return $result;
	}
}


/**
* Clase que modela el Hogar CP
* Contiene los datos de cada integrante del hogar
*/
class HogarCP extends FRDO {	
	private $nTitular;
	private $nRub;
	private $titular;
	private $domicilio;
	private $tarjetas;
	private $turnos;
	private $pagos;
	private $sintys;
	private $bancos;
	private $observaciones;
	private $clasificacion;
	
	public function __construct($ntitu, $arrAliasNames=null) {
		parent::__construct("_buscointegrantes '', $ntitu");
		$this->nTitular = $ntitu;
		$this->autoPopulate($arrAliasNames); //lleno el dataobjet con los datos del Sp
		$this->titular = new TitularCP($ntitu, $this);
		$this->populateComparte();
	}
	
	/**
	* Busca en cada integrante si existe en otro hogar
	*/
	public function populateComparte() {
		if (count($this->getData()) > 0 ) {
		// Recorro los integrantes del hogar
			foreach($this->getData() as $hdo){
			// Por cada integrante, busco si esta en mas de un hogar
				$dni = $hdo->getProperty('nro_doc');
				$integra = new Personas('', $dni);
				$comparte = (count($integra ->getData()) > 1);	// Si esta en mas de un hogar => COMPARTE
				$hdo->setProperty('comparte', $comparte);	// Agrego campo dummy de 'comparte'
			}
		}
	}
	
	
	/**
	* Devuelve el numero de titular del hogar (NTitular)
	*/
	public function getNTitular() {
		return $this->nTitular;
	}
	
	/**
	* Devuelve el titular del Hogar
	* @see{TitularCP}
	*/
	public function getTitular() {
		return $this->titular;
	}
	
	/**
	* Devuelve una propiedad (campo de la consulta)
	* del titular
	*/
	public function getTitularProperty($propName) {
		$tituRDO = $this->getTitular()->getUniqueData();
		return $tituRDO->getProperty($propName);
	}
	
	/**
	* Devuelve el domicilio del Hogar CP
	*/
	public function getDomicilio() {
		if (!isset($this->domicilio)) {
			$this->domicilio = new DomicilioHogarCP($this->getTitularProperty('ntitular'));
		}
		return $this->domicilio;
	}

	
	
	/**
	* Devuelve las tarjetas de los integrantes y el titular del Hogar CP
	*/
	public function getTarjetas() {
		if (!isset($this->tarjetas)) {
			$this->tarjetas = new Tarjetas($this->getNTitular(), $this->getTitularProperty('NRO_DOC'));
		}
		return $this->tarjetas;
	}
	
	/**
	* Devuelve el turno del Hogar CP
	*/
	public function getTurnos() {
		if (!isset($this->turnos)) {
			$this->turnos = new Turnos($this->getNTitular());
		}
		return $this->turnos;
	}
	
	/**
	* Devuelve los pagos de los integrantes del Hogar CP
	*/
	public function getPagos() {
		if (!isset($this->pagos)) {
			$this->pagos = new Pagos($this->getNTitular());
		}
		return $this->pagos;
	}
	
	/**
	* Devuelve las observaciones SINTYS
	*/
	public function getSintys() {
		if (!isset($this->sintys)) {
			$this->sintys = new Sintys($this->getNTitular());
		}
		return $this->sintys;
	}
	
	/**
	 * 
	 * devuelve las observaciones de Banco ...
	 */
	public function getBancos() {
		if (!isset($this->bancos)) {
			// FIXME Agregar SP de Bancos en constructor de clase y descomentar la linea de abajo
			//$this->bancos = new Bancos($this->getNTitular());
		}
		return $this->bancos;
	}
	
	public function getObservaciones() {
		if (!isset($this->observaciones)) {
			$this->observaciones = new ObservacionesCP($this->getNTitular());
		}
		return $this->observaciones;
	}
	
}

/**
* Clase que modela al titular del Hogar CP
* Tiene las propiedades de un integrante del hogar y 
* las propiedades de calificacion del titular
*/
class TitularCP extends FRDO {
	public function __construct($nTitular, $hogarCP, $arrAliasNames=null) {
		parent::__construct("_calificacion $nTitular");
		$this->autoPopulate($arrAliasNames);
	// Agrego properties del titular del hogar ciudadania
		$this->mergeUniqueData($this->getTitularData($hogarCP));
	}
	
	/**
	* Recorre los integrantes del hogar y selecciona
	* el titular.
	* En caso de no encontrarlo devuelve NULL
	*/
	private function getTitularData($hogarCP) {
		foreach ($hogarCP->getData() as $rdo) {
			if ($rdo->getProperty('TITULAR') == 'TITULAR') {
				return $rdo->getProperties();
			}
		}
		return null;
	}
	
	public function __toString() {
		$result = '<TitularCP Object> ';
		if (!empty($this->data)) {
			return $result . $this->data[0];
		}
		return $result;
	}
}

/**
* Clase que modela las tarjetas de los integrantes del hogar CP y del titular
*/
class Tarjetas extends FRDO {
	public function __construct($nTitular, $nDocTitular, $arrAliasNames=null) {
		parent::__construct("_tarjetas $nTitular, $nDocTitular");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
* Clase que modela el Domicilio del Hogar CP
*/
class DomicilioHogarCP extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("domicilio_cp $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
}


class ClasificacionHogarCP extends FRDO {
	public function __construct($nrorub, $arrAliasNames=null) {
		parent::__construct("_clasirub $nrorub");
		$this->autoPopulate($arrAliasNames);
	}
}


/**
* Clase que modela el las tarjetas en banco del Hogar CP
*/
class TarjetaTitularCP extends FRDO {
	public function __construct($nroDoc, $arrAliasNames=null) {
		parent::__construct("_tarjetas_cp $nroDoc");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
* Clase que modela el los turnos del Hogar CP
*/
class Turnos extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("_turnos $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
* Clase que modela el los pagos de los Integrantes del Hogar CP
*/
class Pagos extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("_pagos_cp $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
	
}

/**
 * 
 * Clase para modelar las observaciones SINTYS
 * @author Administrator
 */
class Sintys extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("_observa_Sintys $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
 * 
 * Clase para modelar las observaciones Bancos
 * @author Administrator
 */
class Bancos extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("_observa_Bancos $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
 * Clase que modela el Hogar RUB
 * Contiene los datos de cada integrante del hogar
 * @author Sebastian Canepa
 */
class HogarRub extends FRDO {	
	private $Rel;
	private $nroRub;
	private $Miembro;
	private $DomiRub;
	private $indiceRub;
	private $indiceCP08;	// Mayo 2008
	private $indiceCP09;	// Marzo 2009
	private $indiceCP10;	// Marzo 2010
	private $indiceCP11;	// Marzo 2011
	private $indiceCP12;	// Marzo 2012
	private $indiceCP13;	// Marzo 2013
	private $indiceSeptiembre13;// Septiembre 2013
	private $indiceOctubre13;	// octubre 2013
	private $indiceNoviembre13;	// Noviembre 2013
	private $indiceDiciembre13;	// Diciembre 2013
	private $indiceMayo14;	// Mayo 2014
	private $indiceAgosto14;	// Agosto 2014
	private $indiceSeptiembre14;	// septiembre 2014
	private $indiceNoviembre14;	// Noviembre 2014
	private $indiceEnero15;	// Enero 2015
	private $indiceAbril15;	// Abril 2015
	private $indiceAgosto15;	// Abril 2015
	private $indiceFebrero16;	// Febrero 2016
	private $indiceAgosto16;   // agosto 2016
	private $indiceNoviembre16;   // agosto 2016
	private $indiceFebrero17;   // agosto 2017
	private $indiceJunio17;   // agosto 2017
	private $indiceSeptiembre17; // septiembre 2017
	private $indiceEnero18; //Enero 2018
	private $indiceFebrero18; //febrero 2018
	private $indiceMarzo18; //mayou 2018
	private $indiceAbril18; //abril 2018
	private $indiceMayo18; //mayo 2018
	private $indiceJunio18;//Junio 2018
	private $indiceJulio18; //Julio 2018
	private $indiceAgosto18; //Agosto 2018
	private $indiceSeptiembre18; //Septiembre 2018
	private $indiceOctubre18; //Octubre 2018
	private $indiceNoviembre18;//noviembre 2018
	private $indiceEnero19;//Enero 2019
	private $indiceFebrero19;//febreo 2019
	private $indiceMarzo19;//Marzo 2019
	private $indiceAbril19;//Abril 2019
	private $indiceMayo19; //Mayo 2019
	private $indiceJunio19; //Junio 2019
	private $indiceJulio19; //Julio 2019
	private $indiceAgosto19; //Agosto 2019
	private $indiceSeptiembre19; //septiembre2019
	private $indiceOctubre19; //octubre2019
	private $indiceNoviembre19; //noviembre2019
	private $indiceDiciembre19; //diciembre2019
	private $indiceFebrero20; //Febrero20
	private $indiceMarzo20; //Marzo20
	private $indiceAbril20; //Abril20
	private $indiceMayo20; //Mayo20
	private $indiceJunio20; //Junio20
	private $indiceJulio20; //Julio20
	private $indiceAgosto20; //Agosto20
	private $indiceSeptiembre20; //Septiembre20
	private $indiceOctubre20; //Septiembre20
	private $indiceNoviembre20; //Noviembre20
	private $indiceDiciembre20;
	private $indiceEnero21;//Enero 21
	private $indiceFebrero21; //Febrero 21
	private $indiceMarzo21; //Marzo 21
	private $indiceAbril21; //Abril 21
	private $indiceMayo21; //Mayo 21
	private $indiceJunio21; //Junio 21
	private $indiceJulio21; //Julio 21
	private $indiceAgosto21; //Agosto 21
	private $indiceSeptiembre21; //Septiembre 21
	private $indiceOctubre21; //Octubre 21
	private $indiceNoviembre21; //Noviembre 21
	private $indiceDiciembre21; //Diciembre 21
	private $indiceEnero22; //Enero 22
	private $indiceFebrero22; //Febrero 22
	private $indiceMarzo22; //Marzo 22
	private $indiceAbril22; //Abril 22
	private $indiceMayo22; //Mayo
	private $indiceJunio22; //Junio 22
	private $indiceJulio22; //Julio 22
	private $indiceAgosto22; //agosto 22
	private $indiceSeptiembre22; //septiembre 22
	private $indiceOctubre22; //Octubre 22
	private $indiceNoviembre22; //Noviembre 22
	private $indiceDiciembre22; // Diciembre
	private $indiceEnero23; // Enero 23
	private $indiceFebrero23; // febrero 23
	private $indiceMarzo23; // Marzo 23
	private $indiceAbril23; // Abril 23
	private $indiceMayo23; // Mayo 23
	private $indiceJunio23; // junio 23
	private $indiceJulio23; // julio 23
	private $indiceAgosto23; // agosto 23
	private $indiceSeptiembre23; // septiembre 23
	private $indiceOctubre23; // octubre 23
	private $indiceNoviembre23; // 
	private $indiceDiciembre23; // 
	private $indiceEnero24; // 
	private $indiceFebrero24; // 
	private $indiceMarzo24;
	private $indiceAbril24;
	private $indiceMayo24;
	private $indiceJunio24;
	private $indiceJulio24;
	private $indiceAgosto24;
	private $indiceSeptiembre24;
	private $indiceOctubre24;
	private $indiceNoviembre24;
	private $indiceDiciembre24;
	private $indiceEnero25;
	private $indiceFebrero25;
	private $indiceMarzo25;
	private $indiceAbril25;
	private $indiceMayo25;
	private $indiceJulio25;
	private $salud;
	private $vivienda;
	private $ingresos;
	private $detalleIngresos;
	private $clasirub;
	private $alquila_sub;
	private $comercio;
	private $propietario;
	private $discapacidad;
	private $observaciones;
	private $bienes;
	private $otrosbienes;
	private $bienes_obs;
	private $vivienda2;
	private $clasificacion; 
	private $prc;
	
	public function __construct($nroRub, $arrAliasNames=null) {
		parent::__construct("_buscar_HOGAR_RUB_fichaN $nroRub");
		$this->nroRub = $nroRub;
		$this->autoPopulate($arrAliasNames);
	}
	
	public function getNRub() {
		return $this->nroRub;
		
	}
	
	public function getDomicilioRub() {
		if (!isset($this->DomiRub)) {
			$this->DomiRub = new DomicilioHogarRub($this->getNRub());
		}
		return $this->DomiRub;
	}
	
	public function getClasiRub() {
		if (!isset($this->clasirub)) {
			$this->clasirub = new Clasirub($this->getNRub());
		}
		return $this->clasirub;
	}
	
	public function getIndiceRub() {
		if (!isset($this->indiceRub)) {
			$this->indiceRub = new IndiceRub($this->getNRub(), 2.0, 1.50, 1.75,1.25);
		}
		return $this->indiceRub;
	}
	
	public function getIndiceCP08($ntitu) {
		if (!isset($this->indiceCP08)) {
			$this->indiceCP08 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 315.99, 145.62);
		}
		return $this->indiceCP08;
	}
	
	public function getIndiceCP09($ntitu) {
		if (!isset($this->indiceCP09)) {
			$this->indiceCP09 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 322.57, 143.54);
		}
		return $this->indiceCP09;
	}
	
	public function getIndiceCP10($ntitu) {
		if (!isset($this->indiceCP10)) {
			$this->indiceCP10 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 371.94, 170.94);
		}
		return $this->indiceCP10;
	}
	
	public function getIndiceCP11($ntitu) {
		if (!isset($this->indiceCP11)) {
			$this->indiceCP11 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 411.49, 187.79);
		}
		return $this->indiceCP11;
	}

	public function getIndiceCP12($ntitu) {
		if (!isset($this->indiceCP12)) {
			$this->indiceCP12 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 465.34, 200.94);
		}
		return $this->indiceCP12;
	}

	public function getIndiceCP13($ntitu) {
		if (!isset($this->indiceCP13)) {
			$this->indiceCP13 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 535.73, 234.94);
		}
		return $this->indiceCP13;
	}
	
	public function getIndiceSeptiembre13($ntitu) {
		if (!isset($this->indiceSeptiembre13)) {
			$this->indiceSeptiembre13 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 555.94, 242.9);
		}
		return $this->indiceSeptiembre13;
	}
	
	public function getIndiceOctubre13($ntitu) {
		if (!isset($this->indiceOctubre13)) {
			$this->indiceOctubre13 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 561.28, 245.75);
		}
		return $this->indiceOctubre13;
	}
	
	public function getIndiceNoviembre13($ntitu) {
		if (!isset($this->indiceNoviembre13)) {
			$this->indiceNoviembre13 = new IndiceCP($ntitu, 2.0, 1.50,1.25, 1.75, 566.43, 249.06);
			
		}
		return $this->indiceNoviembre13;
	}
	
	public function getIndiceDiciembre13($ntitu) {
		if (!isset($this->indiceDiciembre13)) {
			$this->indiceDiciembre13 = new IndiceCP($ntitu, 2.0, 1.50,1.25, 1.75, 577.23 , 254.78);
		}
		return $this->indiceDiciembre13;
	}
	
	
	public function getIndiceMayo14($ntitu) {
		if (!isset($this->indiceMayo14)) {
			$this->indiceMayo14 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 701.90 , 305.20);
		
		}
		return $this->indiceMayo14;
	}
	
	public function getIndiceAgosto14($ntitu) {
		if (!isset($this->indiceAgosto14)) {
			$this->indiceAgosto14 = new IndiceCP($ntitu, 2.0, 1.50, 1.75, 1.25,733.90 , 319.10);
		}
		return $this->indiceAgosto14;
	}
	
	
	public function getIndiceSeptiembre14($ntitu) {
		if (!isset($this->indiceSeptiembre14)) {
			$this->indiceSeptiembre14 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 767.3 , 333.6);
		}
		return $this->indiceSeptiembre14;
	}
	
	
	public function getIndiceNoviembre14($ntitu) {
		if (!isset($this->indiceNoviembre14)) {
			$this->indiceNoviembre14 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 795.90 , 346.10);
		}
		return $this->indiceNoviembre14;
	}
	
	public function getIndiceEnero15($ntitu) {
		if (!isset($this->indiceEnero15)) {
			$this->indiceEnero15 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 824.0 , 359.3);
		}
		return $this->indiceEnero15;
	}
	
	public function getIndiceAbril15($ntitu) {
		if (!isset($this->indiceAbril15)) {
			$this->indiceAbril15 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  871.0 , 378.7);
		}
		return $this->indiceAbril15;
	}
	
	public function getIndiceAgosto15($ntitu) {
		if (!isset($this->indiceAgosto15)) {
			$this->indiceAgosto15 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  937.3 ,  407.5);
		}
		return $this->indiceAgosto15;
	}
	
	public function getIndiceFebrero16($ntitu) {
		if (!isset($this->indiceFebrero16)) {
			$this->indiceFebrero16 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 1112.3 ,  483.6);
		}
		return $this->indiceFebrero16;
	}
	
	public function getIndiceAgosto16($ntitu) {
		if (!isset($this->indiceAgosto16)) {
			$this->indiceAgosto16 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  4041.87 , 1675.05);
		}
		return $this->indiceAgosto16;
	}
	
	
	public function getIndiceNoviembre16($ntitu) {
		if (!isset($this->indiceNoviembre16)) {
			$this->indiceNoviembre16 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  4247.99 , 1762.65);
		}
		return $this->indiceNoviembre16;
	}
	
	
	public function getIndiceFebrero17($ntitu) {
		if (!isset($this->indiceFebrero17)) {
			$this->indiceFebrero17 = new IndiceCP($ntitu, 2.0, 1.50, 1.75, 1.25, 4425.08 , 1821.02);
		}
		return $this->indiceFebrero17;
	}
	
	public function getIndiceJunio17($ntitu) {
		if (!isset($this->indiceJunio17)) {
			$this->indiceJunio17 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 4793.23 , 1956.42);
		}
		return $this->indiceJunio17;
	}
	
	public function getIndiceSeptiembre17($ntitu) {
		if (!isset($this->indiceSeptiembre17)) {
			$this->indiceSeptiembre17 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 5000.51 , 2049.39);
		}
		return $this->indiceSeptiembre17;
	}
	
	
	public function getIndiceEnero18($ntitu) {
		if (!isset($this->indiceEnero18)) {
			$this->indiceEnero18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 5493.15 , 2197.26);
		} 
		return $this->indiceEnero18;
	}
	
	public function getIndiceFebrero18($ntitu) {
		if (!isset($this->indiceFebrero18)) {
			$this->indiceFebrero18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 5675.69 , 2261.23);
		}
		return $this->indiceFebrero18;
	}

	public function getIndiceMarzo18($ntitu) {
		if (!isset($this->indiceMarzo18)) {
			$this->indiceMarzo18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 5782.29 , 2294.56);
		}
		return $this->indiceMarzo18;
	}
	
	public function getIndiceAbril18($ntitu) {
		if (!isset($this->indiceAbril18)) {
			$this->indiceAbril18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  5908.76 , 2308.11);
		}
		return $this->indiceAbril18;
	}
	
	public function getIndiceMayo18($ntitu) {
		if (!isset($this->indiceMayo18)) {
			$this->indiceMayo18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  6095.76 , 2418.65);
		}
		return $this->indiceMayo18;
	}
	
	public function getIndiceJunio18($ntitu) {
		if (!isset($this->indiceJunio18)) {
			$this->indiceJunio18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  6343.62 , 2537.45);
		}
		return $this->indiceJunio18;
	}
	
	public function getIndiceJulio18($ntitu) {
		if (!isset($this->indiceJulio18)) {
			$this->indiceJulio18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  6515.88 , 2627.37);
		}
		return $this->indiceJulio18;
	}
	
	public function getIndiceAgosto18($ntitu) {
		if (!isset($this->indiceAgosto18)) {
			$this->indiceAgosto18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  6753.70 , 2701.48);
		}
		return $this->indiceAgosto18;
	}
	
	public function getIndiceSeptiembre18($ntitu) {
		if (!isset($this->indiceSeptiembre18)) {
			$this->indiceSeptiembre18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  7300.38 , 2931.88);
		}
		return $this->indiceSeptiembre18;
	}
	
	public function getIndiceOctubre18($ntitu) {
		if (!isset($this->indiceOctubre18)) {
			$this->indiceOctubre18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  7845.04 , 3150.65);
		}
		return $this->indiceOctubre18;
	}
	
	public function getIndiceNoviembre18($ntitu) {
		if (!isset($this->indiceNoviembre18)) {
			$this->indiceNoviembre18 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  8157.29 , 3276.02);
		}
		return $this->indiceNoviembre18;
	}
	
	public function getIndiceEnero19($ntitu) {
		if (!isset($this->indiceEnero19)) {
			$this->indiceEnero19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  8557.58 , 3423.03);
		}
		return $this->indiceEnero19;
	}
	
	public function getIndiceFebrero19($ntitu) {
		if (!isset($this->indiceFebrero19)) {
			$this->indiceFebrero19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  8922.47 , 3597.77);
		}
		return $this->indiceFebrero19;
	}
	
	public function getIndiceMarzo19($ntitu) {
	    if (!isset($this->indiceMarzo19)) {
	        $this->indiceMarzo19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  9304.51 , 3767.01);
	    }
	    return $this->indiceMarzo19;
	}
	
	public function getIndiceAbril19($ntitu) {
	    if (!isset($this->indiceAbril19)) {
	        $this->indiceAbril19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  9544.87 , 3833.28);
	    }
	    return $this->indiceAbril19;
	}
	
	public function getIndiceMayo19($ntitu) {
	    if (!isset($this->indiceMayo19)) {
	        $this->indiceMayo19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  9818.07 , 3911.58);
	    }
	    return $this->indiceMayo19;
	}
	
	public function getIndiceJunio19($ntitu) {
	    if (!isset($this->indiceJunio19)) {
	        $this->indiceJunio19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  10080.39 ,  4016.09);
	    }
	    return $this->indiceJunio19;
	}
	
	public function getIndiceJulio19($ntitu) {
	    if (!isset($this->indiceJulio19)) {
	        $this->indiceJulio19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  10334.77 ,  4133.91);
	    }
	    return $this->indiceJulio19;
	}
	
	public function getIndiceAgosto19($ntitu) {
	    if (!isset($this->indiceAgosto19)) {
	        $this->indiceAgosto19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  10683.89 ,  4290.72);
	    }
	    return $this->indiceAgosto19;
	}
	
	public function getIndiceSeptiembre19($ntitu) {
	    if (!isset($this->indiceSeptiembre19)) {
	        $this->indiceSeptiembre19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 11257.20  ,  4502.88);
	    }
	    return $this->indiceSeptiembre19;
	}
	
	public function getIndiceOctubre19($ntitu) {
	    if (!isset($this->indiceOctubre19)) {
	        $this->indiceOctubre19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 11536.46  ,  4596.20);
	    }
	    return $this->indiceOctubre19;
	}
	
	public function getIndiceNoviembre19($ntitu) {
	    if (!isset($this->indiceNoviembre19)) {
	        $this->indiceNoviembre19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 12166.99  ,  4886.34);
	    }
	    return $this->indiceNoviembre19;
	}
	
	public function getIndiceDiciembre19($ntitu) {
	    if (!isset($this->indiceDiciembre19)) {
	        $this->indiceDiciembre19 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 12608.52  ,  5043.41);
	    }
	    return $this->indiceDiciembre19;
	}

	
	public function getIndiceFebrero20($ntitu) {
	    if (!isset($this->indiceFebrero20)) {
	        $this->indiceFebrero20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 13200.54  ,  5432.32);
	    }
	    return $this->indiceFebrero20;
	}
	
	public function getIndiceMarzo20($ntitu) {
	    if (!isset($this->indiceMarzo20)) {
	        $this->indiceMarzo20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 13590.57  ,  5615.94);
	    }
	    return $this->indiceMarzo20;
	}
	
	public function getIndiceAbril20($ntitu) {
	    if (!isset($this->indiceAbril20)) {
	        $this->indiceAbril20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 13590.57  ,  5615.94);
	    }
	    return $this->indiceAbril20;
	}

	public function getIndiceMayo20($ntitu) {
	    if (!isset($this->indiceMayo20)) {
	        $this->indiceMayo20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 13941.87  ,  5785.01);
	    }
	    return $this->indiceMayo20;
	}
	public function getIndiceJunio20($ntitu) {
	    if (!isset($this->indiceJunio20)) {
	        $this->indiceJunio20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 14178.22  ,  5834.66);
	    }
	    return $this->indiceJunio20;
	}
	
	public function getIndiceJulio20($ntitu) {
	    if (!isset($this->indiceJulio20)) {
	        $this->indiceJulio20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 14408.18  ,  5959.29);
	    }
	    return $this->indiceJulio20;
	}
	public function getIndiceAgosto20($ntitu) {
	    if (!isset($this->indiceAgosto20)) {
	        $this->indiceAgosto20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 14717.69 ,  6081.69);
	    }
	    return $this->indiceAgosto20;
	}
	public function getIndiceSeptiembre20($ntitu) {
	    if (!isset($this->indiceSeptiembre20)) {
	        $this->indiceSeptiembre20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 15280.25 ,  6288.17);
	    }
	    return $this->indiceSeptiembre20;
	}
	
	public function getIndiceOctubre20($ntitu) {
	    if (!isset($this->indiceOctubre20)) {
	        $this->indiceOctubre20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 16152.62 , 6702.33);
	    }
	    return $this->indiceOctubre20;
	}
	public function getIndiceNoviembre20($ntitu) {
	    if (!isset($this->indiceNoviembre20)) {
	        $this->indiceNoviembre20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 16755.86 , 6981.61);
	    }
	    return $this->indiceNoviembre20;
	}
	
	public function getIndiceDiciembre20($ntitu) {
	    if (!isset($this->indiceDiciembre20)) {
	        $this->indiceDiciembre20 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 17542.89 , 7340.12);
	    }
	    return $this->indiceDiciembre20;
	}
	
	public function getIndiceEnero21($ntitu) {
	    if (!isset($this->indiceEnero21)) {
	        $this->indiceEnero21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 18271.47 , 7677.47);
	    }
	    return $this->indiceEnero21;
	}

	public function getIndiceFebrero21($ntitu) {
	    if (!isset($this->indiceFebrero21)) {
	        $this->indiceFebrero21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 18769.41 , 7953.14);
	    }
	    return $this->indiceFebrero21;
	}
	
	public function getIndiceMarzo21($ntitu) {
	    if (!isset($this->indiceMarzo21)) {
	        $this->indiceMarzo21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 19700.22 , 8312.33);
	    }
	    return $this->indiceMarzo21;
	}
	
	public function getIndiceAbril21($ntitu) {
	    if (!isset($this->indiceAbril21)) {
	        $this->indiceAbril21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 20374.61 , 8633.31);
	    }
	    return $this->indiceAbril21;
	}
	
	public function getIndiceMayo21($ntitu) {
	    if (!isset($this->indiceMayo21)) {
	        $this->indiceMayo21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 20855.99 , 8874.89);
	    }
	    return $this->indiceMayo21;
	}
	
	public function getIndiceJunio21($ntitu) {
	    if (!isset($this->indiceJunio21)) {
	        $this->indiceJunio21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25, 21517.26 , 9195.41);
	    }
	    return $this->indiceJunio21;
	}
	
	public function getIndiceJulio21($ntitu) {
	    if (!isset($this->indiceJulio21)) {
	        $this->indiceJulio21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  21869.47 , 9386.04);
	    }
	    return $this->indiceJulio21;
	}
	
	public function getIndiceAgosto21($ntitu) {
	    if (!isset($this->indiceAgosto21)) {
	        $this->indiceAgosto21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  22122.66 , 9454.13);
	    }
	    return $this->indiceAgosto21;
	}
	
	public function getIndiceSeptiembre21($ntitu) {
	    if (!isset($this->indiceSeptiembre21)) {
	        $this->indiceSeptiembre21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  22826.04 , 9713.21);
	    }
	    return $this->indiceSeptiembre21;
	}
	public function getIndiceOctubre21($ntitu) {
	    if (!isset($this->indiceOctubre21)) {
	        $this->indiceOctubre21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  23419.19 , 10008.2);
	    }
	    return $this->indiceOctubre21;
	}
	
	public function getIndiceNoviembre21($ntitu) {
	    if (!isset($this->indiceNoviembre21)) {
	        $this->indiceNoviembre21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  23921.62 , 10266.79);
	    }
	    return $this->indiceNoviembre21;
	}

	public function getIndiceDiciembre21($ntitu) {
	    if (!isset($this->indiceDiciembre21)) {
	        $this->indiceDiciembre21 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  24642.76 , 10667.86);
	    }
	    return $this->indiceDiciembre21;
	}
	
	public function getIndiceEnero22($ntitu) {
	    if (!isset($this->indiceEnero22)) {
	        $this->indiceEnero22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  25444.81 , 11111.27);
	    }
	    return $this->indiceEnero22;
	}
	
	public function getIndiceFebrero22($ntitu) {
	    if (!isset($this->indiceFebrero22)) {
	        $this->indiceFebrero22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  27122.10 , 12108.08);
	    }
	    return $this->indiceFebrero22;
	}
	
	public function getIndiceMarzo22($ntitu) {
	    if (!isset($this->indiceMarzo22)) {
	        $this->indiceMarzo22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  29026.01 , 12900.45);
	    }
	    return $this->indiceMarzo22;
	}
	
	public function getIndiceAbril22($ntitu) {
	    if (!isset($this->indiceAbril22)) {
	        $this->indiceAbril22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  30828.60 , 13762.77);
	    }
	    return $this->indiceAbril22;
	}
	public function getIndiceMayo22($ntitu) {
	    if (!isset($this->indiceMayo22)) {
	        $this->indiceMayo22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  32257.88 , 14400.84);
	    }
	    return $this->indiceMayo22;
	}
	
	public function getIndiceJunio22($ntitu) {
	    if (!isset($this->indiceJunio22)) {
	        $this->indiceJunio22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  33727.12 , 15056.75);
	    }
	    return $this->indiceJunio22;
	}
	
	public function getIndiceJulio22($ntitu) {
	    if (!isset($this->indiceJulio22)) {
	        $this->indiceJulio22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  36018.63 , 16008.28);
	    }
	    return $this->indiceJulio22;
	}
	
	
	public function getIndiceAgosto22($ntitu) {
	    if (!isset($this->indiceAgosto22)) {
	        $this->indiceAgosto22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  38756.29 ,17148.80);
	    }
	    return $this->indiceAgosto22;
	}
	public function getIndiceSeptiembre22($ntitu) {
	    if (!isset($this->indiceSeptiembre22)) {
	        $this->indiceSeptiembre22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  41493.24 ,18359.84);
	    }
	    return $this->indiceSeptiembre22;
	}
	
	public function getIndiceOctubre22($ntitu) {
	    if (!isset($this->indiceOctubre22)) {
	        $this->indiceOctubre22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  45222.57 ,20098.92);
	    }
	    return $this->indiceOctubre22;
	}
	
	public function getIndiceNoviembre22($ntitu) {
	    if (!isset($this->indiceNoviembre22)) {
	        $this->indiceNoviembre22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   47232.32 ,20715.93);
	    }
	    return $this->indiceNoviembre22;
	}
	
	public function getIndiceDiciembre22($ntitu) {
	    if (!isset($this->indiceDiciembre22)) {
	        $this->indiceDiciembre22 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   49357.70 ,21743.48);
	    }
	    return $this->indiceDiciembre22;
	}
	public function getIndiceEnero23($ntitu) {
	    if (!isset($this->indiceEnero23)) {
	        $this->indiceEnero23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   52925.14 ,23315.04);
	    }
	    return $this->indiceEnero23;
	}
	
	public function getIndiceFebrero23($ntitu) {
	    if (!isset($this->indiceFebrero23)) {
	        $this->indiceFebrero23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   57301.90 ,26046.32);
	    }
	    return $this->indiceFebrero23;
	}
	
	public function getIndiceMarzo23($ntitu) {
	    if (!isset($this->indiceMarzo23)) {
	        $this->indiceMarzo23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   61886.1 ,28388.12);
	    }
	    return $this->indiceMarzo23;
	}
	public function getIndiceAbril23($ntitu) {
	    if (!isset($this->indiceAbril23)) {
	        $this->indiceAbril23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   65812.52 ,30468.76);
	    }
	    return $this->indiceAbril23;
	}
	
	public function getIndiceMayo23($ntitu) {
	    if (!isset($this->indiceMayo23)) {
	        $this->indiceMayo23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   70522.91 ,32055.87);
	    }
	    return $this->indiceMayo23;
	}
	
	public function getIndiceJunio23($ntitu) {
	    if (!isset($this->indiceJunio23)) {
	        $this->indiceJunio23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   75219.04 ,33730.51);
	    }
	    return $this->indiceJunio23;
	}
	
	public function getIndiceJulio23($ntitu) {
	    if (!isset($this->indiceJulio23)) {
	        $this->indiceJulio23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   80570.23 ,36130.15);
	    }
	    return $this->indiceJulio23;
	}
	
	public function getIndiceAgosto23($ntitu) {
	    if (!isset($this->indiceAgosto23)) {
	        $this->indiceAgosto23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    92131.70 ,42262.25);
	    }
	    return $this->indiceAgosto23;
	}
	
	public function getIndiceSeptiembre23($ntitu) {
	    if (!isset($this->indiceSeptiembre23)) {
	        $this->indiceSeptiembre23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    103372.83 ,47857.79);
	    }
	    return $this->indiceSeptiembre23;
	}
	
	
	public function getIndiceOctubre23($ntitu) {
	    if (!isset($this->indiceOctubre23)) {
	        $this->indiceOctubre23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    111746.10 ,51974.93);
	    }
	    return $this->indiceOctubre23;
	}
	
	public function getIndiceNoviembre23($ntitu) {
	    if (!isset($this->indiceNoviembre23)) {
	        $this->indiceNoviembre23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    126361.27 ,59886.86);
	    }
	    return $this->indiceNoviembre23;
	}
	
	public function getIndiceDiciembre23($ntitu) {
	    if (!isset($this->indiceDiciembre23)) {
	        $this->indiceDiciembre23 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    160452.53 ,77889.58);
	    }
	    return $this->indiceDiciembre23;
	}
	
	
	public function getIndiceEnero24($ntitu) {
	    if (!isset($this->indiceEnero24)) {
	        $this->indiceEnero24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    193146.66 ,94414.67);
	    }
	    return $this->indiceEnero24;
	}
	
	public function getIndiceFebrero24($ntitu) {
	    if (!isset($this->indiceFebrero24)) {
	        $this->indiceFebrero24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    223592.74 ,104482.59);
	    }
	    return $this->indiceFebrero24;
	}
	
	
	public function getIndiceMarzo24($ntitu) {
	    if (!isset($this->indiceMarzo24)) {
	        $this->indiceMarzo24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  250286.44 ,115873.35);
	    }
	    return $this->indiceMarzo24;
	}
	
	
	public function getIndiceAbril24($ntitu) {
	    if (!isset($this->indiceAbril24)) {
	        $this->indiceAbril24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,  268012.36, 120726.29);
	    }
	    return $this->indiceAbril24;
	}
	
	
	public function getIndiceMayo24($ntitu) {
	    if (!isset($this->indiceMayo24)) {
	        $this->indiceMayo24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   275518.08, 125235.49);
	    }
	    return $this->indiceMayo24;
	}
	
	public function getIndiceJunio24($ntitu) {
	    if (!isset($this->indiceJunio24)) {
	        $this->indiceJunio24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,   282578.89, 127287.79);
	    }
	    return $this->indiceJunio24;
	}
	
	public function getIndiceJulio24($ntitu) {
	    if (!isset($this->indiceJulio24)) {
	        $this->indiceJulio24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    291471.73, 131293.57);
	    }
	    return $this->indiceJulio24;
	}
	
	public function getIndiceAgosto24($ntitu) {
	    if (!isset($this->indiceAgosto24)) {
	        $this->indiceAgosto24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    304170.44, 136399.3);
	    }
	    return $this->indiceAgosto24;
	}
	
	public function getIndiceSeptiembre24($ntitu) {
	    if (!isset($this->indiceSeptiembre24)) {
	        $this->indiceSeptiembre24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    312174.7, 138744.31);
	    }
	    return $this->indiceSeptiembre24;
	}
	
	public function getIndiceOctubre24($ntitu) {
	    if (!isset($this->indiceOctubre24)) {
	        $this->indiceOctubre24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,     319283.60, 140653.57);
	    }
	    return $this->indiceOctubre24;
	}
	
	public function getIndiceNoviembre24($ntitu) {
	    if (!isset($this->indiceNoviembre24)) {
	        $this->indiceNoviembre24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,     324099.1,  142148.73);
	    }
	    return $this->indiceNoviembre24;
	}
	
	public function getIndiceDiciembre24($ntitu) {
	    if (!isset($this->indiceDiciembre24)) {
	        $this->indiceDiciembre24 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,     331532.43,  145408.96);
	    }
	    return $this->indiceDiciembre24;
	}
	
	public function getIndiceEnero25($ntitu) {
	    if (!isset($this->indiceEnero25)) {
	        $this->indiceEnero25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,     334535.80,  146726.23);
	    }
	    return $this->indiceEnero25;
	}
	
	public function getIndiceFebrero25($ntitu) {
	    if (!isset($this->indiceFebrero25)) {
	        $this->indiceFebrero25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    342370.04,  151491.17);
	    }
	    return $this->indiceFebrero25;
	}
	public function getIndiceMarzo25($ntitu) {
	    if (!isset($this->indiceMarzo25)) {
	        $this->indiceMarzo25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,    356073.46,  160393.45);
	    }
	    return $this->indiceMarzo25;
	}
	public function getIndiceAbril25($ntitu) {
	    if (!isset($this->indiceAbril25)) {
	        $this->indiceAbril25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,     359243.83,  162553.77);
	    }
	    return $this->indiceAbril25;
	}
	public function getIndiceMayo25($ntitu) {
	    if (!isset($this->indiceMayo25)) {
	        $this->indiceMayo25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,      359425.13,  161903.21);
	    }
	    return $this->indiceMayo25;
	}
	public function getIndiceJulio25($ntitu) {
	    if (!isset($this->indiceJulio25)) {
	        $this->indiceJulio25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,      371958.87,   166797.7);
	    }
	    return $this->indiceJulio25;
	}
	
	public function getVivienda() {
		if (!isset($this->vivienda)) {
			$this->vivienda = new Vivienda($this->getNRub());
		}
		return $this->vivienda;
	}
	
	public function getVivienda2() {
		if (!isset($this->vivienda2)) {
			$this->vivienda2 = new Vivienda2($this->getNRub());
		}
		return $this->vivienda2;
	}
	
	public function getAlquila_subalquila() {
		if (!isset($this->alquila_sub)) {
			$this->alquila_sub = new Alquila_sub($this->getNRub());
		}
		return $this->alquila_sub;
	}
	
	public function getComercio() {
		if (!isset($this->comercio)) {
			$this->comercio = new Comercio($this->getNRub());
		}
		return $this->comercio;
	}
	
	public function getPropietario() {
		if (!isset($this->propietario)) {
			$this->propietario = new Propietario($this->getNRub());
		}
		return $this->propietario;
	}
	
	public function getPrc() {
		if (!isset($this->prc)) {
			$this->prc = new Prc($this->getNRub());
		}
		return $this->prc;
	}
	
		
	public function getDiscapacidad() {
		if (!isset($this->discapacidad)) {
			$this->discapacidad = new Discapacidad($this->getNRub());
		}
		return $this->discapacidad;
	}
	

	
	public function getDetalleIngresos() {
		if (!isset($this->detalleIngresos)) {
			$this->detalleIngresos = new detalleIngresos($this->getNRub());
		}
		return $this->detalleIngresos;
	}
	
	
	public function getObservaciones() {
		if (!isset($this->observaciones)) {
			$this->observaciones = new ObservacionesRub($this->getNRub());
		}
		return $this->observaciones;
	}
	
	public function getBienes() {
		if (!isset($this->bienes)) {
			$this->bienes = new Bienes($this->getNRub());
		}
		return $this->bienes;
	}
	
	public function getotro_Bienes() {
		if (!isset($this->otrosbienes)) {
			$this->otrosbienes = new otro_Bienes($this->getNRub());
		}
		return $this->otrosbienes;
	}
	
	public function getBienes_obs() {
		if (!isset($this->bienes_obs)) {
			$this->bienes_obs = new Bienes_obs($this->getNRub());
		}
		return $this->bienes_obs;
	}
	

}
 /**
* Clase que modela el los pagos de los Integrantes del Hogar Rub
*/
class DomicilioHogarRub extends FRDO {
	public function __construct($nrorub, $arrAliasNames=null) {
		parent::__construct("domicilio_rub $nrorub");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
 * Clasificacion del Hogar RUB
 */
class Clasirub extends FRDO {
	public function __construct($nrorub, $arrAliasNames=null) {
		parent::__construct("_clasirub $nrorub");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
 * Indices de la linea de pobreza / indigencia para el Hogar CP
 */
class IndiceCP extends FRDO {
	public function __construct($nTitu, $idx1, $idx2, $idx3, $idx4, $idx5,$idx6, $arrAliasNames=null) {
		parent::__construct("_INDICES_CP $nTitu, $idx1, $idx2, $idx3, $idx4, $idx5, $idx6");
		$this->autoPopulate($arrAliasNames);
	}
}

/**
 * Indices de la linea de pobreza / indigencia para el Hogar RUB
 */
class IndiceRub extends FRDO {
	public function __construct($nRub, $idx1, $idx2, $idx3,$idx4, $arrAliasNames=null) {
		parent::__construct("_INDICES_RUB $nRub, $idx1, $idx2, $idx3, $idx4");
		$this->autoPopulate($arrAliasNames);
	}
}

class Vivienda extends FRDO {
	public function __construct($rub, $arrAliasNames=null) {
		parent::__construct("tipo_viviendaN $rub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Vivienda2 extends FRDO {
	public function __construct($rub, $arrAliasNames=null) {
		parent::__construct("vivienda2 $rub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Alquila_sub extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("alquila $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Comercio extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("_tipo_comercio $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Propietario extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("propietarior $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Discapacidad extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("_buscar_discapacidad_ficha $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class ObservacionesRub extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("_obser_comp_rub $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class ObservacionesCP extends FRDO {
	public function __construct($nTitular, $arrAliasNames=null) {
		parent::__construct("_obser_comp $nTitular");
		$this->autoPopulate($arrAliasNames);
	}
}

class Bienes extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("bienes_hogar_rub $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class otro_Bienes extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("otros_bienes_hogar_rub $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class Bienes_obs extends FRDO {
	public function __construct($nRub, $arrAliasNames=null) {
		parent::__construct("Bienes_rub_obser $nRub");
		$this->autoPopulate($arrAliasNames);
	}
}

class detalleIngresos extends FRDO {
	public function __construct($nroRub, $arrAliasNames=null) {
		parent::__construct("_buscar_HOGAR_RUB_fichaN $nroRub");
		$this->autoPopulate($arrAliasNames);
	}
}



class Prc extends FRDO {
	public function __construct($nroRub, $arrAliasNames=null) {
		parent::__construct("prctotal1 $nroRub");
		$this->autoPopulate($arrAliasNames);
	}
}


?>