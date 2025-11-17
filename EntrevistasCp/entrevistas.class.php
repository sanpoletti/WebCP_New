<?php

/*
 echo "<pre>";
 echo "🔍 DEBUG Parámetros:\n en GENERAR.PHP";
 echo "ntitu = " . ($_GET['ntitu'] ?? 'NO LLEGA') . "\n";
 echo "nroDoc = " . ($_GET['NroDoc'] ?? 'NO LLEGA') . "\n";
 echo "idhogar = " . ($_GET['idhogar'] ?? 'NO LLEGA') . "\n";
 echo "nrorub = " . ($_GET['nrorub'] ?? 'NO LLEGA') . "\n";
 echo "numeroConsulta = " . ($_GET['numeroConsulta'] ?? 'NO LLEGA') . "\n";
 echo "idpersonahogar = " . ($_GET['idpersonahogar'] ?? 'NO LLEGA') . "\n";
 echo "nombre = " . ($_GET['nombre'] ?? 'NO LLEGA') . "\n";
 echo "username = " . ($_GET['username'] ?? 'NO LLEGA') . "\n";
 echo "</pre>";
 
 */
header("Content-Type: text/html;charset=utf-8");

include 'rdo.class.php';

define('ADODB_DIR', __DIR__ . '/adodb5');
require_once(ADODB_DIR . '/adodb.inc.php');

function escapeSqlUnicode($str) {
    $str = trim($str);
    $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
    $str = str_replace("'", "''", $str);
    return "N'" . $str . "'";
}


class SHDO {
    
    const CACHE_TIMEOUT = 60;
    protected $rs = [];         // Datos crudos del resultset
    protected $data = [];       // Objetos DataObject para acceso por propiedad
    protected $conn = null;     // Conexión SQL Server interna
    
    public function __construct($spString, $timeout = 60) {
        $this->connect();
        $this->rs = $this->ejecutarSP($spString);
        $this->autoPopulate();
    }
    
    protected function connect() {
        $this->conn = sqlsrv_connect("10.22.0.253", [
            "Database" => "titularDebo",
            "Uid" => "ficharub",
            "PWD" => "colo",
            "CharacterSet" => "UTF-8"
        ]);
        
        if (!$this->conn) {
            die("Error conectando a la base de datos: " . print_r(sqlsrv_errors(), true));
        }
    }
    
    protected function ejecutarSP($spString) {
        $tsql = "SET NOCOUNT ON; EXEC $spString";
        $stmt = sqlsrv_query($this->conn, $tsql);
        
        if (!$stmt) {
            die("Error ejecutando el SP: $spString<br>" . print_r(sqlsrv_errors(), true));
        }
        
        $resultados = [];
        if (!sqlsrv_has_rows($stmt)) {
            //echo("⚠️ El SP '$spString' no devolvió filas.");
        }
        
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $resultados[] = $row;
        }
        
        sqlsrv_free_stmt($stmt);
        return $resultados;
    }
    
    public function autoPopulate($arrAliasNames = null) {
        $this->data = [];
        
        // Recorremos el resultset
        foreach ($this->rs as $row) {
            $rdo = new RowDataObject();
            
            // Cargamos los datos al objeto
            $rdo->populate($row);
            
            // Si se usan alias para los campos, aplicalos (opcional)
            if ($arrAliasNames) {
                foreach ($arrAliasNames as $alias => $original) {
                    if (isset($row[$original])) {
                        $rdo->$alias = $row[$original];
                    }
                }
            }
            
            // Guardamos el RDO como entrada individual
            $this->data[] = $rdo;
        }
    }
 
    public function getUniqueData() {
        return (!empty($this->data) && is_array($this->data)) ? $this->data[0] : null;
    }
    
    
    public function getData() {
        return $this->data;
    }
    
    public function isEmpty() {
        return empty($this->data);
    }
    
    public function __destruct() {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }
}



class Personas extends SHDO {
    public function __construct($ntitu, $nrodoc, $apellido ,$nroRub,$timeout = self::CACHE_TIMEOUT) {
        parent::__construct(Personas::getSPString($ntitu, $nrodoc, $apellido,$nroRub), $timeout);
        $this->autoPopulate();
    }
    
    private static function getSPString($ntitu, $nrodoc, $apellido,$nroRub) {
        
        
        //DEBUG: mostrar valores que llegan
       /*    echo "<pre>DEBUG Turnos - Parámetros recibidos:\n";
           echo "ntitular: " . var_export($ntitu, true) . "\n";
           echo "nrodoc: " . var_export($nrodoc, true) . "\n";
           echo "apellido: " . var_export($apellido, true) . "\n";
           echo "nroRub: " . var_export($nroRub, true) . "\n";
         echo "</pre>";  
        */ 
        // ✅ Ejecutar solo UNA VEZ el constructor padre
        
        
        
        if (empty($ntitu) && empty($apellido)&& empty($nroRub)) {//buscamos por nrodoc

            return "_personas 0, $nrodoc, '',''";
        
        } else if(empty($nrodoc) && empty($apellido)&& empty($nroRub)) {//buscamos por ntitu
            
            //echo "<pre>DEBUG Personas - Parámetros recibidos:\n";
           
            return "_personas $ntitu,0, '','' ";
            
            
        }else if(empty($nrodoc) && empty($apellido)&& empty($ntitu)) {//buscamos por nroRub
            
            /*
             echo "<pre>DEBUG Personas - Parámetros recibidos:\n";
             echo "ntitular: " . var_export($ntitu, true) . "\n";
             echo "nrodoc: " . var_export($nrodoc, true) . "\n";
             echo "apellido: " . var_export($apellido, true) . "\n";
             echo "nroRub: " . var_export($nroRub, true) . "\n";
             echo "Procedimiento: _personas 0,0,'','$nroRub' \n";
             
             echo "</pre>";
             */
            return "_personas 0,0, '',$nroRub ";
        }
        
        
        
        else if (empty($nrodoc) && empty($ntitu)&& empty($nroRub)) {
            // Escapamos comillas simples dentro del apellido para evitar errores de SQL
            $apellido = str_replace("'", "''", $apellido);          
            return "_personas 0 ,0, '" . $apellido . "',''";
        }
    }
}



class Turnos extends SHDO {   
    public function __construct($idhogar, $tipo='entre', $arrAliasNames=null) {
        
        //DEBUG: mostrar valores que llegan
     /*   echo "<pre>DEBUG Turnos - Parámetros recibidos:\n";
        echo "idhogar: " . var_export($idhogar, true) . "\n";
        echo "tipo: " . var_export($tipo, true) . "\n";
        echo "IdUbicacion (ubicación): " . self::getIdUbicacion($tipo) . "\n";
        echo "Procedimiento: _turnos $idhogar, " . self::getIdUbicacion($tipo) . "\n";
        echo "</pre>";  */
        // ✅ Ejecutar solo UNA VEZ el constructor padre
        parent::__construct("_turnos $idhogar, " . self::getIdUbicacion($tipo));     
        
        //$sql = sprintf("EXEC _turnos %d, %d", (int)$idhogar, (int)self::getIdUbicacion($tipo));
        //parent::__construct($sql);
        
        
        
        // ✅ Ejecutar solo UNA VEZ la carga automática
        $this->autoPopulate($arrAliasNames);
    }  
    public function getIdUbicacion($tipo) {
        if ( $tipo=='inte' ) {
            // Baja Integrante
            return '12';
        } else if ( $tipo=='lega' ) {
            // Legales
            return '16';
        } else if ( $tipo=='inteSalguero' ) {
            // Baja integrantes Salguero
            return '40';         
        } else if ( $tipo=='entre' ){
            // Entrevistas
            return '2';
        }
        else if ( $tipo=='decla' ){
            // Cambio titu- Declaraciones
            return '8';
        }
    }
}

class Entrevistas extends SHDO{
    private $hogarCP;
    private $eet;
    private $hogarRub;
    private $nRub;
    private $ntitu;
    private $tipo;
    private $idhogar;
    private $numeroConsulta;
    private $observacionsegui;
    private $observacionseguiHistorico;
    private $idpersonahogar;

    
    public function __construct($ntitular, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar) {

        /*
        echo "<pre>🛠️ DEBUG ENTREVISTAS\n";
        echo "ntitular: $ntitular\n";
        echo "idhogar: $idhogar\n";
        echo "nrorub: $nrorub\n";
        echo "numeroConsulta: $numeroConsulta\n";
        echo "idpersonahogar: $idpersonahogar\n";
        echo "</pre>";
        */
        
        $this->nRub  = $nrorub;
        $this->ntitu = $ntitular;
        $this->idhogar = $idhogar;
        $this->numeroConsulta = $numeroConsulta;
        $this->idpersonahogar = $idpersonahogar;
        //echo "<p>🛠️ Parametros ingresados a Entrevistas: ntitu=$ntitular, nrorub=$nrorub, idhogar=$idhogar</p>";
       
        $this->hogarCP = new HogarCP($ntitular, $idhogar, $numeroConsulta, $idpersonahogar);
        
        $this->hogarCP->cargarDatos(); 
        
        if (empty($ntitular)) {
            throw new Exception("ERROR: No se puede ejecutar _calificacion sin ntitular.");
        }
        
        $spString = "_calificacion $ntitular";
        parent::__construct($spString);
        $this->autoPopulate();
       // $this->cargarHogarCP($ntitular, $nrorub, $idhogar, $numeroConsulta);
    }
    
    public function getHogarRub() {
        if (!isset($this->hogarRub)) {
            $this->hogarRub = new HogarRub($this->nRub);
        }
        return $this->hogarRub;
    }
    
    public function getObservacionessegui() {
        if (!isset($this->observacionsegui)) {
            $this->observacionsegui = new Observacionesseguimiento($this->getIdHogar());
        }
        return $this->observacionsegui;
    }

    public function getHogarCP() {
        if (!isset($this->hogarCP)) {
            $this->hogarCP = new HogarCP($this->ntitu,$this->idhogar,$this->numeroConsulta,$this->idpersonahogar);
        }
        return $this->hogarCP;
    }
    
    public function getHistoricoObserva() {
        if (!isset($this->histoObservacion)) {
            $this->histoObservacion = new HistoObservacion($this->getNTitular());
        }
        return $this->histoObservacion;
    }
    
    public function getNTitular() {
        return $this->ntitu;
    }
    
    public function getIdHogar(){
        return $this->idhogar;
    }
    
    public function getIdPersonaHogar(){
        return $this->idpersonahogar;
    }
    
    
    public function getNumeroConsulta(){
        return $this->numeroConsulta;
    }
    
    public function getObservacionesseguiHistorico() {
        if (!isset($this->observacionseguiHistorico)) {
            $this->observacionseguiHistorico = new ObservacionesseguimientoHistorico($this->getNTitular());
        }
        return $this->observacionseguiHistorico;
    }
    
    public function getObservacionesseguiHistoricoInt() {
        if (!isset($this->observacionseguiHistoricoInt)) {
            $this->observacionseguiHistoricoInt = new ObservacionesseguimientoHistoricoInt($this->getNTitular());
        }
        return $this->observacionseguiHistoricoInt;
    }
    /**
     * Obtiene el valor de una propiedad de la calificacion de CP
     * param $propName Nombre de la propiedad (nombre del campo)
     */
    public function getCalifCPProperty($propName) {
        return $this->califCP->getProperty($propName);
    }
    

    public function getInscripcionEET() {
        if (!isset($this->eet)) {
            $this->eet = new INSCRIPCIONEET($this->getNTitular());
        }
        return $this->eet;
    }
    
    public function __toString() {
        $result = '<hr/>SeguimientoHogar Object<br/><ul>';
        $result .= '<li>HogarCP: ' . $this->getHogarCP();
        $result .= '<li>Titular HogarCP: ' . $this->getHogarCP()->getTitular();
        $result .= '<li>Domicilio CP: ' . $this->getHogarCP()->getDomicilio();
        $result .= '<li>Tarjetas: ' . $this->getHogarCP()->getTarjetas();
        $result .= '<li>Pagos: ' . $this->getHogarCP()->getPagos();
        $result .= '<li>HogarRub: ' . $this->getHogarRub();
        
        $result .= '</ul><hr/>';
        return $result;
    }
    private function cargarHogarCP($ntitular, $nrorub, $idhogar, $numeroConsulta,$idpersonahogar = null) {
       // $this->hogarCP = new HogarCP($ntitular, $nrorub, $idhogar, $numeroConsulta);
        $this->hogarCP = new HogarCP(
            $ntitular,
            $idhogar,          // acá va el idhogar real
            $numeroConsulta,
            $idpersonahogar ?? null
            );
    }
    
}
 
class HogarCP extends SHDO {
    public $idhogar;
    private $observacionsegui;
    private $nTitular;
    public $idpersonahogar;
    public $numeroConsulta;
    private $titular;
    private $domicilio;
    private $domicilioH;
    private $tarjetas;
    //private $turnos;	// Array ['entre'=>Turno Object, 'inte'=>Turno Object]
    private $pagos;
    private $observaciones;
    private $observa_seguimiento;
    private $observacionseguiI;
    private $doc_faltante;
    private $salud;
    private $educacion;
    private $histopagos;
    private $ultimo_pago;
    private $declarados_eet;
    private $adeuda;
    private $Observacionesrub;
    private $composicionFamiliar;
    private $situacionEntrevista;
    private $insertarComposicionFamiliar;
    private $turnos = [];
    
    
    public function __construct($ntitu, $idhogar, $numeroConsulta, $idpersonahogar, $arrAliasNames = null) {
        
    
        
        
        
        /*     echo "<pre>🧩 DEBUG HogarCP:
            ntitu: $ntitu
            idhogar (esperado): $idhogar
            numeroConsulta: $numeroConsulta
            idpersonahogar: $idpersonahogar
            </pre>";*/     
        parent::__construct("_buscohogar $idhogar");     
        $this->nTitular = $ntitu;
        $this->idhogar = $idhogar;
        $this->idpersonahogar = $idpersonahogar;
        $this->numeroConsulta = $numeroConsulta;        
        $this->autoPopulate($arrAliasNames);    
        $this->titular = new TitularCP($ntitu, $this);
    }
      
    public function cargarSoloTurnos($tipo = 'entre') {
        $this->turnos[$tipo] = $this->getTurnos($tipo);
    }
    
    public function cargarSoloComposicionFamiliar() {
        $this->composicionFamiliar = $this->getComposicionFamiliar();
    }
    
    public function cargarSoloTitular() {
        $this->titular = $this->buscarTitular();
    }
    
    
    
    
    public function buscarTitular() {
        return $this->titular;
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
    
    public function getIdHogar(){
        return $this->idhogar;
    }
    
    public function getIdPersonaHogar(){
        return $this->idpersonahogar;
    }
    
    public function getNumeroConsulta() {
        return $this->numeroConsulta;
    }
    
    public function getId() {//id de la tabla composicion familair
        return $this->id;
    }
   
    public function getEvaluacionEntrevista() {
        if (!isset($this->evaluacionEntrevista)) {
            
            $this->evaluacionEntrevista = new EvaluacionEntrevista($this->getNumeroConsulta());
        }
        
        return $this->evaluacionEntrevista;
        
    }
    /**
     * Devuelve el domicilio del Hogar CP
     */
    public function getDomicilio() {
        if (!isset($this->domicilio)) {
            $this->domicilio = new DomicilioHogarCP($this->getIdHogar());
        }
        return $this->domicilio;
    }
    
    
    public function getObservacionessegui() {
        if (!isset($this->observacionsegui)) {
            $this->observacionsegui = new Observacionesseguimiento($this->getIdHogar());
        }
        return $this->observacionsegui;
    }
    /**
     * Devuelve el Historicos domicilio del Hogar CP
     */
    public function getDomicilioHistorico() {
        if (!isset($this->domicilioH)) {
            $this->domicilioH = new DomicilioHogarCPHistorico($this->getNTitular());
        }
        return $this->domicilioH;
    }
    
    
    public function getSituacionEntrevista() {
        if (!isset($this->situacionEntrevista)) {
            
            $this->situacionEntrevista = new SituacionEntrevista($this->getNumeroConsulta());
        }
        
        return $this->situacionEntrevista;
        
    }
    public function cargarDatos() {
       // $this->titular = $this->buscarTitular();
      //  $this->turnos['entre'] = $this->getTurnos('entre');
      //  $this->turnos['inte'] = $this->getTurnos('inte');
       // $this->composicionFamiliar = $this->getComposicionFamiliar(); // 🚀 ESTA ES LA CLAVE
    }
    public function getTitularProperty($propName) {
        $titular = $this->getTitular();
        
        // Validamos que exista el objeto y que tenga data
        if (!$titular || empty($titular->getData())) {
            error_log("⚠️ Titular vacío o no encontrado en getTitularProperty para: {$propName}");
            return ''; // o null
        }
        
        // Si no existe la propiedad, devolvés vacío también
        $value = $titular->getProperty($propName);
        return $value ?? '';
    }
    
    
    /**
     * Devuelve las tarjetas de los integrantes y el titular del Hogar CP
     */
    public function getTarjetas() {
        if (!isset($this->tarjetas)) {
            $this->tarjetas = new Tarjetas($this->getIdHogar());
        }
        return $this->tarjetas;
    }
    
    /**
     * Devuelve el turno del Hogar CP
     */
	public function getTurnos($tipo = 'entre') {
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    //error_log("⚠️ getTurnos llamado desde: " . $trace[1]['function']);

    $idHogar = $this->getIdHogar();
   // error_log("🔍 getTurnos() → idHogar = " . var_export($idHogar, true) . " | tipo = " . $tipo);

    if (!isset($this->turnos[$tipo])) {
        try {
            $this->turnos[$tipo] = new Turnos($idHogar, $tipo);
            //error_log("✅ getTurnos() terminó OK ($tipo)");
        } catch (Throwable $e) {
            error_log("❌ Error en getTurnos($tipo): " . $e->getMessage());
        }
    }

    return $this->turnos[$tipo] ?? null;
}


    /** inmuebles sintys*/
    
    public function getInmuebleSintys() {
        if (!isset($this->inmuebleSintys)) {
            $this->inmuebleSintys = new InmueblesSintys($idpersonahogar);
        }
        return $this->inmuebleSintys;
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
    
    public function getAdeuda() {
        if (!isset($this->adeuda)) {
            $this->adeuda = new adeuda_educ($this->getIdHogar()); // CORREGIDO: antes usabas getNTitular()
        }
        return $this->adeuda;
    }
    
    public function getObservacionesHogar() {
        if (!isset($this->observaciones)) {
            $this->observaciones = new ObservacionesCP($this->getIdHogar());
        }
        return $this->observaciones;
    }
    
    public function getObservacionesseguimiento() {
        if (!isset($this->observa_seguimiento)) {
            $this->observa_seguimiento = new Observaciones_seguimiento($this->getIdHogar());
        }
        return $this->observa_seguimiento;
    }
    
    public function getObservacionesseguiI() {
        if (!isset($this->observacionseguiI)) {
            $this->observacionseguiI = new ObservacionesseguimientoI($this->getIdHogar());
        }
        return $this->observacionseguiI;
    }
    
    public function getUltimopago() {
        if (!isset($this->ultimo_pago)) {
            $this->ultimo_pago = new ultimopago($this->getNTitular());
        }
        return $this->ultimo_pago;
    }
    
    public function getSalud() {
        if (!isset($this->salud)) {
            $this->salud = new salud($this->getNTitular());
        }
        return $this->salud;
    }
    
    public function getDeclaradosEET() {
        if (!isset($this->declarados_eet)) {
            $this->declarados_eet = new EET_DECLARADOS($this->getNTitular());
        }
        return $this->declarados_eet;
    }
    
    public function getEducacion() {
        if (!isset($this->educacion)) {
            $this->educacion = new educacion($this->getNTitular());
        }
        return $this->educacion;
    }
    
    public function getHistoricoPagos() {
        if (!isset($this->histopagos)) {
            $this->histopagos = new histopagos($this->getNTitular());
        }
        return $this->histopagos;
    }
    
    /**
     * Clase que modela al titular del Hogar CP
     * Tiene las propiedades de un integrante del hogar y
     * las propiedades de calificacion del titular
     */
}

class TitularCP extends SHDO {
    public function __construct($ntitu, $hogarCP, $arrAliasNames=null) {
        // Agrego properties del titular del hogar ciudadania
        parent::__construct("_calificacion $ntitu");
        $this->autoPopulate($arrAliasNames);
        $this->mergeUniqueData($this->getTitularData($hogarCP));
    }
    public function getProperty($propName) {
        if (empty($this->data) || !is_array($this->data)) {
            throw new Exception("No hay datos disponibles en TitularCP.");
        }
        
        $firstRow = $this->data[0] ?? null;
        
        if (!$firstRow || !method_exists($firstRow, 'getProperties')) {
            throw new Exception("No se puede obtener propiedades del RowDataObject.");
        }
        
        return $firstRow->getProperty($propName);
    }

    /**
     * Recorre los integrantes del hogar y selecciona el titular.
     * En caso de no encontrarlo devuelve NULL.
     */
    public function getTitularData($hogarCP) {
        if (method_exists($hogarCP, 'cargarDatos')) {
            $hogarCP->cargarDatos(); // <- asegurate que se poblen los campos necesarios
            
            $hogarCP->cargarSoloTitular();
            $hogarCP->cargarSoloTurnos('inte');
        }
        return $hogarCP;
    }
    
    /**
     * Fusiona los datos únicos del titular del hogar.
     */
    private function mergeUniqueData($titularData) {
        if (is_array($titularData)) {
            foreach ($titularData as $key => $value) {
                $this->setProperty($key, $value);
            }
        }
    }
    
    public function __toString() {
        $result = '<TitularCP Object> ';
        if (!empty($this->data)) {
            return $result . $this->data[0];
        }
        return $result;
    }
}


class ObservacionesseguimientoHistorico extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("observacionesSeguimientoHistorico $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}



class Observacionesseguimiento extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("observa_seguimiento $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}


/**
 * Clase para buscar las indices cp marzo 2011
 */
/*
class IndiceCP extends SHDO {
    public function __construct($nTitu, $idx1, $idx2, $idx3, $idx4, $idx5, $arrAliasNames=null) {

        parent::__construct("_INDICES_CP $nTitu, $idx1, $idx2, $idx3, $idx4, $idx5");
        $this->autoPopulate($arrAliasNames);
    }
}
*/

class IndiceCP extends SHDO {
    public function __construct($nTitu, $idx1, $idx2, $idx3, $idx4, $idx5,$idx6, $arrAliasNames = null) {
       // echo "<p>📦 DEBUG SP IndiceCP: ntitu:$nTitu,.idx1=$idx1.idx2=.$idx2.idx3=.$idx3.idx4=.$idx4.idx5=.$idx5</p>";
       // die();
        parent::__construct("_INDICES_CP $nTitu, $idx1, $idx2, $idx3, $idx4, $idx5,$idx6");
       // echo "<p>📦 DEBUG SP IndiceCP: ntitu:$nTitu.idx1=$idx1.idx2=.$idx2.idx3=.$idx3.idx4=.$idx4.idx5=.$idx5.idx6=.$idx6</p>";
       // die();
        $this->autoPopulate($arrAliasNames);
    }
    
    /**
     * Retorna el RowDataObject único (si existe) - helper
     */
    public function getUniqueData() {
        // 'data' es protected en SHDO, por herencia podemos accederlo
        if (!empty($this->data) && is_array($this->data) && isset($this->data[0])) {
            return $this->data[0];
        }
        return null;
    }
    
    /**
     * Proxy para obtener una propiedad; si el RowDataObject existe,
     * le delegamos la llamada a getProperty().
     */
    public function getProperty($name) {
        // Si la clase padre ya tiene un método getProperty, usarlo
        if (method_exists(get_parent_class($this), 'getProperty')) {
            return parent::getProperty($name);
        }
        
        $ud = $this->getUniqueData();
        if ($ud && method_exists($ud, 'getProperty')) {
            return $ud->getProperty($name);
        }
        
        // fallback: buscar en el array rs (si existe y es array asociativo)
        if (isset($this->rs) && is_array($this->rs) && isset($this->rs[0]) && array_key_exists($name, $this->rs[0])) {
            return $this->rs[0][$name];
        }
        
        return null;
    }
}




class Ingresos extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("_buscar_HOGAR_RUB_fichaN $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}
class InmueblesSintys extends SHDO {
    public function __construct($idpersonahogar,$idtipo, $arrAliasNames=null) {
        parent::__construct("inmueble_sintys $idpersonahogar,$idtipo");
        $this->autoPopulate($arrAliasNames);
    }
    
}
class Clasirub extends SHDO {
    public function __construct($nrorub, $arrAliasNames=null) {
        parent::__construct("_clasirub $nrorub");
        $this->autoPopulate($arrAliasNames);
    }
}
class ObservacionesseguimientoI extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("observa_seguimientoI $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}
class HistoricoAdeudaEducacion extends SHDO {
    public function __construct($idhogar,$idcatego, $arrAliasNames=null) {
        parent::__construct("historicoadeudaEducacion $idhogar, $idcatego");
        $this->autoPopulate($arrAliasNames);
    }
}
class HistoricoDetalleLiquidacion extends SHDO {
    public function __construct($idhogar,$idcatego, $arrAliasNames=null) {
        parent::__construct("detalle_liquidacion $idhogar, $idcatego");
        $this->autoPopulate($arrAliasNames);
    }
}
class DomicilioHogarRub extends SHDO {
    public function __construct($nrorub, $arrAliasNames=null) {
        parent::__construct("domicilio_rub $nrorub");
        $this->autoPopulate($arrAliasNames);
    }
}


class Pagos extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("_pagos_GRAL $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}

/**
 * Clase que modela las observaciones sintys hogar cp
 */
class ObservacionesCP extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("_obser_comp $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
}

/**
 * Clase que modela las observaciones del RUB
 */
class ObservacionesRub extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
  ;
        parent::__construct("_obser_comp_rub $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}

/**
 * Clase que modela el observaciones del hogar cp
 */
class Observaciones_seguimiento extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("observa_seguimiento $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
}
class HistoObservacion extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("_obser_comp_historico $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}

/**
 * Clase que modela la doc que adeuda de educacion el hogar cp
 */
class adeuda_educ extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        //echo "<p>📦 DEBUG SP adeuda_educacion: idhogar=$idhogar</p>";
        
        parent::__construct("adeuda_educacion $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}


/**
 * Clase que modela el ultimo pago del hogar cp
 */
class ultimopago extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("ultimo_pago $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}

class ObservacionesseguimientoHistoricoInt extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("observacionesSeguimientoHistoricoInt $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}

/**
 * Clase que modela los integrantes con beneficio en el plan Estudiar es Trabajar
 */
class INSCRIPCIONEET extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("INSCRIPTOSEET $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}

class EET_DECLARADOS extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("eet_declarados $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}
/**
 *clase que modela la salud de los integrantes de Cp
 */

class salud extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("_salud $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}

/**
 *clase que modela la Educacion de los integrantes de Cp
 */
class educacion extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("_Educacion $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}


/**
 *clase que modela el historial de pagos de Cp
 */
class histopagos extends SHDO {
    public function __construct($nTitular, $arrAliasNames=null) {
        parent::__construct("_pagos $nTitular");
        $this->autoPopulate($arrAliasNames);
    }
    
}	

class Tarjetas extends SHDO {
    public function __construct($ntitular, $arrAliasNames=null) {
        parent::__construct("_tarjetas_GRAL $ntitular");
        $this->autoPopulate($arrAliasNames);
    }
}

/**
 * Clase que modela el Domicilio del Hogar CP
 */
class DomicilioHogarCP extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("_buscoDomiCp $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
}

/**
 * Clase que modela el HIstorico Domicilio del Hogar CP
 */
class DomicilioHogarCPHistorico extends SHDO {
    public function __construct($idhogar, $arrAliasNames=null) {
        parent::__construct("Historico_domi_cp $idhogar");
        $this->autoPopulate($arrAliasNames);
    }
    
}

class SituacionEntrevista extends SHDO {
    public function __construct($numeroConsulta, $arrAliasNames=null) {
        
        parent::__construct("sh_situacion_entrevista $numeroConsulta");
        $this->autoPopulate($arrAliasNames);
    }
    
}


/*class AbmSituacionEntrevista extends SHDO {



public function __construct($identrevista,$composFamiliar,$numeroConsulta,$sitEconomica,$sitHabitacional,$sitSalud,$sitEducacion,$idpersonahogar,$mantienecompo,$uname,$observacion,$completa ,$evaluada,$arrAliasNames=null) {
parent::__construct("_abm_situacion_entrevista $identrevista, N'$composFamiliar', $numeroConsulta,N'$sitEconomica',N'$sitHabitacional',N'$sitSalud',N'$sitEducacion','$idpersonahogar','$mantienecompo','$uname','$observacion','$completa','$evaluada'");

$this->autoPopulate($arrAliasNames);
}

}
*/

class AbmSituacionEntrevista extends SHDO {
    
    
    public function __construct($identrevista,$composFamiliar,$numeroConsulta,$sitEconomica,$sitHabitacional,$sitSalud,$sitEducacion,$idpersonahogar,$mantienecompo,$uname,$observacion,$completa ,$evaluada,$arrAliasNames=null) {
        parent::__construct("_abm_situacion_entrevista $identrevista, N'$composFamiliar', $numeroConsulta,N'$sitEconomica',N'$sitHabitacional',N'$sitSalud',N'$sitEducacion','$idpersonahogar','$mantienecompo','$uname','$observacion','$completa','$evaluada'");
        
        $this->autoPopulate($arrAliasNames);
    }
    
}




class AbmDocumentacionEntrevista extends SHDO {
    
    
    public function __construct($idDocuentacion,$numeroConsulta,$nombreDoc,$usuario,$arrAliasNames=null) {
        parent::__construct("_abm_documentacion_entrevista $idDocuentacion,  $numeroConsulta,'$nombreDoc','$usuario'");
        
        $this->autoPopulate($arrAliasNames);
    }
    
}


class AbmEntrevista extends SHDO {
    
    
    public function __construct($idDocuentacion,$numeroConsulta,$nombreDoc,$usuario,$arrAliasNames=null) {
        parent::__construct("_abm__entrevista $idDocuentacion,  $numeroConsulta,'$nombreDoc','$usuario'");
        
        $this->autoPopulate($arrAliasNames);
    }
    
}


class DeleteEntrevista extends SHDO {
    
    
    public function __construct($numeroConsulta,$usuario) {
        parent::__construct("_delete_entrevista $numeroConsulta,'$usuario'");
        
        $this->autoPopulate($arrAliasNames);
    }
    
}

class EvaluacionEntrevista extends SHDO {
    public function __construct($numeroConsulta, $arrAliasNames=null) {
        
        parent::__construct("sh_evaluacion_entrevista $numeroConsulta");
        $this->autoPopulate($arrAliasNames);
    }
    
}
class AbmEvaluacionEntrevista extends SHDO {
    
    
    public function __construct($idEntrevista,$numeroConsulta,$uname,$observacion,$arrAliasNames=null) {
        parent::__construct("_abm_evaluacion_entrevista $idEntrevista,$numeroConsulta,'$uname','$observacion'");
        $this->autoPopulate($arrAliasNames);
    }
    /*	private function getUsuario() {
     require_once $_SERVER["DOCUMENT_ROOT"].'/login/phpUserClass/access.class.php';
     $user = new flexibleAccess();
     return "'" . $user->get_property('username') . "'";
     }*/
    
}

class Bienes extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("bienes_hogar_rub $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}

class otro_Bienes extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("otros_bienes_hogar_rub $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}

class Bienes_obs extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("Bienes_rub_obser $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}
class Vivienda2 extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("vivienda2 $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}


class IndiceRub extends SHDO {
    public function __construct($nRub, $idx1, $idx2, $idx3,$idx4, $arrAliasNames=null) {
        //DEBUG: mostrar valores que llegan
         /*
        echo "<pre>DEBUG IndiceRub Parámetros recibidos:\n";
           echo "nroRub: " . var_export($nRub, true) . "\n";
             echo "idx1: " . var_export($idx1, true) . "\n";
             echo "idx2: " . var_export($idx2, true) . "\n";
             echo "idx3: " . var_export($idx3, true) . "\n";
             echo "idx4: " . var_export($idx4, true) . "\n";
        
         echo "</pre>";  
        // ✅ Ejecutar solo UNA VEZ el constructor padre
        */
        parent::__construct("_INDICES_RUB $nRub, $idx1, $idx2, $idx3, $idx4");
        $this->autoPopulate($arrAliasNames);
    }
}
class detalleIngresos extends SHDO {
    public function __construct($nroRub, $arrAliasNames=null) {
        parent::__construct("_buscar_HOGAR_RUB_fichaN $nroRub");
        $this->autoPopulate($arrAliasNames);
    }
}

class HogarRub extends SHDO {
    public $DomiRub;
    private $observaciones;
    private $nroRub;
    private $indiceRub;
    private $indiceCP11;
    private $indiceDic13;
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
    private $indiceAgosto25;
    
    private $salud;
    private $vivienda;
    private $ingresos;
    private $detalleIngresos;
    private $clasirub;
    private $alquila_sub;
    private $comercio;
    private $propietario;
    private $discapacidad;
    private $bienes;
    private $otrosbienes;
    private $bienes_obs;
    private $vivienda2;
    private $clasificacion;
    private $prc;
    private $domiRub;
    private $idHogar; // nueva propiedad
    
    
    
    public function __construct($nroRub) {
        // Si no vino por parámetro, tratamos de levantarlo de sesión
        if (empty($nroRub) && isset($_SESSION['nrorub'])) {
            $nroRub = $_SESSION['nrorub'];  // corregido: sin comillas, nombre correcto
        }
        /*
        echo "<pre>🧩 DEBUG Hogar RUB:\n";
        echo "NroRUb (esperado): $nroRub\n</pre>";
        */
        $this->nroRub = $nroRub;
        $this->idHogar =$nroRub;
        
        parent::__construct("_buscar_HOGAR_RUB_fichaN $nroRub");
        $this->autoPopulate(); // no uses $arrAliasNames si no está definido
    }
    public function getIdHogar() {
        return $this->idHogar;
    }
       
    public function getNRub() {
        return $this->nroRub;
    }
    
    public function getIngresos() {
        if (!isset($this->ingresos)) {
            $this->ingresos = new Ingresos($this->getNRub());
        }
        return $this->ingresos;
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
    
    public function getDetalleIngresos() {
        if (!isset($this->detalleIngresos)) {
            $this->detalleIngresos = new detalleIngresos($this->getNRub());
        }
        return $this->detalleIngresos;
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
    
    
    
    
    
    public function getDomicilioRub() {
        if (!isset($this->DomiRub)) {
            $this->DomiRub = new DomicilioHogarRub($this->getNRub());
        }
        return $this->DomiRub;
    }
    
    
    public function getObservaciones() {
        if (!isset($this->observaciones)) {
            $this->observaciones = new ObservacionesRub($this->getNRub());
        }
        return $this->observaciones;
    }
    
    public function getIndiceCP11($ntitu) {
        if (!isset($this->indiceCP11)) {
            $this->indiceCP11 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,411.49,187.79);
        }
        return $this->indiceCP11;
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
    public function getIndiceDic13($ntitu) {
        
        
        if (!isset($this->indiceDic13)) {
            $this->indiceDic13 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,577.23,254.78);
        }
        return $this->indiceDic13;
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
            //var_dump($this->indiceJulio25->getUniqueData());
        }
        return $this->indiceJulio25;
    }
    
    public function getIndiceAgosto25($ntitu) {
        if (!isset($this->indiceAgosto25)) {
            $this->indiceAgosto25 = new IndiceCP($ntitu, 2.0, 1.50, 1.75,1.25,       375656.97,   168456.04);
        }
        return $this->indiceAgosto25;
    }
}
?>

