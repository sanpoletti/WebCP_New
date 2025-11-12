<?php



require_once __DIR__ . '/../login/phpUserClass/access.class.php';
$user = new flexibleAccess();
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

echo "usure = " .$_SESSION['username'] ?? '';

echo "</pre>";
*/

//$uname   = $_SESSION['username'] ?? '';
$hora    = $_SESSION['hora'] ?? 'Desconocido';

if (!isset($_GET['numeroconsulta']) || empty($_GET['numeroconsulta'])) {
    echo "No tiene permisos para acceder a Entrevistas";
    die();
}

$numeroconsulta   = $_GET['numeroconsulta'];
$nroDoc           = $_GET['nroDoc']?? '';
$composFamiliar   = $_GET['composFamiliar'] ?? '';
$sitEconomica     = $_GET['sitEconomica'] ?? '';
$sitHabitacional  = $_GET['sitHabitacional'] ?? '';
$observacion      = $_GET['observacion'] ?? '';
$sitEducacion     = $_GET['sitEducacion'] ?? '';
$idpersonahogar   = $_GET['idpersonahogar'] ?? '';
$sitSalud         = $_GET['sitSalud'] ?? '';
$nrorub           = $_GET['nrorub'] ?? '';
$idhogar          = $_GET['idhogar'] ?? '';
$ntitu            = $_GET['ntitu'] ?? '';
$identrevista     = intval($_GET['identrevista'] ?? 0);
$usuario          =  $_SESSION['username'] ?? '';
$hora             = $_GET['hora'] ?? $hora;

include_once 'entrevistas.class.php';
$sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroconsulta, $idpersonahogar);
include 'secciones_pdf/common_func.pdf.php';

$params = [
    'numeroconsulta' => $numeroconsulta,
    'nroDoc' => $nroDoc,
    'composFamiliar' => $composFamiliar,
    'sitEconomica' => $sitEconomica,
    'sitHabitacional' => $sitHabitacional,
    'observacion' => $observacion,
    'sitEducacion' => $sitEducacion,
    'idpersonahogar' => $idpersonahogar,
    'sitSalud' => $sitSalud,
    'nrorub' => $nrorub,
    'idhogar' => $idhogar,
    'ntitu' => $ntitu,
    'identrevista' => $identrevista,
    'uname' => $usuario,
    'hora' => $hora
];
$queryCargaDoc = http_build_query($params);
?>
<!DOCTYPE html>
<html lang="es">



<head>
    <meta charset="UTF-8">
    <title>Nueva Situación Entrevista</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <style>
    
        body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
        #formAbmUsuario { max-width: 95%; margin: auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-grid { display: flex; flex-wrap: wrap; gap: 20px; }
        .form-group { flex: 1 1 calc(50% - 20px); display: flex; flex-direction: column; }
        .form-group-full { flex: 1 1 100%; }
        label { font-weight: bold; margin-bottom: 4px; }
        input[type="text"], textarea, select { padding: 6px; font-size: 14px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; width: 100%; }
        textarea { resize: vertical; min-height: 60px; }
        input[type="submit"] { background-color: #2d89ef; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 8px; margin-top: 20px; }
        input[type="submit"]:hover { background-color: #1b5eaa; }
        .modulo-resaltado { background: #e8f4ff; border: 1px solid #2d89ef; border-radius: 6px; padding: 8px; margin-bottom: 10px; }
        .modulo-resaltado h3 {margin: 0;font-size: 14px; /* más chico */line-height: 1.2; /* menos altura */padding: 6px 8px; /* agrega padding interno */}
        
         /* ✅ Agregado para ocultar contenido inicialmente */
        .contenidoModulo {
            display: none;
        }
        
    </style>
    <script>
        function volverAFormulario() {
            window.location.href = "FormInsertSituacionEntrevista.php?<?= $queryCargaDoc ?>";
        }
    </script>
</head>
<body>

<div id="formAbmUsuario">
   	<div id="formAbmUsuario">
    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Resoluciones
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/resolucion.pdf.php'; ?>
        </div>
    </div>
	    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Domicilio
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/domicilios_entrevista.pdf.php'; ?>
        </div>
    </div>
    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Integrantes del hogar
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/cp_integrantesCargaDoc.pdf.php'; ?>
        </div>
    </div>
    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Observaciones de integrantes
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/carga_observa_seguimientoInt.pdf.php'; ?>
        </div>
    </div>
       
    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Observaciones Sintys
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/observ_hogar.pdf.php'; ?>
        </div>
    </div>   
       
       
       

    <form id="abmFormUsuario" action="compo_familiar.php" method="get">
        <input type="hidden" name="identrevista" value="<?= htmlspecialchars($identrevista) ?>">
        <input type="hidden" name="usuario" value="<?= htmlspecialchars($usuario) ?>">
        <input type="hidden" name="idpersonahogar" value="<?= htmlspecialchars($idpersonahogar) ?>">
        <input type="hidden" name="numeroConsulta" value="<?= htmlspecialchars($numeroconsulta) ?>">
        <input type="hidden" name="nroDoc" value="<?= htmlspecialchars($nroDoc) ?>">

        <div class="form-grid">
            <div class="form-group">
                <label for="hora">Horario</label>
                <input type="text" id="hora" name="hora" value="<?= htmlspecialchars($hora) ?>" disabled>
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
                <textarea name="composFamiliar"><?= htmlspecialchars($composFamiliar, ENT_QUOTES, 'UTF-8') ?></textarea>
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





        <div style="text-align: center; margin-top:20px;">
            <input type="submit" id="submitButton" value="Guardar">
            <button type="button" id="btnDocFaltante" style="margin-left:15px; padding:10px 15px; background:#2d89ef; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                Documentación Faltante
            </button>
        </div>
    </form>
</div>

<script>
    $('#abmFormUsuario').submit(function () {
        $('#submitButton').attr("disabled", "disabled");
    });

    function cerrarYActualizarPadre() {
        if (window.opener && !window.opener.closed) {
            window.opener.location.reload();
        }
        window.close();
    }

    $("#toggleModulo").click(function () {
        $("#contenidoModulo").slideToggle(200);
        $("#flecha").text($("#contenidoModulo").is(":visible") ? "▼" : "▲");
    });

    // Abrir popup Documentación Faltante
    $('#btnDocFaltante').click(function () {
        const params = '<?= $queryCargaDoc ?>';
        const w = 900;
        const h = 300;
        const left = (screen.width / 2) - (w / 2);
        const top = (screen.height / 2) - (h / 2);
        const opciones = `width=${w},height=${h},top=${top},left=${left},resizable=yes,scrollbars=yes`;

        const popup = window.open(
            './secciones_pdf/cp_integrantesDocAdeuda.pdf.php?' + params,
            'popupCargaDoc',
            opciones
        );

        if (popup) {
            popup.focus();
            popup.onbeforeunload = volverAFormulario;
        } else {
            alert("Por favor, permite las ventanas emergentes para este sitio.");
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modulos = document.querySelectorAll('.toggleModulo');

    modulos.forEach(h3 => {
        h3.addEventListener('click', function () {
            const contenido = this.nextElementSibling;
            const flecha = this.querySelector('.flecha');

            // Alternar visibilidad
            if (contenido.style.display === 'block') {
                contenido.style.display = 'none';
                flecha.textContent = '▼';
            } else {
                contenido.style.display = 'block';
                flecha.textContent = '▲';
            }
        });
    });
});
</script>

</body>

<?php 

if (  $user->tienePermiso('revision') ) {
 
$agregarEvaluacion = './FormInsertEvaluacionEntrevista.php?numeroConsulta=' . urlencode($_GET['numeroconsulta']);
echo "<a href='$agregarEvaluacion'
                onclick=\"window.open(this.href,'popup','width=800,height=600,resizable=yes,scrollbars=yes'); return false;\"
                style='font-weight:bold;'>➕ Agregar Nueva Evaluación</a>";
}
?>


</html>

