<?php
$turnos = $sh->getHogarCP()->getTurnos();
$turnosData = $turnos->getData();
$totalTurnos = count($turnosData);

if ($totalTurnos > 0) {

    usort($turnosData, function($a, $b) {
        return strtotime($b->getProperty('fecha')) - strtotime($a->getProperty('fecha'));
    });

    $uniqueBlock = uniqid('moduloTurnos_');
?>
    <h3 style="text-align:left">
        Turnos <a href="#" id="toggleHistorico_<?php echo $uniqueBlock; ?>">(Ver histórico...)</a>
    </h3>

    <table id="tabla-turnos-<?php echo $uniqueBlock; ?>" 
           style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
        <tr>
            <th style="width: 20%; text-align: left; border: solid 1px black;"><i>Fecha</i></th>
            <th style="width: 20%; text-align: left; border: solid 1px black;"><i>Hora</i></th>
            <th style="width: 50%; text-align: left; border: solid 1px black;"><i>Estado</i></th>
            <th style="width: 10%; text-align: left; border: solid 1px black;"><i>Detalle</i></th>
        </tr>

        <?php
        // mostrar los 2 más recientes
        $ultimosTurnos = array_slice($turnosData, 0, 2);

        foreach ($ultimosTurnos as $i => $turno) {
            $numeroConsulta = $turno->getProperty('numeroconsulta');
            $ntitu = $turno->getProperty('ntitular');
            $nrorub = $turno->getProperty('nrorub');
            $idpersonahogar = $turno->getProperty('idpersonahogar');
            $idhogar = $turno->getProperty('idhogar');

            $detalleEntrevista = "./secciones_pdf/VisualizarEntrevista.pdf.php?numeroConsulta=" . urlencode($numeroConsulta) .
                                 "&idpersonahogar=" . urlencode($idpersonahogar) .
                                 "&ntitu=" . urlencode($ntitu) .
                                 "&nrorub=" . urlencode($nrorub) .
                                 "&idhogar=" . urlencode($idhogar);

            $detalleId = "detalleEntrevista_{$uniqueBlock}_{$i}";
        ?>
            <tr>
                <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('fecha')); ?></td>
                <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('hora')); ?></td>
                <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('estado')); ?></td>
                <td style="text-align: left; border: solid 1px black;">
                    <a href="#" class="toggleDetalle" data-url="<?php echo $detalleEntrevista; ?>" data-target="<?php echo $detalleId; ?>">Ver</a>
                </td>
            </tr>
            <tr id="<?php echo $detalleId; ?>" style="display:none;">
                <td colspan="4" style="border: solid 1px black; background-color:#f8f8f8;">
                    <!-- Empieza VACÍO, se carga solo al hacer clic -->
                    <div class="detalleContainer" data-loaded="false" style="padding:10px; text-align:center;"></div>
                </td>
            </tr>
        <?php } ?>

        <?php
        if ($totalTurnos > 2) {
            $restoTurnos = array_slice($turnosData, 2);
            foreach ($restoTurnos as $i => $turno) {
                $numeroConsulta = $turno->getProperty('numeroconsulta');
                $ntitu = $turno->getProperty('ntitular');
                $nrorub = $turno->getProperty('nrorub');
                $idpersonahogar = $turno->getProperty('idpersonahogar');
                $idhogar = $turno->getProperty('idhogar');

                $detalleEntrevista = "./secciones_pdf/VisualizarEntrevista.pdf.php?numeroConsulta=" . urlencode($numeroConsulta) .
                                     "&idpersonahogar=" . urlencode($idpersonahogar) .
                                     "&ntitu=" . urlencode($ntitu) .
                                     "&nrorub=" . urlencode($nrorub) .
                                     "&idhogar=" . urlencode($idhogar);

                $detalleId = "detalleEntrevista_{$uniqueBlock}_extra_{$i}";
        ?>
                <tr class="fila-extra-<?php echo $uniqueBlock; ?>" style="display:none;">
                    <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('fecha')); ?></td>
                    <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('hora')); ?></td>
                    <td style="text-align: left; border: solid 1px black;"><?php echo htmlspecialchars($turno->getProperty('estado')); ?></td>
                    <td style="text-align: left; border: solid 1px black;">
                        <a href="#" class="toggleDetalle" data-url="<?php echo $detalleEntrevista; ?>" data-target="<?php echo $detalleId; ?>">Ver</a>
                    </td>
                </tr>
                <tr id="<?php echo $detalleId; ?>" class="fila-extra-<?php echo $uniqueBlock; ?>" style="display:none;">
                    <td colspan="4" style="border: solid 1px black; background-color:#f8f8f8;">
                        <div class="detalleContainer" data-loaded="false" style="padding:10px; text-align:center;"></div>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(function(){
        // mostrar/ocultar histórico (solo muestra filas, no toca detalles)
        const toggleId = "#toggleHistorico_<?php echo $uniqueBlock; ?>";
        const extraRows = ".fila-extra-<?php echo $uniqueBlock; ?>";

        $(toggleId).off('click').on('click', function(e){
            e.preventDefault();
            const $rows = $(extraRows);
            const isVisible = $rows.is(':visible');
            if (isVisible) {
                $rows.slideUp(300);
                $(this).text('(Ver histórico...)');
            } else {
                $rows.slideDown(300);
                $(this).text('(Ocultar histórico...)');
            }
        });

        // cargar detalle SOLO cuando el usuario hace clic en "Ver"
        $(document).off('click', '.toggleDetalle').on('click', '.toggleDetalle', function(e){
            e.preventDefault();
            const $link = $(this);
            const targetId = '#' + $link.data('target');
            const url = $link.data('url');
            const $targetRow = $(targetId);
            const $container = $targetRow.find('.detalleContainer');

            // si ya visible -> ocultar y salir
            if ($targetRow.is(':visible')) {
                $targetRow.slideUp(300);
                return;
            }

            // cerrar otros detalles visibles (si querés mantener abiertos, comentá esta línea)
            $targetRow.closest('table').find('tr[id^="detalleEntrevista_"]').filter(':visible').not($targetRow).slideUp(200);

            // mostrar el row y cargar solo si no está cargado
            $targetRow.slideDown(200);

            if ($container.data('loaded') !== true) {
                $container.html('<em>Cargando detalle...</em>');
                fetch(url, { credentials: 'same-origin' })
                    .then(r => {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.text();
                    })
                    .then(html => {
                        $container.html(html);
                        $container.data('loaded', true);
                    })
                    .catch(() => {
                        $container.html('<strong style="color:red;">Error al cargar el detalle.</strong>');
                    });
            }
        });
    });
    </script>

<?php
} else {
    echo "<p style='text-align:center;'>Sin datos</p>";
}
?>

