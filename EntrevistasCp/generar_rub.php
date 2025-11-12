<?php
// 🔒 Arranque seguro de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 👤 Usuario logueado o desconocido
$usuario  = $_SESSION['nombre'] ?? 'Desconocido';
$uname    = $_SESSION['username'] ?? 'Desconocido';

// ✅ Variables GET
if (isset($_GET['ntitulares']) && !empty($_GET['ntitulares']) && isset($_GET['rubs']) && !empty($_GET['rubs'])) {
    $ntitulares     = $_GET['ntitulares'];
    $rubs           = $_GET['rubs'];
    $idpersonahogar = $_GET['idpersonahogar'] ?? '';
    $numeroConsulta = $_GET['numeroConsulta'] ?? '';
    $nrorub         = $_GET['rubs'] ?? '';
    $idhogar        = $_GET['idhogar'] ?? '';
} elseif (isset($_GET['ntitu']) && !empty($_GET['ntitu']) && isset($_GET['rubs'])) {
    $ntitu          = $_GET['ntitu'];
    $nrorub         = $_GET['rubs'];
    $idpersonahogar = $_GET['idpersonahogar'] ?? '';
    $numeroConsulta = $_GET['numeroConsulta'] ?? '';
    $idhogar        = $_GET['idhogar'] ?? '';
} elseif (isset($_GET['rubs']) && !empty($_GET['rubs'])) {
    $nrorub = $_GET['rubs'];
    echo "nrorub = " . ($_GET['rubs'] ?? 'NO LLEGA') . "\n";
} else {
    die("⚠️ No tiene permisos para generar el modelo.");
}

// ⏳ Timeout indefinido
set_time_limit(0);

$content = '';
$nrorub  = $_GET['rubs'];

// Render de contenido dinámico
ob_start();
include "gen_modelo_rub.php";
ob_end_clean();

ob_start();
include "gen_pdf_rub.php";
$content .= ob_get_clean();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="ISO-8859-1" />
    <title>Ficha RUB</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f9f9fb;
            margin: 0;
            padding: 0;
        }

        /* 🔹 Encabezado */
/* 🔹 Encabezado */
.header {
    background: linear-gradient(90deg, #003366, #0055a5);
    color: #fff;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-left .logo {
    height: 28px; /* 🔸 Logo más chico */
    width: auto;
    vertical-align: middle;
    opacity: 0.95;
}

.header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.header-info {
    font-size: 14px;
    text-align: right;
    line-height: 1.3;
}

        .header h2 {
            margin: 0;
            font-size: 22px;
        }
        .header-info {
            font-size: 14px;
            text-align: right;
        }

        /* 🔹 Secciones */
        .section {
            margin: 20px auto;
            padding: 15px;
            width: 95%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .section h3 {
            margin-top: 0;
            color: #003366;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 5px;
        }

        /* 🔹 Tablas */
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }
        th {
            background: #0055a5;
            color: white;
            padding: 8px;
            text-align: center;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: center;
        }
        tr:nth-child(even) {
            background: #f4f6f9;
        }
    </style>
</head>
<body>
<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
$fechaActual = date('d/m/Y H:i');
?>

<!-- 🔹 Encabezado Moderno -->
<!-- 🔹 Encabezado Moderno -->
<div class="header">
    <div class="header-left">
        <img src="./img/BA.png" alt="BA Logo" class="logo">
        <h2>Ficha Hogar RUB</h2>
    </div>
    <div class="header-info">
        <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
        <p><strong>Fecha:</strong> <?= $fechaActual ?></p>
    </div>
</div>


<!-- 🔹 Datos RUB -->
<div class="section">
    <h3>Datos RUB</h3>
    <p><strong>RUB Nº:</strong> <?= htmlspecialchars($nrorub) ?></p>
    <p><strong>Hogar ID:</strong> <?= htmlspecialchars($idhogar) ?></p>
    <p><strong>Consulta:</strong> <?= htmlspecialchars($numeroConsulta) ?></p>
</div>

<!-- 🔹 Contenido Generado -->
<div class="section">
    <?= $content ?>
</div>

</body>
</html>


