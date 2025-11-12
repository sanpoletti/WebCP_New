<?php
// test_sql.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "10.22.0.253";
$connectionOptions = [
    "Database" => "titularDebo",
    "Uid" => "ficharub",
    "PWD" => "colo",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn) {
    echo "<h2 style='color: green;'>✅ Conexión exitosa  a SQL Server</h2>";
    $sql = "SELECT TOP 5 * FROM sysobjects";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        echo "<p style='color: orange;'>Conexión OK, pero error al consultar:</p>";
        print_r(sqlsrv_errors());
    } else {
        echo "<p>Ejemplo de datos obtenidos:</p><pre>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            print
