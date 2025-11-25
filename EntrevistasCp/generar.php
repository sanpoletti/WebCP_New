<?php
require_once __DIR__ . '/../login/phpUserClass/access.class.php';
$user = new flexibleAccess();

if (!$user->is_loaded()) {
    header("Location: /DGPOLA/login/index.php");
    exit;
}


// 🔒 Arranque seguro de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 👤 Usuario logueado o desconocido
$name     = $_SESSION['username'] ?? 'Desconocido';
$usuario  = $_SESSION['nombre'] ?? 'Desconocido';
$uname    = $_SESSION['username'] ?? 'Desconocido';
$hora     = $_SESSION['hora'] ?? 'Desconocido';
$evaluada = $_SESSION['evaluada'] ?? 'Desconocido';
$numeroConsulta = $_SESSION['numeroConsulta'] ?? 0;

// ✅ Variables de sesión desde parámetros
$_SESSION['ntitu']          = $_GET['ntitu'] ?? null;
$_SESSION['idhogar']        = $_GET['idhogar'] ?? null;
$_SESSION['nrorub']         = $_GET['nrorub'] ?? ($_SESSION['nrorub'] ?? null);
$_SESSION['numeroConsulta'] = $_GET['numeroConsulta'] ?? null;
$_SESSION['idpersonahogar'] = $_GET['idpersonahogar'] ?? null;
$identrevista               = $_SESSION['identrevista'] ?? 0;
$_SESSION['uname']          = $_GET['uname'] ?? null;


/*
 echo "<pre>";
 echo "🔍 DEBUG Parámetros:\n en GENERAR.PHP";
 echo "ntitu = " . ($_GET['ntitu'] ?? 'NO LLEGA') . "\n";
 echo "nroDoc = " . ($_GET['NroDoc'] ?? 'NO LLEGA') . "\n";
 echo "idhogar = " . ($_GET['idhogar'] ?? 'NO LLEGA') . "\n";
 echo "nrorub = " . ($_GET['nrorub'] ?? 'NO LLEGA') . "\n";
 echo "numeroConsulta = " . ($_GET['numeroConsulta'] ?? 'NO LLEGA') . "\n";
 echo "idpersonahogar = " . ($_GET['idpersonahogar'] ?? 'NO LLEGA') . "\n";
 echo "nombre = " . ($_GET['nombre'] ?? 'NO LLEGA') . "\n";
 echo "username = " . ($_GET['username'] ?? 'NO LLEGA') . "\n";
 echo "</pre>";
 
 */


// 📦 Seteo de variables de entrada
if (isset($_GET['ntitulares']) && isset($_GET['rubs']) && isset($_GET['numeroConsulta']) && isset($_GET['idhogar'])) {
    // 🧾 Modo múltiples fichas
    $ntitulares      = $_GET['ntitulares'];
    $rubs            = $_GET['rubs'];
    $numeroConsulta  = $_GET['numeroConsulta'];
    $idpersonahogar  = $_GET['idpersonahogar'];
    $horas           = $_GET['horas'];
    $idhogares       = $_GET['idhogar'];
} elseif (isset($_GET['ntitu']) && isset($_GET['nrorub'])) {
    // 🧾 Modo ficha única
    $ntitu           = $_GET['ntitu'];
    $hora            = $_SESSION['hora'] ?? 'Desconocido';
    $evaluada        = $_SESSION['evaluada'] ?? 'Desconocido';
    $numeroConsulta  = $_SESSION['numeroConsulta'] ?? 0;
    $idpersonahogar  = $_GET['idpersonahogar'];
    $idhogar         = $_GET['idhogar'];
    $nrorub          = $_GET['nrorub'] ?? ($_SESSION['nrorub'] ?? null);
} else {
    die("⚠️ Error al visualizar el hogar: Faltan parámetros.");
}

set_time_limit(0);
$content = '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="ISO-8859-1" />
    <title>Ficha General</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

    <!-- 🎨 Estilos mejorados -->
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f9f9fb;
            margin: 0;
            padding: 0;
        }

        /* 🔹 Encabezado */
        .header {
            background: linear-gradient(90deg, #003366, #0055a5);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        
        /* 🔹 Encabezado */
.header {
    background: linear-gradient(90deg, #003366, #0055a5);
    color: #fff;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-left .logo {
    height: 40px;
}
.header h2 {
    margin: 0;
    font-size: 22px;
}
.header-info {
    font-size: 14px;
    text-align: right;
}
 /* 🔹 Encabezado */
.header {
    background: linear-gradient(90deg, #003366, #0055a5);
    color: #fff;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-left .logo {
    height: 40px;
}
.header h2 {
    margin: 0;
    font-size: 22px;
}
.header-info {
    font-size: 14px;
    text-align: right;
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
        <h2>Ficha del Hogar</h2>
    </div>
    <div class="header-info">
        <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
        <p><strong>Fecha:</strong> <?= $fechaActual ?></p>
    </div>
</div>


<!-- 🔹 Datos Generales -->
<div class="section">
    <h3>Datos Generales</h3>
    <p><strong>CUIL:</strong> <?= htmlspecialchars($uname) ?></p>
    
</div>

<?php
if (isset($ntitulares)) {
    for ($i = 0; $i < count($ntitulares); $i++) {
        $ntitu          = $ntitulares[$i];
        $nrorub         = $rubs[$i];
        $numeroConsulta = $numeroConsulta[$i];
        $idpersonahogar = $idpersonahogar[$i];
        $hora           = $horas[$i];
        $idhogar        = $idhogares[$i];
        
        ob_start();
        include "gen_modelo.php";
        ob_end_clean();

        ob_start();
        include "gen_pdf.php";
        echo '<div style="page-break-after: always"></div>';
        $content .= ob_get_clean();
    }
} else {
    ob_start();
    include "gen_modelo.php";
    ob_end_clean();

    ob_start();
    include "gen_pdf_new.php";
    $content .= ob_get_clean();
}

echo $content;
?>

</body>
</html>