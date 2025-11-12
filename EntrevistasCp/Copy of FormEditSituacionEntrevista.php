<?php
// —————————————— FormEditSituacionEntrevista.php ——————————————
header('Content-Type: text/html; charset=UTF-8');

// Validamos que vengan los parámetros obligatorios (por GET)
// Si no existen, detenemos el script
if (
    !isset($_GET['numeroconsulta']) ||
    !isset($_GET['idpersonahogar']) ||
    !isset($_GET['identrevista'])
) {
    echo "No tiene permisos para acceder a este módulo.";
    die();
}

// Recuperamos todas las variables que necesitamos mostrar en el form
$numeroconsulta    = $_GET['numeroconsulta'];
$idpersonahogar    = $_GET['idpersonahogar'];
$composFamiliar    = isset($_GET['composFamiliar'])   ? $_GET['composFamiliar']   : '';
$identrevista      = $_GET['identrevista'];
$sitEconomica      = isset($_GET['sitEconomica'])     ? $_GET['sitEconomica']     : '';
$sitHabitacional   = isset($_GET['sitHabitacional'])  ? $_GET['sitHabitacional']  : '';
$sitSalud          = isset($_GET['sitSalud'])         ? $_GET['sitSalud']         : '';
$sitEducacion      = isset($_GET['sitEducacion'])     ? $_GET['sitEducacion']     : '';
$mantienecompo     = isset($_GET['mantienecompo'])    ? $_GET['mantienecompo']    : 'NO';
$completa          = isset($_GET['completa'])         ? $_GET['completa']         : 'NO';
$evaluada          = isset($_GET['evaluada'])         ? $_GET['evaluada']         : 'NO';
$observacion       = isset($_GET['observacion'])      ? $_GET['observacion']      : '';
$registrado_eva    = isset($_GET['registrado_eva'])   ? $_GET['registrado_eva']   : 'NO';
$hora              = isset($_GET['hora'])             ? $_GET['hora']             : '';
$nrorub            = isset($_GET['nro_rub'])          ? $_GET['nro_rub']          : '';
$idhogar           = isset($_GET['idhogar'])          ? $_GET['idhogar']          : '';
$ntitu             = isset($_GET['ntitu'])            ? $_GET['ntitu']            : '';

// Aquí podrías instanciar tu clase Entrevistas si la necesitas,
// por ejemplo:
// include_once 'entrevistas.class.php';
// $sh = new Entrevistas($ntitu, $nrorub, $idhogar, $numeroconsulta, $idpersonahogar);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Composición Familiar</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Fondo semitransparente si quisieras oscurecer (opcional) */
        /* .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        } */

        /* Contenedor popup */
        .popup-form {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 20px;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            overflow-y: auto;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
            width: 30%;
        }

        td textarea, td select {
            width: 100%;
            resize: vertical;
            padding: 6px;
            font-size: 14px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-header h2 {
            margin: 0;
        }

        .cerrar-btn {
            background: #c0392b;
            color: #fff;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn {
            background: #27ae60;
            color: #fff;
            border: none;
            padding: 10px 18px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .submit-btn:hover {
            background: #1e8449;
        }
    </style>
</head>
<body>

<div class="popup-form">
    <div class="form-header">
        <h2>Editar Composición Familiar</h2>
        <button class="cerrar-btn" onclick="cerrarYActualizarPadre()">Cerrar</button>
    </div>

    <form action="compo_familiar.php" method="get" id="editFormUsuario">
        <!-- Campos ocultos -->
        <input type="hidden" name="idpersonahogar"   value="<?php echo htmlspecialchars($idpersonahogar,   ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="evaluada"          value="<?php echo htmlspecialchars($evaluada,          ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="identrevista"      value="<?php echo htmlspecialchars($identrevista,      ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="numeroConsulta"    value="<?php echo htmlspecialchars($numeroconsulta,    ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="nro_rub"           value="<?php echo htmlspecialchars($nrorub,            ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="idhogar"           value="<?php echo htmlspecialchars($idhogar,           ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="ntitu"             value="<?php echo htmlspecialchars($ntitu,             ENT_QUOTES, 'UTF-8'); ?>">

        <table>
            <tr>
                <th>Horario:</th>
                <td>
                    <textarea rows="1" disabled><?php echo htmlspecialchars($hora, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Mantiene Composición Familiar:</th>
                <td>
                    <select name="mantienecompo">
                        <option value="<?php echo htmlspecialchars($mantienecompo, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($mantienecompo, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Composición Familiar:</th>
                <td>
                    <textarea rows="4" name="composFamiliar"><?php echo htmlspecialchars($composFamiliar, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Situación Económica:</th>
                <td>
                    <textarea rows="4" name="sitEconomica"><?php echo htmlspecialchars($sitEconomica, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Situación Habitacional:</th>
                <td>
                    <textarea rows="4" name="sitHabitacional"><?php echo htmlspecialchars($sitHabitacional, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Situación Salud:</th>
                <td>
                    <textarea rows="4" name="sitSalud"><?php echo htmlspecialchars($sitSalud, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Situación Educación:</th>
                <td>
                    <textarea rows="4" name="sitEducacion"><?php echo htmlspecialchars($sitEducacion, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Observaciones:</th>
                <td>
                    <textarea rows="4" name="observacion"><?php echo htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Documentación Completa:</th>
                <td>
                    <select name="completa">
                        <option value="<?php echo htmlspecialchars($completa, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($completa, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" class="submit-btn" value="Guardar">
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
function cerrarYActualizarPadre() {
    if (window.opener && !window.opener.closed) {
        window.opener.location.reload();
    }
    window.close();
    // Fallback en caso de que no se permita cerrar:
    setTimeout(function() {
        if (!window.closed) {
            window.location.href = "about:blank";
        }
    }, 300);
}
</script>

</body>
</html>
