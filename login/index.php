<?php
require_once 'phpUserClass/access.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = new flexibleAccess();

// Logout
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $user->logout();
    header("Location: " . $_SERVER['PHP_SELF']);
    
    
    exit;
}

// Procesar login
$errorMsg = '';
if (!$user->is_loaded()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uname = $_POST['uname'] ?? '';
        $pwd = $_POST['pwd'] ?? '';
        $remember = !empty($_POST['remember']);
        if (!$user->login($uname, $pwd, true, $remember)) {
            $errorMsg = 'Usuario y/o contraseña inválido.';
        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Web DGPOLA</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue-700:#0b61a8;
      --blue-500:#0077b6;
      --card-bg: rgba(255,255,255,0.9);
      --muted:#6b7280;
    }

    *{box-sizing:border-box}
    html,body{height:100%;margin:0;padding:0;}

    body {
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      display:flex;
      align-items:center;
      justify-content:center;
      background: url('/DGPOLA/EntrevistasCp/img/fondo.png') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content:"";
      position:fixed;
      inset:0;
      background: rgba(255,255,255,0.6);
      backdrop-filter: blur(6px);
      z-index:0;
    }

    .login-wrap {
      position:relative;
      z-index:1;
      width:100%;
      max-width:950px;
      display:grid;
      grid-template-columns: 420px 1fr;
      gap:28px;
      align-items:center;
      background: rgba(255,255,255,0.4);
      border-radius:16px;
      padding:20px;
      box-shadow:0 8px 30px rgba(0,0,0,0.2);
    }

    .brand-card {
      background: rgba(255,255,255,0.85);
      border-radius:12px;
      padding:26px;
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      box-shadow: 0 6px 24px rgba(0,0,0,0.08);
      border:1px solid rgba(11,97,168,0.1);
    }

    .brand-card img.logo {
      width:110px;
      height:auto;
      margin-bottom:10px;
    }
    .brand-card h1 {
      margin:6px 0 6px;
      font-size:22px;
      color:var(--blue-700);
      letter-spacing:0.2px;
    }
    .brand-card p {
      margin:0;
      color:var(--muted);
      font-size:14px;
    }

    .panel {
      background: var(--card-bg);
      border-radius:12px;
      padding:26px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
      border:1px solid rgba(0,0,0,0.05);
    }

    .login-header {
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom:14px;
    }
    .login-header h2 {
      margin:0;
      font-size:18px;
      color:#083a71;
    }
    .login-sub {
      color:var(--muted);
      font-size:13px;
    }

    .field input[type="text"],
    .field input[type="password"]{
      width:100%;
      padding:12px 12px 12px 44px;
      border-radius:8px;
      border:1px solid #d1d5db;
      background:white;
      font-size:14px;
      outline:none;
      transition: box-shadow .15s, border-color .15s;
    }
    .field input:focus{
      border-color: var(--blue-500);
      box-shadow: 0 6px 18px rgba(11,97,168,0.08);
    }

    .btn-primary {
      display:inline-block;
      padding:11px 18px;
      border-radius:8px;
      background: linear-gradient(180deg,var(--blue-500),var(--blue-700));
      color:white;
      border:none;
      cursor:pointer;
      font-weight:600;
      letter-spacing:0.2px;
      box-shadow: 0 6px 18px rgba(11,97,168,0.18);
      transition: transform 0.15s;
    }

    .btn-primary:hover { transform: translateY(-1px); }

    .error {
      margin-top:10px;
      color:#b91c1c;
      background: #fff4f4;
      border: 1px solid #f8d7da;
      padding:8px 10px;
      border-radius:6px;
      font-size:13px;
    }

    /* NUEVO: Logo en panel logueado */
    .welcome-header {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:20px;
    }

    .welcome-left {
      display:flex;
      align-items:center;
      gap:10px;
    }

    .welcome-left img {
      width:50px;
      height:auto;
    }

    @media (max-width:900px){
      .login-wrap{ grid-template-columns: 1fr; padding:10px; }
      .brand-card{ order: -1; margin-bottom:8px; }
    }
  </style>
</head>

<body>

<?php if ($user->is_loaded()): ?>
  <div style="width:100%; max-width:900px; position:relative; z-index:1;">
    <div class="panel">
      <div class="welcome-header">
        <div class="welcome-left">
          <img src="/DGPOLA/EntrevistasCp/img/BA.png" alt="Logo GCBA">
          <div style="color:var(--muted); font-size:16px; font-weight:600;">Bienvenido</div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:14px; color:var(--muted)">Sesión iniciada como</div>
          <div style="margin-top:6px;"><strong><?= htmlspecialchars($_SESSION['nombre'] ?? ($_SESSION['uname'] ?? 'Usuario')) ?></strong></div>
          <div style="margin-top:10px;">
            <a href="?logout=1" style="color:var(--blue-500); text-decoration:none; font-weight:600;">Cerrar sesión</a>
          </div>
        </div>
      </div>
      <hr style="margin:16px 0; border:none; border-top:1px solid #f0f2f4">

      <nav style="display:flex; gap:14px; flex-wrap:wrap;">
        <?php if ($user->tienePermiso('admin')): ?>
          <a href="/DGPOLA/login/admin.php" target="_blank" style="padding:8px 12px; background:#eef6ff; color:var(--blue-700); border-radius:8px; text-decoration:none; font-weight:600;">Administración</a>
        <?php endif; ?>
        <?php if ($user->tienePermiso('simplerub')): ?>
          <a href="/DGPOLA/EntrevistasCp/consultaHogarRub.php" target="_blank" style="padding:8px 12px; background:#fff; color:var(--blue-700); border-radius:8px; text-decoration:none; border:1px solid #e6eef7">Consulta Ficha RUB</a>
        <?php endif; ?>
        <?php if ($user->tienePermiso('seguimiento')): ?>
          <a href="/DGPOLA/EntrevistasCp/consultaHogar.php" target="_blank" style="padding:8px 12px; background:#fff; color:var(--blue-700); border-radius:8px; text-decoration:none; border:1px solid #e6eef7">Seguimiento Hogares</a>
        <?php endif; ?>
        <?php if ($user->tienePermiso('canasta')): ?>
          <a href="/DGPOLA/EntrevistasCp/canasta.php" target="_blank" style="padding:8px 12px; background:#fff; color:var(--blue-700); border-radius:8px; text-decoration:none; border:1px solid #e6eef7">Cálculo Canasta</a>
        <?php endif; ?>
        <?php if ($user->tienePermiso('entrevistas')): ?>
          <a href="/DGPOLA/EntrevistasCp/index.php" target="_blank" style="padding:8px 12px; background:#fff; color:var(--blue-700); border-radius:8px; text-decoration:none; border:1px solid #e6eef7">Informes</a>
        <?php endif; ?>
        <?php if ($user->tienePermiso('revision')): ?>
          <a href="/DGPOLA/EntrevistasCp/revisionEntrevista.php" target="_blank" style="padding:8px 12px; background:#fff; color:var(--blue-700); border-radius:8px; text-decoration:none; border:1px solid #e6eef7">Revisión</a>
        <?php endif; ?>
      </nav>
    </div>
  </div>

<?php else: ?>
  <div class="login-wrap">
    <div class="brand-card">
      <img class="logo" src="/DGPOLA/EntrevistasCp/img/BA.png" alt="Logo GCBA">
      <h1>DGPOLA</h1>
      <p>Ingrese al Sistema con CUIL y contraseña</p>
    </div>

    <div class="panel">
      <div class="login-header">
        <div>
          <h2>Acceder</h2>
          <div class="login-sub">Bienvenido, ingrese sus credenciales</div>
        </div>
      </div>

      <?php if ($errorMsg): ?>
        <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
      <?php endif; ?>

      <form method="post" style="margin-top:14px;">
        <div class="field">
          <input type="text" id="uname" name="uname" required autofocus placeholder="CUIL" value="<?= htmlspecialchars($_POST['uname'] ?? '') ?>">
        </div>

        <div class="field">
          <input type="password" id="pwd" name="pwd" required placeholder="Contraseña">
          <button type="button" class="show-pass" id="togglePass">Mostrar</button>
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
          <label style="font-size:13px; color:var(--muted);"><input type="checkbox" name="remember" <?= !empty($_POST['remember']) ? 'checked' : '' ?>> Recordar usuario</label>
          <button type="submit" class="btn-primary">Acceder</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      const btn = document.getElementById('togglePass');
      const pwd = document.getElementById('pwd');
      if (!btn || !pwd) return;
      btn.addEventListener('click', function(){
        if (pwd.type === 'password') {
          pwd.type = 'text';
          btn.textContent = 'Ocultar';
        } else {
          pwd.type = 'password';
          btn.textContent = 'Mostrar';
        }
      });
    })();
  </script>
<?php endif; ?>

</body>
</html>




