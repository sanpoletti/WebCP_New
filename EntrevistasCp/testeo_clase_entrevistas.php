<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h3>Turnos para la fecha y tipo </h3>";
require_once 'entrevistas.class.php';  // Asegurate que carga las clases necesarias

$fechaTest = '2025-06-17';  // O la fecha que quieras
$tipoTest = 'entre';        // O 'inte', 'lega', etc.

$turnosFecha = new TurnosFecha($fechaTest, $tipoTest);

$data = $turnosFecha->getData();

echo "<h3>Turnos para la fecha $fechaTest y tipo $tipoTest</h3>";

if (empty($data)) {
    echo "No se encontraron turnos para esa fecha y tipo.";
} else {
    echo "<table border='1' cellpadding='5'><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Fecha</th><th>Hora</th></tr>";
    foreach ($data as $turno) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($turno->getProperty('apellido')) . "</td>";
        echo "<td>" . htmlspecialchars($turno->getProperty('nombre')) . "</td>";
        echo "<td>" . htmlspecialchars($turno->getProperty('nro_doc')) . "</td>";
        echo "<td>" . htmlspecialchars($turno->getProperty('fecha')) . "</td>";
        echo "<td>" . htmlspecialchars($turno->getProperty('hora')) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
