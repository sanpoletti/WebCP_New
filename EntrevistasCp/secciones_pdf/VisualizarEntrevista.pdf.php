<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">

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


<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");


if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../login/phpUserClass/access.class.php';
$user = new flexibleAccess();

$hora = $_GET['hora'] ?? '';
$numeroConsulta = $_GET['numeroConsulta'] ?? '';

$nrorub = $_GET['nrorub'] ?? '';
$ntitular = $_GET['ntitu'] ?? '';
$idpersonahogar = $_GET['idpersonahogar'] ?? '';
$idhogar = $_GET['idhogar'] ?? '';


$nroDoc = $_GET['NroDoc'] ?? '';
$uname = $_SESSION['uname'] ?? 'Desconocido';

// Situación entrevista
include_once __DIR__ . '/../entrevistas.class.php';

/*
 echo "<pre>🛠️ DEBUG visualizar Entrevista\n";
 echo "ntitular: $ntitular\n";
 echo "idhogar: $idhogar\n";
 echo "nrorub: $nrorub\n";
 echo "numeroConsulta: $numeroConsulta\n";
 echo "idpersonahogar: $idpersonahogar\n";
 echo "</pre>";
 */
// Creo objeto Seguimiento del Hogar (contendra todos los datos de la ficha)
//$sh = new Entrevistas($ntitu, $nrorub, $idhogar,$numeroConsulta,$idpersonahogar);
$sh = new Entrevistas($ntitular, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);

$situacionEntrevista = $sh->getHogarCP()->getSituacionEntrevista();

if (!$situacionEntrevista->isEmpty()):
?>
<table>
    <tr>
    	<th style="width:7%;"><i>Fecha</i></th>
        <th style="width:60%;"><i>Composición Familiar</i></th>
         <th style="width:15%;"><i>Entrevistador/a</i></th>
        <th style="width:7%;"><i>Evaluada</i></th>
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
    ?>
    <tr>
    	<td class="bg-amarillo"><?= htmlspecialchars($hora) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($composFamiliar) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($usuarioCarga) ?></td>
        <td class="bg-amarillo"><?= htmlspecialchars($evaluada) ?></td>
        
    </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
    <div style="text-align:center; color:#888; font-style:italic; margin-top:20px;">
        🚫 No tiene Entrevista registrada
    </div>
<?php endif; ?>

</body>
</html>


