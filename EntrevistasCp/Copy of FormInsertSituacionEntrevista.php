<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Nueva Situación Entrevista</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
        }
        #formAbmUsuario {
            max-width: 95%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-group {
            flex: 1 1 calc(50% - 20px); /* dos columnas */
            display: flex;
            flex-direction: column;
        }
        .form-group-full {
            flex: 1 1 100%;
        }
        label {
            font-weight: bold;
            margin-bottom: 4px;
        }
        input[type="text"],
        textarea,
        select {
            padding: 6px;
            font-size: 14px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
        }
        textarea {
            resize: vertical;
            min-height: 60px;
        }
        input[type="submit"] {
            background-color: #2d89ef;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #1b5eaa;
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['numeroconsulta']) && !empty($_GET['numeroconsulta'])) {
    $numeroconsulta = $_GET['numeroconsulta'];
    $composFamiliar = $_GET['composFamiliar'];
    $idpersonahogar = $_GET['idpersonahogar'];
    $nrorub = $_GET['nro_rub'];
    $idhogar = $_GET['idhogar'];
    $ntitu = $_GET['ntitu'];
    $identrevista = $_GET['identrevista'];
    $usuario = $_GET['uname'];
    $hora = $_GET['hora'];
} else {
    echo "No tiene permisos para acceder a este módulo";
    die();
}

function escapeSqlUnicode($str) {
    $str = str_replace("'", "''", $str); // Escapamos comillas simples
    return "N'" . $str . "'";
}



include_once 'entrevistas.class.php';
$sh = new Entrevistas($ntitu, $nrorub, $idhogar, $numeroConsulta, $idpersonahogar);
include 'secciones_pdf/common_func.pdf.php';
?>

<div id="formAbmUsuario">
    <h2 style="text-align: center;">📝 Nueva Entrevista</h2>
    <form id="abmFormUsuario" action="compo_familiar.php" method="get">
        <input type="hidden" name="identrevista" value="<?= $identrevista ?>">
        <input type="hidden" name="usuario" value="<?= $usuario ?>">
        <input type="hidden" name="idpersonahogar" value="<?= $idpersonahogar ?>">
        <input type="hidden" name="numeroConsulta" value="<?= $numeroconsulta ?>">

        <div class="form-grid">
            <div class="form-group">
                <label for="hora">Horario</label>
                <input type="text" id="hora" name="hora" value="<?= $hora ?>" disabled>
            </div>
            <div class="form-group">
                <label for="mantienecompo">Mantiene Composición Familiar</label>
                <select name="mantienecompo">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>

            <div class="form-group">
                <label for="composFamiliar">Composición Familiar</label>
                <textarea name="composFamiliar"><?= mb_convert_encoding($composFamiliar,  "UTF-16LE", "UTF-8") ?></textarea>
            </div>
            <div class="form-group">
                <label for="sitEconomica">Situación Económica</label>
                <textarea name="sitEconomica"><?= htmlspecialchars($sitEconomica, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitHabitacional">Situación Habitacional</label>
                <textarea name="sitHabitacional"><?= htmlspecialchars($sitHabitacional, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="form-group">
                <label for="sitSalud">Situación Salud</label>
                <textarea name="sitSalud"><?= htmlspecialchars($sitSalud, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitEducacion">Situación Educación</label>
                <textarea name="sitEducacion"><?= htmlspecialchars($sitEducacion, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="form-group">
                <label for="observacion">Observaciones</label>
                <textarea name="observacion"><?= htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="form-group">
                <label for="completa">Documentación Completa</label>
                <select name="completa">
                    <option value="NO">NO</option>
                    <option value="SI">SI</option>
                </select>
            </div>
        </div>

        <div style="text-align: center;">
            <input type="submit" id="submitButton" value="💾 Guardar">
        </div>
    </form>
</div>

<script>
    $('#abmFormUsuario').submit(function() {
        $('#submitButton').attr("disabled", "disabled");
    });

    function cerrarYActualizarPadre() {
        if (window.opener && !window.opener.closed) {
            window.opener.location.reload();
        }
        window.close();
        setTimeout(function () {
            if (!window.closed) {
                window.location.href = "about:blank";
            }
        }, 300);
    }
</script>

</body>
</html>

