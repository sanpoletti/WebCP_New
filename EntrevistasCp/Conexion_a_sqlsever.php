<?php
require_once 'adodb5/adodb.inc.php';
require_once 'adodb5/drivers/adodb-pdo_sqlsrv.inc.php'; // 🔧 importante

$db = ADONewConnection('pdo_sqlsrv'); 
$db->debug = true;

$dsn = "sqlsrv:Server=10.22.0.253;Database=titularDebo";

if (!$db->Connect($dsn, 'ficharub', 'colo')) {
    die("Fallo conexión: " . $db->ErrorMsg());
}

$rs = $db->Execute("SELECT TOP 1 * FROM personas"); // Cambiá por una tabla real

if (!$rs) {
    echo "Error en consulta: " . $db->ErrorMsg();
} else {
    print_r($rs->fields);
}
?>

