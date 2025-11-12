<?php
// Configuración
$debug = false;
$cacheEnabled = false; // Ya no necesario, removemos cache viejo.
$edad = isset($_GET["edad"]) ? intval($_GET["edad"]) : 0;
$sexo = isset($_GET["sexo"]) ? $_GET["sexo"] : '';

if (!$edad || empty($sexo)) {
    http_response_code(400);
    die("Parámetros inválidos");
}

// Obtener la inicial del sexo
$sexoLetter = strtoupper(substr($sexo, 0, 1));

// Armar query según sexo
if ($sexoLetter == 'M') {
    $sql = "SELECT masculino AS CANASTA FROM TITULAR..VALORES WHERE edad = ?";
} elseif ($sexoLetter == 'F') {
    $sql = "SELECT femenino AS CANASTA FROM TITULAR..VALORES WHERE edad = ?";
} else {
    die("Sexo inválido");
}

// Conexión SQL Server con sqlsrv
$serverName = "10.22.0.253"; 
$connectionOptions = [
    "Database" => "titularDebo",
    "Uid" => "ficharub",
    "PWD" => "colo",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("❌ Error de conexión: " . print_r(sqlsrv_errors(), true));
}

// Ejecutar consulta con parámetros seguros
$params = [$edad];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("❌ Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

// Devolver resultado
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo substr($row['CANASTA'], 0, 4); // Devuelve el coeficiente
} else {
    echo 0; // Si no encuentra datos, devuelve 0
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
