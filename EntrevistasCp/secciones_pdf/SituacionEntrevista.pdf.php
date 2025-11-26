<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Entrevista</title>
<style>
/* Estilos tabla */
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid black; padding: 4px; text-align: left; }
th i { font-style: italic; }
td.bg-amarillo { background-color: #FFFFCC; }
td.bg-rosa { background-color: #ffcceb; }

/* Modal pantalla completa */
#modalFormulario {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
}
#modalContenido {
    position: relative;
    width: 90%;
    height: 90%;
    margin: 5% auto;
    background: white;
    border-radius: 6px;
    overflow: hidden;
}
#modalClose {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 24px;
    cursor: pointer;
    z-index: 1010;
}
#modalIframe {
    width: 100%;
    height: 100%;
    border: none;
}
</style>
<script>
function abrirModal(url) {
    document.getElementById('modalIframe').src = url;
    document.getElementById('modalFormulario').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalFormulario').style.display = 'none';
    document.getElementById('modalIframe').src = ''; // limpiar iframe
}
</script>
</head>
<body>
<h3 style="text-align:center;">SITUACION ENTREVISTA</h3>

<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");


if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/path.php';
require_once APP_ROOT . '/login/phpUserClass/access.class.php';


$user = new flexibleAccess();

$hora = $_GET['hora'] ?? '';
$numeroConsulta = $_GET['numeroConsulta'] ?? '';
$nroDoc = $_GET['NroDoc'] ?? '';
$uname = $_SESSION['uname'] ?? 'Desconocido';

// Situación entrevista
$situacionEntrevista = $sh->getHogarCP()->getSituacionEntrevista();

if (!$situacionEntrevista->isEmpty()):
?>

<table>
    <tr>
    	<th style="width:7%;"><i>Fecha</i></th>
        <th style="width:60%;"><i>Composición Familiar</i></th>
         <th style="width:15%;"><i>Entrevistador/a</i></th>
        <th style="width:7%;"><i>Evaluada</i></th>
        <th style="width:7%;"><i>Acciones</i></th>
    </tr>
    <?php foreach($situacionEntrevista->getData() as $s): 
        $identrevista    = $s->getProperty('identrevista');
        $numeroConsulta  = $s->getProperty('numeroconsulta'); 
        $composFamiliar  = $s->getProperty('composFamiliar');
        $sitEconomica    = $s->getProperty('siteconomica');
        $sitHabitacional = $s->getProperty('sithabitacional');
        $sitSalud        = $s->getProperty('sitsalud');
        $sitEducacion    = $s->getProperty('siteducacion');
        $idpersonahogar  = $s->getProperty('idpersonahogar');
        $idhogar         = $s->getProperty('idhogar');
        $ntitu           = $s->getProperty('nrotitular');
        $nrorub          = $s->getProperty('nro_rub');
        $mantienecompo   = $s->getProperty('mantienecompo');
        $observacion     = $s->getProperty('observacion');
        $usuarioCarga    = $s->getProperty('usuarioCarga');
        $hora            = $s->getProperty('hora');
        $completa        = $s->getProperty('completa');
        $evaluada        = $s->getProperty('evaluada');
        $registrado_eva  = $s->getProperty('registrado_eva');

        $urlEdit = './FormEditSituacionEntrevista.php?numeroconsulta=' . urlencode($numeroConsulta) .
            "&composFamiliar=" . urlencode($composFamiliar) .
            "&identrevista=" . urlencode($identrevista) .
            "&ntitu=" . urlencode($ntitu) .
            "&idhogar=" . urlencode($idhogar) .
            "&nrorub=" . urlencode($nrorub) .
            "&hora=" . urlencode($hora) .
            "&sitEconomica=" . urlencode($sitEconomica) .
            "&sitHabitacional=" . urlencode($sitHabitacional) .
            "&sitSalud=" . urlencode($sitSalud) .
            "&sitEducacion=" . urlencode($sitEducacion) .
            "&idpersonahogar=" . urlencode($idpersonahogar) .
            "&mantienecompo=" . urlencode($mantienecompo) .
            "&observacion=" . urlencode($observacion) .
            "&usuarioCarga=" . urlencode($usuarioCarga) .
            "&completa=" . urlencode($completa) .
            "&evaluada=" . urlencode($evaluada) .
            "&registrado_eva=" . urlencode($registrado_eva) .
            "&hora=" . urlencode($hora);
    ?>
    <tr>
    	<td class="bg-amarillo"><?= htmlspecialchars($hora) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($composFamiliar) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($usuarioCarga) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($evaluada) ?></td>
        <td>
            <?php if($registrado_eva === 'SI'): ?>
                <a href="#" onclick="alert('Formulario ya registrado'); return false;">Ver</a>
            <?php else: ?>
                <a href="#" onclick="abrirModal('<?= $urlEdit ?>'); return false;">Ver</a> 
                
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php else: 
$idpersonahogar = $_GET['idpersonahogar'];
$identrevista = 0;
$numeroConsulta = $_GET['numeroConsulta'] ?? 0;
$composFamiliar = '';
$ntitu = $_GET['ntitu'] ?? '';
$idhogar = $_GET['idhogar'] ?? '';
$nrorub = $_GET['nrorub'] ?? '';
$nroDoc = $_GET['NroDoc'] ?? '';

$urlInsert = './FormInsertSituacionEntrevista.php?numeroconsulta=' .$numeroConsulta .
    "&composFamiliar=$composFamiliar&identrevista=$identrevista&idpersonahogar=$idpersonahogar&uname=$uname&hora=$hora&ntitu=$ntitu&idhogar=$idhogar&nrorub=$nrorub&nroDoc=$nroDoc";
?>
<table>
    <tr>
        <th style="width:80%;"><i>Composición Familiar</i></th>
        <th style="width:50%; text-align:center;"><i>Acciones</i></th>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center;">
            <?php if ($user->tienePermiso('entrevistas')): ?>
                <a href="#" onclick="abrirModal('<?= $urlInsert ?>'); return false;">➕ Agregar Situación</a>
            <?php endif; ?>
        </td>
    </tr>
</table>




<?php endif; ?>

<!-- Modal -->
<div id="modalFormulario">
    <div id="modalContenido">
        <span id="modalClose" onclick="cerrarModal()">×</span>
        <iframe id="modalIframe"></iframe>
    </div>
</div>

</body>
</html>


