<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");

if (session_status() === PHP_SESSION_NONE) session_start();
$uname = $_SESSION['username'] ?? '';

if (isset($_GET['numeroConsulta']) && !empty($_GET['numeroConsulta'])) {
    $numeroConsulta = $_GET['numeroConsulta'];
    $idEntrevista = 0;
    $observacion = '';
} else {
    echo "Usuario: " . $uname;
    echo "<br>Solicite permiso para poder ingresar la evaluación.";
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva Evaluación</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
#formAbmUsuario {
    width: 600px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    text-align: center;
}
input[type="submit"], .btn-cerrar {
    background-color: #2d89ef;
    color: white;
    border: none;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 8px;
    width: 48%;
    margin-top: 10px;
}
input[type="submit"]:hover, .btn-cerrar:hover {
    background-color: #1b5eaa;
}
.acciones { display: flex; justify-content: space-between; }


textarea {
    width: 100%;
    height: 180px;           /* 🔹 Más alto */
    padding: 10px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    resize: vertical;        /* 🔹 Permite agrandarlo manualmente */
    box-sizing: border-box;
    line-height: 1.4;
    background-color: #fafafa;
    transition: border-color 0.3s, box-shadow 0.3s;
}

textarea:focus {
    outline: none;
    border-color: #2d89ef;
    box-shadow: 0 0 6px rgba(45,137,239,0.3);
}




</style>
</head>
<body>

<div id="formAbmUsuario">
    <h2>Nueva Evaluación</h2>

    <form id="abmFormUsuario">
        <input type="hidden" name="idEntrevista" value="<?= $idEntrevista ?>">
        <input type="hidden" name="uname" value="<?= $uname ?>">
        <input type="hidden" name="numeroConsulta" value="<?= $numeroConsulta ?>">

        <div class="form-group">
            <label for="observacion">Observación:</label>
            <textarea id="observacion" name="observacion" required><?= htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="acciones">
            <input type="submit" id="submitButton" value="Guardar">
            <button type="button" class="btn-cerrar" onclick="cerrarYActualizarPadre()">Cerrar</button>
        </div>
    </form>
</div>

<script>
$('#abmFormUsuario').on('submit', function(e) {
    e.preventDefault(); // evita que navegue fuera
    $('#submitButton').prop('disabled', true);

    $.get('compo_evaluacion.php', $(this).serialize())
        .done(function(res) {
            console.log('Guardado exitoso:', res);
            cerrarYActualizarPadre();
        })
        .fail(function(err) {
            alert('Error al guardar. Ver consola.');
            console.error(err);
            $('#submitButton').prop('disabled', false);
        });
});

function cerrarYActualizarPadre() {
    try {
        if (window.opener && !window.opener.closed) {
            // 🔹 Caso: popup abierto con window.open()
            window.opener.location.reload();
            window.close();
        } else if (window.parent && window.parent !== window) {
            // 🔹 Caso: abierto dentro de iframe modal
            const modal = window.parent.document.getElementById('modalFormulario');
            const iframe = window.parent.document.getElementById('modalIframe');
            if (modal && iframe) {
                modal.style.display = 'none';
                iframe.src = '';
            }
            window.parent.location.reload();
        } else {
            // 🔹 Fallback
            window.location.href = 'generar.php';
        }
    } catch (e) {
        console.error('Error al cerrar y actualizar:', e);
    }
}
</script>
</body>
</html>

