
<?php
/*
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

*/



// Detecta si estás en tu PC local
$isLocal = (
    $_SERVER['SERVER_NAME'] === 'localhost' ||
    $_SERVER['SERVER_NAME'] === '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], 'local') !== false
    );

// BASE_URL: URL base del proyecto
// Local → /DGPOLA
// Producción (IIS) → ''
define('BASE_URL', $isLocal ? '/DGPOLA' : '');

// APP_ROOT: ruta física ABSOLUTA correcta SIEMPRE
define(
'APP_ROOT',
rtrim($_SERVER['DOCUMENT_ROOT'] . BASE_URL, DIRECTORY_SEPARATOR)
);
