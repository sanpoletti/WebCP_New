<?php
echo "<p>Test directo con sqlsrv_query()</p>";

$serverName = "10.22.0.253";
$connectionOptions = [
    "Database" => "titularDebo",
    "UID" => "ficharub",
    "PWD" => "colo",
    "CharacterSet" => "UTF-8"
];

// Establecer conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$fecha = '30-06-2025';
$sql = "EXEC _turnos 0, 2, '$fecha'";

echo "<p>Ejecutando: $sql</p>";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("<p>Error ejecutando el SP:</p>" . print_r(sqlsrv_errors(), true));
}

$hayDatos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hayDatos = true;
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

if (!$hayDatos) {
    echo "<p>⚠️ El SP no devolvió datos.</p>";
}

sqlsrv_close($conn);
?>
