<?php
require_once 'phpUserClass/access.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = new flexibleAccess();

// Logout
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $user->logout('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    exit;
}

// Si no está logueado
if (!$user->is_loaded()) {
    $errorMsg = '';
    // Siempre definimos las variables
    $uname = '';
    $pwd = '';
    $remember = false;
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Leer y limpiar entradas
    $uname = htmlspecialchars(trim(filter_input(INPUT_POST, 'uname', FILTER_UNSAFE_RAW)), ENT_QUOTES, 'UTF-8');
    $pwd = htmlspecialchars(trim(filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW)), ENT_QUOTES, 'UTF-8');
    $remember = !empty($_POST['remember']);

    if (!$uname || !$pwd) {
        echo '<p style="color:red;">Por favor completa usuario y contraseña.</p>';
    } else {
        if (!$user->login($uname, $pwd, true, $remember)) {
            echo '<p style="color:red;">Usuario y/o contraseña inválidos.</p>';
        } else {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

    

    // Formulario con mensaje de error si corresponde
    echo '<h2>Iniciar Sesión</h2>';
    if ($errorMsg) {
        echo '<p style="color:red; font-weight:bold;">' . htmlspecialchars($errorMsg) . '</p>';
    }
    echo '
    <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
        <ol>
            <li>
                <label for="uname">Usuario:</label><br>
                <input id="uname" name="uname" type="text" value="' . (isset($uname) ? htmlspecialchars($uname) : '') . '" required autofocus />
            </li>
            <li>
                <label for="pwd">Contraseña:</label><br>
                <input id="pwd" name="pwd" type="password" required />
            </li>
            <li>
                <label>
                    <input id="remember" name="remember" type="checkbox" ' . (isset($remember) && $remember ? 'checked' : '') . ' />
                    Recordar Usuario
                </label>
            </li>
            <li>
                <button type="submit">Acceder</button>
            </li>
        </ol>
    </form>';

} else {

    $uname = $_SESSION['username'] ?? 'Desconocido';
    $nombre = $_SESSION['nombre'] ?? 'Desconocido';

    echo '<p>Hola, <strong>' . htmlspecialchars($nombre) . '</strong> | <a href="' . $_SERVER['PHP_SELF'] . '?logout=1">Cerrar sesión</a></p>';
    echo '<h2>Acceso a Programas</h2><ul>';

    $enlaces = [
        'simplerub' => '/EntrevistasCp/consultaHogarRub.php|Consulta Ficha RUB',
        'seguimiento' => '/EntrevistasCp/consultaHogar.php|Seguimiento Hogares',
        'canasta' => '/EntrevistasCp/canasta.php|Cálculo de Canasta Familiar',
        'admin' => '/login/admin.php|Administración de Usuarios',
        'reportes_Ticket' => '/reportes_Ticket/power.php|Reporte',
        'entrevistas' => '/EntrevistasCp/index.php|Informes Entrevistas',
        'revision_entrevistas' => '/EntrevistasCp/revisionEntrevista.php|Revision Entrevistas',
        'revision' => '/test_Desarrollo_Informes_entrevistas/index.php|Revisión',
        'reporte' => '/reportes/power.php|Reportes',
     
    ];

    foreach ($enlaces as $permiso => $rutaTexto) {
        if ($user->tienePermiso($permiso)) {
            list($url, $texto) = explode('|', $rutaTexto);
            echo '<li><a target="_blank" href="http://' . $_SERVER['HTTP_HOST'] . $url . '">' . htmlspecialchars($texto) . '</a></li>';
        }
    }

    echo '</ul>';
}
?>
