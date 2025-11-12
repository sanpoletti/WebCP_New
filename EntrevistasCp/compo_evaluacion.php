<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");

// Variables de sesión
$ntitu   = $_SESSION['ntitu']  ?? '';
$idhogar = $_SESSION['idhogar'] ?? '';
$nrorub  = $_SESSION['nrorub'] ?? '';

// Validar datos recibidos
if (!isset($_GET['numeroConsulta']) || empty($_GET['numeroConsulta'])) {
    die("❌ No tiene permisos para acceder a este módulo (Compo Evaluación).");
}

$numeroConsulta = $_GET['numeroConsulta'];
$usuario        = strtoupper($_GET['uname'] ?? '');
$idEntrevista   = $_GET['idEntrevista'] ?? 0;
$observacion    = strtoupper($_GET['observacion'] ?? '');
$idpersonahogar = $_GET['idpersonahogar'] ?? '';

// Evitar timeout
set_time_limit(0);

// Cargar clases
require_once 'entrevistas.class.php';

// Guardar la evaluación en base de datos
$hdoc = new AbmEvaluacionEntrevista($idEntrevista, $numeroConsulta, $usuario, $observacion);

// 🔹 No redirigimos a una nueva URL (que puede perder estado),
// sino que recargamos la ventana padre tal como está.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardar Evaluación</title>
    <script>
        window.onload = function() {
            if (window.opener && !window.opener.closed) {
                // ✅ Refresca la ventana padre (mantiene parámetros y contexto)
                window.opener.location.reload();
            }
            // ✅ Cierra el popup
            window.close();
        };
    </script>
</head>
<body style="background:#f8f8f8; font-family: Arial, sans-serif; text-align:center; padding-top:50px;">
    <h2>Guardando evaluación...</h2>
    <p>Esta ventana se cerrará automáticamente.</p>
</body>
</html>



