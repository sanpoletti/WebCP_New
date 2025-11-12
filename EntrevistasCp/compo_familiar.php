<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
session_start();

// Variables de sesión
$ntitu        = $_SESSION['ntitu'] ?? '';
$idhogar      = $_SESSION['idhogar'] ?? 0;
$nrorub       = $_SESSION['nrorub'] ?? 0;
$uname        = $_SESSION['uname'] ?? '';
$hora         = $_SESSION['hora'] ?? 'Desconocido';
$identrevista = isset($_GET['identrevista']) ? intval($_GET['identrevista']) : 0;

// Validación de parámetros obligatorios
if (
    isset($_GET['numeroConsulta']) && !empty($_GET['numeroConsulta']) &&
    isset($_GET['identrevista'])
) {
    // Sanitización y normalización
    $numeroConsulta   = intval($_GET['numeroConsulta']);
    $nroDoc           = intval($_GET['nroDoc']);
    $composFamiliar   = mb_strtoupper(trim($_GET['composFamiliar'] ?? ''), 'UTF-8');
    $sitEconomica     = mb_strtoupper(trim($_GET['sitEconomica'] ?? ''), 'UTF-8');
    $sitHabitacional  = mb_strtoupper(trim($_GET['sitHabitacional'] ?? ''), 'UTF-8');
    $sitSalud         = mb_strtoupper(trim($_GET['sitSalud'] ?? ''), 'UTF-8');
    $sitEducacion     = mb_strtoupper(trim($_GET['sitEducacion'] ?? ''), 'UTF-8');
    $idpersonahogar   = intval($_GET['idpersonahogar'] ?? 0);
    $mantienecompo    = $_GET['mantienecompo'] ;
    $observacion      = mb_strtoupper(trim($_GET['observacion'] ?? ''), 'UTF-8');
    $completa         = $_GET['completa'];
    $evaluada         = $_SESSION['evaluada'] ?? '';
    $usuario          = trim($_GET['usuario'] ?? '');
    $hora             = $_SESSION['hora'] ?? 'Desconocido';

    // Limpieza de comillas
    $replaceQuotes = function($str) {
        return str_replace(['"', "'"], '', $str);
    };
    $composFamiliar  = $replaceQuotes($composFamiliar);
    $sitEconomica    = $replaceQuotes($sitEconomica);
    $sitHabitacional = $replaceQuotes($sitHabitacional);
    $sitSalud        = $replaceQuotes($sitSalud);
    $sitEducacion    = $replaceQuotes($sitEducacion);
    $observacion     = $replaceQuotes($observacion);
} else {
    echo "No tiene permisos para acceder a este módulo.";
    exit;
}

set_time_limit(0);

// Incluir clase y guardar datos
include 'entrevistas.class.php';
$hdoc = new AbmSituacionEntrevista(
    $identrevista,
    $composFamiliar,
    $numeroConsulta,
    $sitEconomica,
    $sitHabitacional,
    $sitSalud,
    $sitEducacion,
    $idpersonahogar,
    $mantienecompo,
    $usuario,
    $observacion,
    $completa,
    $evaluada
);

// Salida HTML con mensaje y redirección
ob_end_clean();
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardando…</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f9f9f9;
        }
        .mensaje {
            font-size: 1.5em;
            color: #333;
        }
        .spinner {
            margin: 20px auto;
            width: 50px;
            height: 50px;
            border: 6px solid #ccc;
            border-top: 6px solid #007bff;
            border-radius: 50%;
            animation: girar 1s linear infinite;
        }
        @keyframes girar {
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="mensaje">Guardando los datos, por favor espere…</div>
    <div class="spinner"></div>

    <script>
        // Redirige a la ventana padre si existe
        if (window.parent && !window.parent.closed) {
            let urlBase = window.parent.location.origin + window.parent.location.pathname;
            let params = '?idhogar=<?php echo urlencode($idhogar); ?>&nrorub=<?php echo urlencode($nrorub); ?>&identrevista=<?php echo urlencode($identrevista); ?>&ntitu=<?php echo urlencode($ntitu); ?>&idpersonahogar=<?php echo urlencode($idpersonahogar); ?>&numeroConsulta=<?php echo urlencode($numeroConsulta); ?>&NroDoc=<?php echo urlencode($nroDoc); ?>';
            
            setTimeout(() => {
                window.parent.location.href = urlBase + params;
                if (window.parent.cerrarModal) {
                    window.parent.cerrarModal();
                }
            }, 1500);
        }

        // Fallback: cerrar esta ventana si no hay modal
        setTimeout(function() {
            window.location.href = 'about:blank';
        }, 3000);
    </script>
</body>
</html>
<?php
exit;
