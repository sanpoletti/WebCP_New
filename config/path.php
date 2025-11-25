<?php

// Ruta física absoluta del proyecto (siempre funciona)
define('APP_ROOT', realpath(__DIR__ . '/..'));

// Detecta si estás en tu PC local o en producción
$isLocal = (
    $_SERVER['SERVER_NAME'] === 'localhost' ||
    $_SERVER['SERVER_NAME'] === '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], 'local') !== false
);

// URL base del proyecto
// PC local → /DGPOLA
// Servidor → ''
define('BASE_URL', $isLocal ? '/DGPOLA' : '');

