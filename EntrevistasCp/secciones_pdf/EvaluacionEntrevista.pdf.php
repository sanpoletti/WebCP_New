<script>
function abrirPopupCentrado(url, ancho, alto) {
    const izquierda = (screen.width - ancho) / 2;
    const arriba = (screen.height - alto) / 2;
    window.open(url, 'popup', `width=${ancho},height=${alto},top=${arriba},left=${izquierda},resizable=yes,scrollbars=yes`);
}
</script>


<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
echo "<pre>DEBUG Parámetros:
ntitu = " . htmlspecialchars($_GET['ntitu'] ?? 'NULL') . "
idhogar = " . htmlspecialchars($_GET['idhogar'] ?? 'NULL') . "
nrorub = " . htmlspecialchars($_GET['nrorub'] ?? 'NULL') . "
numeroConsulta = " . htmlspecialchars($_GET['numeroConsulta'] ?? 'NULL') . "
idpersonahogar = " . htmlspecialchars($_GET['idpersonahogar'] ?? 'NULL') . "</pre>";
*/





$numeroConsulta = $_GET['numeroConsulta'] ?? '';
$ntitu          = $_GET['ntitu'] ?? ($_GET['ntitular'] ?? '');
$idhogar        = $_GET['idhogar'] ?? '';
$nrorub         = $_GET['rubs'] ?? $_GET['nrorub'] ?? '';
$idpersonahogar = $_GET['idpersonahogar'] ?? 0;


$server_file = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/entrevista/';
$filecmn = $numeroConsulta . '.pdf';
$filenameEntrevista = $server_file . $filecmn;

$server_file_ = 'http://' . $_SERVER['SERVER_NAME'] . '/desarrollo_informes_entrevistas/uploads/doc_adeudada/';
$filenameDocFaltante = $server_file_ . $filecmn;


require_once __DIR__ . '/../../login/phpUserClass/access.class.php';
if (!isset($sh) || !is_object($sh)) {
    // ajustá la ruta si tu entrevistas.class.php está en otra carpeta
    require_once __DIR__ . '/../entrevistas.class.php';
    // el constructor: Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar)
    $sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);
}




$user = new flexibleAccess();
$evaluacionEntrevista = $sh->getHogarCP()->getEvaluacionEntrevista();

if (!$evaluacionEntrevista->isEmpty()) {
?>
    <table style="width: 100%; border: solid 1px black; border-collapse: collapse" align="center">
        <tr>
            <th style="width: 10%; text-align: left; border: solid 1px black"><i>Fecha</i></th>
            <th style="width: 70%; text-align: left; border: solid 1px black"><i>Observación</i></th>
            <th style="width: 10%; text-align: left; border: solid 1px black"><i>Usuario</i></th>
            <th style="width: 10%; text-align: center; border: solid 1px black"><i>Acción</i></th>
        </tr>

        <?php foreach ($evaluacionEntrevista->getData() as $evaluacionEntre) {
            $idEntrevista    = $evaluacionEntre->getProperty('id');
            $numeroConsulta  = $evaluacionEntre->getProperty('numeroconsulta');
            $observacion     = $evaluacionEntre->getProperty('observacion');
            $usuario         = $evaluacionEntre->getProperty('usuarioCarga');
            $fecha           = $evaluacionEntre->getProperty('fecha');

            $EditEvaluacionEntrevista = './FormEditEvaluacionEntrevista.php?' .
                'idEntrevista=' . urlencode($idEntrevista) .
                '&observacion=' . urlencode($observacion) .
                '&numeroConsulta=' . urlencode($numeroConsulta) .
                '&usuario=' . urlencode($usuario);
        ?>
            <tr>
                <td bgcolor="#FFFFCC" style="border: solid 1px black;"><?= htmlspecialchars($fecha) ?></td>
                <td bgcolor="#FFFFCC" style="border: solid 1px black;"><?= htmlspecialchars($observacion) ?></td>
                <td bgcolor="#FFFFCC" style="border: solid 1px black;"><?= htmlspecialchars($usuario) ?></td>
                <td style="text-align: center; border: solid 1px black;">
                        <a href="<?= $EditEvaluacionEntrevista ?>"
                           onclick="abrirPopupCentrado(this.href, 800, 600); return false;">
                       <?php
                       if (  $user->tienePermiso('revision') ) {
                           ?>
                           ✏ Editar
                           <?php } ?>
                       
                       
                        </a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br>

    <!-- Botón para agregar nueva evaluación -->
    <div style="text-align:center;">
        <?php
        $agregarEvaluacion = './FormInsertEvaluacionEntrevista.php?numeroConsulta=' . urlencode($numeroConsulta);
        
        ?>
    </div>

<?php
} else {
    // Si no hay evaluaciones cargadas, mostrar sólo el botón de agregar
    $agregarEvaluacion = './FormInsertEvaluacionEntrevista.php?numeroConsulta=' . urlencode($numeroConsulta);
?>
   
<?php
}
?>
