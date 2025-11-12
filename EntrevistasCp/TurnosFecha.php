<?php
class TurnosFecha {
    private $serverName = "10.22.0.253";
    private $connectionOptions = [
        "Database" => "titularDebo",
        "Uid" => "ficharub",
        "PWD" => "colo",
        "CharacterSet" => "UTF-8"
    ];
    private $conn = null;
    private $result = [];
    
    public function __construct($fecha, $tipo = 'entre') {
        $this->connect();
        $this->loadTurnos($fecha, $tipo);
    }
    
    private function connect() {
        $this->conn = sqlsrv_connect($this->serverName, $this->connectionOptions);
        if ($this->conn === false) {
            $errors = sqlsrv_errors();
            die("Error conectando a la base de datos: " . print_r($errors, true));
        }
    }
    
    private function loadTurnos($fecha, $tipo) {
        $idUbicacion = $this->getIdUbicacion($tipo);
        
        // SP con parámetros, mejor usar prepare + execute
        $tsql = "{CALL _turnos (?, ?, ?)}";
        $params = [0, $idUbicacion, $fecha];
        
        $stmt = sqlsrv_prepare($this->conn, $tsql, $params);
        if (!$stmt) {
            $errors = sqlsrv_errors();
            die("Error preparando la consulta: " . print_r($errors, true));
        }
        
        if (!sqlsrv_execute($stmt)) {
            $errors = sqlsrv_errors();
            die("Error ejecutando la consulta: " . print_r($errors, true));
        }
        
        $this->result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $this->result[] = $row;
        }
        
        sqlsrv_free_stmt($stmt);
    }
    public function getResult() {
        return $this->result;
    }
    private function getIdUbicacion($tipo) {
        switch ($tipo) {
            case 'inte': return 12;
            case 'lega': return 16;
            case 'inteSalguero': return 40;
            case 'decla': return 8;
            case 'entre':
            default:
                return 2;
        }
    }
    
    public function getData() {
        return $this->result;
    }
    
    public function __destruct() {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }
}
?>