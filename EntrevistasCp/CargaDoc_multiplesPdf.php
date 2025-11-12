<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
include 'funciones.php';

// --- Variables de entorno ---
$params = [
    'numeroconsulta' => $_GET['numeroconsulta'] ?? $_SESSION['numeroconsulta'] ?? '',
    'composFamiliar' => $_GET['composFamiliar'] ?? '',
    'sitEconomica' => $_GET['sitEconomica'] ?? '',
    'sitHabitacional' => $_GET['sitHabitacional'] ?? '',
    'observacion' => $_GET['observacion'] ?? '',
    'sitEducacion' => $_GET['sitEducacion'] ?? '',
    'idpersonahogar' => $_GET['idpersonahogar'] ?? $_SESSION['idpersonahogar'] ?? '',
    'sitSalud' => $_GET['sitSalud'] ?? '',
    'nrorub' => $_GET['nrorub'] ?? $_SESSION['nrorub'] ?? '',
    'idhogar' => $_GET['idhogar'] ?? $_SESSION['idhogar'] ?? '',
    'ntitu' => $_GET['ntitu'] ?? $_SESSION['ntitu'] ?? '',
    'identrevista' => $_GET['identrevista'] ?? '',
    'uname' => $_GET['uname'] ?? $_SESSION['uname'] ?? '',
    'hora' => $_GET['hora'] ?? $_SESSION['hora'] ?? '',
    'sec' => $_GET['sec'] ?? $_SESSION['sec'] ?? ''
];
$queryString = http_build_query($params);

$numeroConsulta  = $params['numeroconsulta'];
$uname = $params['uname'];
$sec  = $params['sec'];
$fechaCarga = date('d-m-Y');
$carpetaDestino = __DIR__ . "/uploads/doc_adjuntada/";
$permitidos = ['application/pdf', 'image/jpeg', 'image/jpg'];

// --- PROCESO DE SUBIDA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['archivo']) || empty($_FILES['archivo']['name'][0])) {
        echo '<!DOCTYPE html><html><body><script>
            window.opener?.postMessage("cerrarModal", "*");
            window.close();
        </script></body></html>';
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);

    // Verificar si la carpeta existe o se puede crear
    if (!is_dir($carpetaDestino)) {
        if (!@mkdir($carpetaDestino, 0777, true)) {
            die("❌ No se pudo crear la carpeta destino: $carpetaDestino");
        }
    }

    // Forzar permisos 777 para asegurar escritura
    @chmod($carpetaDestino, 0777);

    foreach ($_FILES['archivo']['name'] as $i => $origName) {
        $tmp = $_FILES['archivo']['tmp_name'][$i];
        $error = $_FILES['archivo']['error'][$i];
        if ($error !== UPLOAD_ERR_OK) continue;

        $mime = $finfo->file($tmp) ?: ($_FILES['archivo']['type'][$i] ?? '');
        if (!in_array($mime, $permitidos)) continue;

        $safe = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $origName);
        $destino = "{$carpetaDestino}{$numeroConsulta}-{$sec}-{$safe}";

        if (@move_uploaded_file($tmp, $destino)) {
            @chmod($destino, 0644); // Lectura global
            // --- Forzar permisos de lectura para IIS al nuevo archivo ---
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                try {
                    // Da permisos de lectura/escritura a IUSR, IIS_IUSRS y al AppPool actual
                    $cmds = [
                        'icacls "' . $destino . '" /grant IUSR:(R)',
                        'icacls "' . $destino . '" /grant "IIS_IUSRS":(R)',
                        'icacls "' . $destino . '" /grant "IIS AppPool\\DGPOLA":(R)',
                    ];
                    foreach ($cmds as $c) {
                        @exec($c);
                    }
                } catch (Exception $e) {
                    error_log("Error ajustando permisos NTFS: " . $e->getMessage());
                }
            }
            
            
            // Registrar en la base si existe la clase
            if (file_exists(__DIR__ . '/entrevistas.class.php')) {
                include_once __DIR__ . '/entrevistas.class.php';
                if (class_exists('AbmDocumentacionEntrevista')) {
                    try {
                        new AbmDocumentacionEntrevista(0, $numeroConsulta, $safe . '-' . $fechaCarga, $uname);
                    } catch (Exception $e) {
                        error_log("Error al registrar documento: " . $e->getMessage());
                    }
                }
            }
        } else {
            error_log("No se pudo mover el archivo a: $destino");
        }
    }

    echo '<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Subida exitosa</title>
<style>
    body { font-family: Arial, sans-serif; text-align:center; padding-top:60px; background:#f8f9fb; color:#333; }
    .msg { background:#d1e7dd; color:#0f5132; border:1px solid #badbcc; border-radius:8px; display:inline-block; padding:15px 25px; box-shadow:0 2px 8px rgba(0,0,0,0.1);}
</style></head>
<body>
<div class="msg">✅ Archivo subido correctamente. Cerrando ventana...</div>
<script>
setTimeout(function(){
    if (window.opener && !window.opener.closed) window.opener.location.reload();
    window.close();
}, 1200);
</script>
</body>
</html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Adjuntar documentación</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f8f9fb;
            margin: 0;
            padding: 30px;
        }
        .container {
            background: #fff;
            border-radius: 12px;
            max-width: 600px;
            margin: 0 auto;
            padding: 25px 35px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: #888;
        }
        .close-btn:hover {
            color: #000;
        }
        h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 10px;
        }
        h4 { color: #555; margin-bottom: 20px; }
        input[type="file"] { display: block; margin-bottom: 20px; width: 100%; }
        input[type="submit"], button {
            background-color: #0078D7;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            margin-right: 10px;
        }
        input[type="submit"]:hover, button:hover { background-color: #005fa3; }
        button { background-color: #6c757d; }
        button:hover { background-color: #5a6268; }
        small { display: block; margin-top: 10px; color: #777; }
    </style>
</head>
<body>

<div class="container">
    <span class="close-btn" onclick="cerrarVentana()">×</span>
    <h3>📎 Adjuntar documentación escaneada (.pdf / .jpg)</h3>
    <h4>
        <strong>Integrante:</strong> <?= htmlspecialchars($_GET['ape'] ?? '') ?>, <?= htmlspecialchars($_GET['nom'] ?? '') ?><br>
        <strong>Secuencia:</strong> <?= htmlspecialchars($_GET['sec'] ?? '') ?>
    </h4>

    <form id="abmFormUsuario" action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . '?' . $queryString) ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="archivo[]" multiple required>
        <input type="hidden" name="sec" value="<?= htmlspecialchars($_GET['sec'] ?? $sec) ?>">
        <input type="hidden" name="tipo" value="<?= htmlspecialchars($_GET['tipo'] ?? '') ?>">
        <input type="hidden" name="hora" value="<?= htmlspecialchars($_GET['hora'] ?? '') ?>">

        <input type="submit" id="submitButton" value="Subir archivo">
        <button type="button" id="cancelarBtn">Cancelar</button>
        <small>Puede seleccionar más de un archivo antes de enviar.</small>
    </form>
</div>

<script>
let formSubmitted = false;

// Botón "Cancelar"
document.getElementById('cancelarBtn').addEventListener('click', function() {
    cerrarVentana();
});

// Cerrar ventana (botón o "X")
function cerrarVentana() {
    if (window.parent) {
        window.parent.postMessage('cerrarModal', '*');
    } else if (window.opener && !window.opener.closed) {
        window.opener.location.reload();
    }
    window.close();
}

// Desactivar advertencia del navegador
window.onbeforeunload = null;

// Mostrar "Subiendo..." al enviar
$('#abmFormUsuario').on('submit', function() {
    formSubmitted = true;
    $('#submitButton')
        .attr('disabled', 'disabled')
        .val('Subiendo...');
});
</script>

</body>
</html>





