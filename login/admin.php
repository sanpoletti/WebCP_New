<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/login/phpUserClass/access.class.php';
$user = new flexibleAccess();
if (!$user->tienePermiso('admin')) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/index.php');
    exit;
}

if (empty($_POST['action'])) {
    $_POST['action'] = 'new';
}

$newChecked = ($_POST['action'] == 'new') ? 'checked' : '';
$modifChecked = ($_POST['action'] == 'modif') ? 'checked' : '';
$remChecked = ($_POST['action'] == 'rem') ? 'checked' : '';

$actionClick = "onclick='this.form.submit()'";

// Estilos generales y estructura visual moderna
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f5f6fa;
            color: #2f3640;
        }
        h1 {
            color: #273c75;
        }
        .form-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .radio-group {
            margin-bottom: 30px;
        }
        .radio-group input {
            margin-left: 10px;
        }
        .btn {
            margin-top: 20px;
            background-color: #44bd32;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #4cd137;
        }
    </style>
</head>
<body>

<div class="radio-group">
    <form method="post" action="#" name="actionForm">
        <label>
            <input type="radio" name="action" value="new" <?= $newChecked ?> <?= $actionClick ?> /> Nuevo
        </label>
        <label>
            <input type="radio" name="action" value="modif" <?= $modifChecked ?> <?= $actionClick ?> /> Modificar
        </label>
        <label>
            <input type="radio" name="action" value="rem" <?= $remChecked ?> <?= $actionClick ?> /> Eliminar
        </label>
    </form>
</div>

<?php
if ($_POST['action'] == 'new') {
    if (!empty($_POST['username'])) {
        $newUser = new flexibleAccess();
        $data = [
            'username' => $_POST['username'],
            'passw' => sha1($_POST['pwd']),
            'email' => $_POST['email'],
            'active' => 1,
            'idGrupo' => $_POST['group'],
            'nombre' => $_POST['nombre']
        ];
        $userID = $newUser->insertUser($data);
        echo '<div class="form-section">';
        echo ($userID == 0) ? '<p style="color:red;">❌ Usuario no registrado. Verificá que insertUser esté implementado correctamente.</p>' : '<p style="color:green;">✅ Usuario registrado con ID: ' . $userID . '</p>';
        echo '</div>';
    }
    $optionsGroup = getGroupOptions($user);
    ?>
    <div class="form-section">
        <h1>Registrar</h1>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <label for="username">CUIT:</label>
            <input type="text" name="username" required />

            <label for="nombre">Nombre completo:</label>
            <input type="text" name="nombre" required />

            <label for="pwd">Contraseña:</label>
            <input type="password" name="pwd" required />

            <label for="email">E-Mail:</label>
            <input type="email" name="email" required />

            <label for="group">Grupo:</label>
            <select name="group"> <?= $optionsGroup ?> </select>

            <input type="hidden" name="action" value="new" />
            <input type="submit" value="Registrar usuario" class="btn" />
        </form>
    </div>
    <?php
} elseif ($_POST['action'] == 'rem') {
    if (isset($_POST['user'])) {
        $userId = $_POST['user'];
        $msg = $user->removeUser($userId) ? "Usuario $userId eliminado" : "No se pudo eliminar el usuario $userId";
        echo "<div class='form-section'><p>$msg</p></div>";
    }
    $optionsUser = getUserOptions($user);
    ?>
    <div class="form-section">
        <h1>Eliminar</h1>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <label for="user">Usuario:</label>
            <select name="user"> <?= $optionsUser ?> </select>

            <input type="hidden" name="action" value="rem" />
            <input type="submit" value="Eliminar usuario" class="btn" />
        </form>
    </div>
    <?php
} elseif ($_POST['action'] == 'modif') {
    $optionsUser = getUserOptions($user);
    $optionsGroup = getGroupOptions($user);
    ?>
    <div class="form-section">
        <h1>Modificar</h1>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <label for="user">CUIT:</label>
            <select name="user"> <?= $optionsUser ?> </select>

            <label for="group">Grupo:</label>
            <select name="group"> <?= $optionsGroup ?> </select>

            <input type="hidden" name="action" value="modif" />
            <input type="submit" value="Modificar usuario" class="btn" />
        </form>
    </div>
    <?php
}

function getGroupOptions($user) {
    $groups = $user->getAvailableGroups();
    $optionsGroup = '';
    foreach ($groups as $group) {
        $optionsGroup .= "<option value='{$group['idgrupo']}'>{$group['nombre']}</option>";
    }
    return $optionsGroup;
}

function getUserOptions($user) {
    $users = $user->getAllUsers();
    $optionsUser = '';
    foreach ($users as $u) {
        $optionsUser .= "<option value='{$u['userID']}'>{$u['username']}</option>";
    }
    return $optionsUser;
}

?>
</body>
</html>
