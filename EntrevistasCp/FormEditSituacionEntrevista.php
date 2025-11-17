
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../login/phpUserClass/access.class.php';

header('Content-Type: text/html; charset=UTF-8');

if (
    !isset($_GET['numeroconsulta']) ||
    !isset($_GET['idpersonahogar']) ||
    !isset($_GET['identrevista'])
) {
    echo "No tiene permisos para acceder a Entrevistas.";
    die();
}





// Variables
$numeroconsulta  = $_GET['numeroconsulta'];
$nroDoc           = $_GET['nroDoc']?? '';
$idpersonahogar  = $_GET['idpersonahogar'];
$identrevista    = $_GET['identrevista'];
$composFamiliar  = $_GET['composFamiliar'] ?? '';
$sitEconomica    = $_GET['sitEconomica'] ?? '';
$sitHabitacional = $_GET['sitHabitacional'] ?? '';
$sitSalud        = $_GET['sitSalud'] ?? '';
$sitEducacion    = $_GET['sitEducacion'] ?? '';
$mantienecompo   = $_GET['mantienecompo'] ?? 'NO';
$completa        = $_GET['completa'] ?? 'NO';
$evaluada        = $_GET['evaluada'] ?? 'NO';
$observacion     = $_GET['observacion'] ?? '';
$hora            = $_GET['hora'] ?? '';
$nrorub          = $_GET['nro_rub'] ?? '';
$idhogar         = $_GET['idhogar'] ?? '';
$ntitu           = $_GET['ntitu'] ?? '';
$usuario          =  $_SESSION['username'] ?? '';


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
    <title>Editar Situación Entrevista</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
        #formEditUsuario { max-width: 95%; margin: auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-grid { display: flex; flex-wrap: wrap; gap: 20px; }
        .form-group { flex: 1 1 calc(50% - 20px); display: flex; flex-direction: column; }
        .form-group-full { flex: 1 1 100%; }
        label { font-weight: bold; margin-bottom: 4px; }
        input[type="text"], textarea, select { padding: 6px; font-size: 14px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; width: 100%; }
        textarea { resize: vertical; min-height: 60px; }
        .btn-primary { background-color: #2d89ef; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 8px; margin-top: 20px; }
        .btn-primary:hover { background-color: #1b5eaa; }
        .btn-close { background-color: #c0392b; color: #fff; border: none; padding: 10px 15px; font-size: 14px; border-radius: 8px; margin-top: 20px; cursor: pointer; }
        .btn-close:hover { background-color: #922b21; }
        .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .form-header h2 { margin: 0; color: #2d89ef; }
        .modulo-resaltado { background: #e8f4ff; border: 2px solid #2d89ef; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .modulo-resaltado h3 { margin: 0 0 10px 0; color: #2d89ef; cursor:pointer; }
    </style>
</head>
<body>

<div id="formEditUsuario">

    <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Integrantes del hogar
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/cp_integrantesCargaDoc.pdf.php'; ?>
        </div>
    </div>


    <div class="form-header">
       
    </div>

    <form action="compo_familiar.php" method="get" id="editFormUsuario">
        <!-- Campos ocultos -->
        <input type="hidden" name="idpersonahogar" value="<?= htmlspecialchars($idpersonahogar) ?>">
        <input type="hidden" name="evaluada" value="<?= htmlspecialchars($evaluada) ?>">
        <input type="hidden" name="identrevista" value="<?= htmlspecialchars($identrevista) ?>">
        <input type="hidden" name="numeroConsulta" value="<?= htmlspecialchars($numeroconsulta) ?>">
        <input type="hidden" name="nro_rub" value="<?= htmlspecialchars($nrorub) ?>">
        <input type="hidden" name="idhogar" value="<?= htmlspecialchars($idhogar) ?>">
        <input type="hidden" name="ntitu" value="<?= htmlspecialchars($ntitu) ?>">

        <div class="form-grid">
            <div class="form-group">
                <label for="hora">Horario</label>
                <input type="text" id="hora" value="<?= htmlspecialchars($hora) ?>" disabled>
            </div>

            <div class="form-group">
                <label for="mantienecompo">Mantiene Composición Familiar</label>
                <select name="mantienecompo">
                    <option value="SI" <?= $mantienecompo === 'SI' ? 'selected' : '' ?>>SI</option>
                    <option value="NO" <?= $mantienecompo === 'NO' ? 'selected' : '' ?>>NO</option>
                </select>
            </div>

            <div class="form-group">
                <label for="composFamiliar">Composición Familiar</label>
                <textarea name="composFamiliar"><?= htmlspecialchars($composFamiliar) ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitEconomica">Situación Económica</label>
                <textarea name="sitEconomica"><?= htmlspecialchars($sitEconomica) ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitHabitacional">Situación Habitacional</label>
                <textarea name="sitHabitacional"><?= htmlspecialchars($sitHabitacional) ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitSalud">Situación Salud</label>
                <textarea name="sitSalud"><?= htmlspecialchars($sitSalud) ?></textarea>
            </div>

            <div class="form-group">
                <label for="sitEducacion">Situación Educación</label>
                <textarea name="sitEducacion"><?= htmlspecialchars($sitEducacion) ?></textarea>
            </div>

            <div class="form-group">
                <label for="observacion">Observaciones</label>
                <textarea name="observacion"><?= htmlspecialchars($observacion) ?></textarea>
            </div>

            <div class="form-group">
                <label for="completa">Documentación Completa</label>
                <select name="completa">
                    <option value="SI" <?= $completa === 'SI' ? 'selected' : '' ?>>SI</option>
                    <option value="NO" <?= $completa === 'NO' ? 'selected' : '' ?>>NO</option>
                </select>
            </div>
        </div>
<?php $user = new flexibleAccess();
if (  $user->tienePermiso('entrevistas') ) { ?>
        <div style="text-align:center;">
            <input type="submit" class="btn-primary" value="Guardar">
            <button type="button" id="btnDocFaltante" style="margin-left:15px; padding:10px 15px; background:#2d89ef; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                 Documentación Faltante
            </button>
        </div>
  		 <div style="text-align:center; margin-top:20px;">
            <button type="button" id="btnImprimir" class="btn-primary">
                🖨️ Imprimir Entrevista
            </button>
		</div>
   
        
        
        
<?php }?>

    </form>
    <br>
    <br>
        <div class="modulo-resaltado">
        <h3 class="toggleModulo">
            Evaluaciones
            <span class="flecha" style="float: right;">▼</span>
        </h3>
        <div class="contenidoModulo">
            <?php include 'secciones_pdf/EvaluacionEntrevista.pdf.php'; ?>
        </div>
    </div>
    
    
    
</div>

<!-- jQuery (necesario para que funcione el $) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


function cerrarYActualizarPadre() {
    if (window.opener && !window.opener.closed) {
        window.opener.location.reload();
    }
    window.close();
}
    // Toggle del módulo
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


<script>
$(document).ready(function () {
    // Deshabilitar submit doble
    $('#editFormUsuario').submit(function () {
        $(this).find(':submit').attr("disabled", "disabled");
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
            // cuando se cierre el popup refrescamos el padre
            popup.onbeforeunload = function () {
                if (window.opener && !window.opener.closed) {
                    window.location.reload();
                }
            };
        } else {
            alert("Por favor, permite las ventanas emergentes para este sitio.");
        }
    });
});
</script>
<script>
document.getElementById('btnImprimir').addEventListener('click', function () {
    const params = '<?= $queryCargaDoc ?>';
    const w = 900;
    const h = 700;
    const left = (screen.width / 2) - (w / 2);
    const top = (screen.height / 2) - (h / 2);
    const opciones = `width=${w},height=${h},top=${top},left=${left},resizable=yes,scrollbars=yes`;

    const popup = window.open(
        './entrevista_print.php?' + params,
        'popupImprimir',
        opciones
    );

    if (popup) {
        popup.focus();
    } else {
        alert("Por favor, permite las ventanas emergentes para este sitio.");
    }
});
</script>


<!-- Modal bloqueante reutilizable -->
<div id="modalOverlay" style="display:none;
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.6); z-index:9998;"></div>

<div id="modalContainer" style="display:none;
    position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
    width:900px; height:450px; background:#fff;
    border-radius:10px; overflow:hidden;
    box-shadow:0 0 25px rgba(0,0,0,0.4); z-index:9999;">
    <iframe id="modalIframe" src="" style="width:100%;height:100%;border:none;"></iframe>
</div>
<script>
// --- Cuando se cierre la ventana (popup), refrescar la página que la abrió ---
window.onbeforeunload = function() {
    if (window.opener && !window.opener.closed) {
        try {
            window.opener.location.reload(); // 🔄 refresca generar.php
        } catch (e) {
            console.warn("No se pudo refrescar la ventana padre:", e);
        }
    }
};

// --- Si usás un botón con 'X' para cerrar manualmente ---
function cerrarVentana() {
    if (window.opener && !window.opener.closed) {
        try {
            window.opener.location.reload();
        } catch (e) {}
    }
    window.close(); // cierra el popup
}
</script>
<script>
function cerrarVentana() {
    if (window.opener && !window.opener.closed) {
        // Refresca la ventana que abrió este formulario
        window.opener.location.reload();
    }
    window.close(); // Cierra el popup
}
</script>

</body>


<?php 
$user = new flexibleAccess();
if (  $user->tienePermiso('revision') ) {
$agregarEvaluacion = './FormInsertEvaluacionEntrevista.php?numeroConsulta=' . urlencode($_GET['numeroconsulta']);
echo "<a href='$agregarEvaluacion'
                onclick=\"window.open(this.href,'popup','width=800,height=600,resizable=yes,scrollbars=yes'); return false;\"
                style='font-weight:bold;'>➕Nueva Evaluación</a>";
}
?>
</html>
<script>
// --- Abrir modal bloqueante con un iframe ---
function abrirModalBloqueante(url) {
    document.getElementById('modalIframe').src = url;
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('modalContainer').style.display = 'block';
}

// --- Cerrar modal y refrescar ---
function cerrarModalBloqueante() {
    document.getElementById('modalIframe').src = '';
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('modalContainer').style.display = 'none';
    location.reload(); // refresca al cerrar
}

// --- Escuchar mensajes del iframe ---
window.addEventListener('message', function(e) {
    if (e.data === 'cerrarModal') {
        cerrarModalBloqueante();
    }
});

</script>

