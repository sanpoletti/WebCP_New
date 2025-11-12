<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");

// Validar datos recibidos
if (isset($_GET['idEntrevista']) && !empty($_GET['idEntrevista'])) {
    $idEntrevista   = $_GET['idEntrevista'];
    $observacion    = $_GET['observacion'] ?? '';
    $numeroConsulta = $_GET['numeroConsulta'] ?? '';
    $uname          =  $_SESSION['username'] ?? '';
    $fecha          = $_GET['fecha'] ?? '';
} else {
    die("❌ No tiene permisos para acceder a este módulo.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evaluación</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #formAbmUsuario {
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 500px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
            min-height: 80px;
        }
        input[type="submit"] {
            background-color: #2d89ef;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #1b5eaa;
        }
    </style>
</head>
<body>

<div id="formAbmUsuario">

    <h2>Editar Evaluación</h2>
    <form id="editFormUsuario" action="compo_evaluacion.php" method="get">
        <input type="hidden" name="idEntrevista" value="<?= $idEntrevista ?>">
        <input type="hidden" name="numeroConsulta" value="<?= $numeroConsulta ?>">
        <input type="hidden" name="uname" value="<?= $uname ?>">

        <div class="form-group">
            <label for="observacion">Observación:</label>
            <textarea id="observacion" name="observacion"><?= htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <input type="submit" value="Guardar">
    </form>
</div>

<script>
$(function() {
    $('#editFormUsuario').submit(function() {
        $('input[type="submit"]').attr("disabled", "disabled");
    });
});
</script>

</body>
</html>
