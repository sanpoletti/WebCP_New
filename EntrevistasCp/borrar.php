<?php
session_start();

// --- Validaciones básicas ---
if (!isset($_GET['archivo'])) {
    die("Archivo no especificado.");
}

$archivo = $_GET['archivo'];

// --- Prefijo esperado (para evitar borrar archivos ajenos) ---
$numeroConsulta = $_SESSION['numeroConsulta'] ?? '';
$sec = $_SESSION['sec'] ?? '';

$prefijoPermitido = $numeroConsulta . "-" . $sec . "-";

// --- Validamos que el archivo realmente pertenece al integrante ---
if (strpos($archivo, $prefijoPermitido) !== 0) {
    die("No está permitido borrar este archivo.");
}

// --- Ruta segura ---
$directorio = __DIR__ . "/uploads/doc_adjuntada/";
$rutaCompleta = realpath($directorio . $archivo);

// Verificamos que exista físicamente
if (!$rutaCompleta || !file_exists($rutaCompleta)) {
    die("El archivo no existe.");
}

// Aseguramos que NO pueda salir del directorio
if (strpos($rutaCompleta, realpath($directorio)) !== 0) {
    die("Ruta inválida.");
}

// --- Intentamos eliminar ---
if (unlink($rutaCompleta)) {
    // Volvemos a la pantalla anterior
    $url = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $url");
    exit;
} else {
    die("No se pudo eliminar el archivo.");
}
?>


