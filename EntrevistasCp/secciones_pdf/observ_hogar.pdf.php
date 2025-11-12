<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- jQuery (asegurate de incluirlo una sola vez en tu página) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
$observDO = $sh->getHogarCP()->getObservacionesHogar();
$numeroConsulta = $_GET['numeroConsulta'] ?? '';

if (!$observDO->isEmpty()) {
    $observaciones = $observDO->getData();
?>
    <h3 style='text-align:left'>
        OBSERVACIONES SINTYS  
        <a href="#" id="toggleHistorico_sin">(Ver histórico...)</a>
    </h3>

    <table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
        <tr>
            <th style="width: 7%; text-align: left; border: solid 1px black"><i>Monto/Cant.</i></th>
            <th style="width: 48%; text-align: left; border: solid 1px black"><i>Observacion</i></th>
            <th style="width: 10%; text-align: left; border: solid 1px black"><i>Detalle</i></th>
        </tr>
        <?php
        foreach ($observaciones as $i => $obrdo) {
            $monto = $obrdo->getProperty('monto');
            $obs = getLimitedLengthString($obrdo->getProperty('OBSERVACION'), 85);
            $idpersonahogar = $obrdo->getProperty('idpersonahogar');
            $idtipo = $obrdo->getProperty('idtipo');
            $histoInmueble = "./hist_inmueble.php?idpersonahogar=$idpersonahogar&idtipo=$idtipo";

            $uniqueId = "histoDiv_$i";

            echo "<tr>";
            echo "<td style='text-align: left; border: solid 1px black;'>$monto</td>";
            echo "<td style='text-align: left; border: solid 1px black;'>$obs</td>";
            echo "<td style='text-align: left; border: solid 1px black;'>
                    <a href='#' onclick=\"toggleHistorial_sin('$uniqueId', '$histoInmueble'); return false;\">Ver</a>
                  </td>";
            echo "</tr>";

            echo "<tr id='fila_$i'><td colspan='3'>
                    <div id='$uniqueId' style='display: none; padding: 10px; background-color: #f0f0f0;'></div>
                  </td></tr>";
        }
        ?>
    </table>

    <!-- Contenedor oculto del histórico -->
    <div id="historicoObservaciones_sin" style="display:none; margin-top:20px;">
        <iframe src="./hist_observ.php?ntitu=<?= $_GET['ntitu'] ?>&idhogar=<?= $_GET['idhogar'] ?>&rubs=<?= $_GET['nrorub'] ?>&numeroConsulta=<?= $numeroConsulta ?>&idpersonahogar=<?= $_GET['idpersonahogar'] ?>"
                style="width:100%; height:200px; border:1px solid #aaa; border-radius:8px;">
        </iframe>
    </div>

    <!-- ✅ Solo este script controla el toggle -->
    <script>
        $(function() {
            $("#toggleHistorico_sin").click(function(e) {
                e.preventDefault();
                $("#historicoObservaciones_sin").stop(true, true).slideToggle(300);
                $(this).text(
                    $(this).text() === '(Ver histórico...)' 
                        ? '(Ocultar histórico...)' 
                        : '(Ver histórico...)'
                );
            });
        });

        // ⚙️ Esta función solo se usa para los "Ver" de cada fila
        function toggleHistorial_sin(divId, url) {
            const div = document.getElementById(divId);
            if (div.style.display === 'none') {
                if (!div.innerHTML.trim()) {
                    fetch(url)
                        .then(r => r.text())
                        .then(html => {
                            div.innerHTML = html;
                            div.style.display = 'block';
                        });
                } else {
                    div.style.display = 'block';
                }
            } else {
                div.style.display = 'none';
            }
        }
    </script>

<?php
} else {
    echo "";
}
?>
