<?php
$observsegI = $sh->getHogarCP()->getObservacionesseguiI();
$numeroConsulta = $_GET['numeroConsulta'] ?? '';

if (count($observsegI->getData()) > 0) {
    $observacionessegI = $observsegI->getData();
    ?>
    <h3 style='text-align:left'>
        OBSERVACIONES DE INTEGRANTES DEL HOGAR 
        <a href="#" id="toggleHistoricoInt">(Ver histórico...)</a>
    </h3>

    <table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
        <tr>
            <th style="width: 5%; border: solid 1px black;">Nro doc.</th>
            <th style="width: 10%; border: solid 1px black;">Apellido</th>
            <th style="width: 10%; border: solid 1px black;">Nombre</th>
            <th style="width: 30%; border: solid 1px black;">Observacion</th>
            <th style="width: 7%; border: solid 1px black;">Fecha Alta</th>
            <th style="width: 10%; border: solid 1px black;">Area Alta</th>
            <th style="width: 10%; border: solid 1px black;">Fecha Eliminacion</th>
            <th style="width: 10%; border: solid 1px black;">Area Eliminacion</th>
        </tr>
        <?php
        $bg_css = "";
        foreach ($observacionessegI as $obrdo) {
            echo "<tr>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('NRO_DOC')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('APELLIDO')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('NOMBRE')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('DESCRIP')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('FALTA')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('AREAALTA')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('FECHAELIM')}</td>
                    <td style='text-align: left; border: solid 1px black; $bg_css'>{$obrdo->getProperty('AREAELIM')}</td>
                </tr>";
        }
        ?>
    </table>

    <!-- 🔽 Contenedor oculto del histórico -->
    <div id="historicoObservacionesInt" style="display:none; margin-top:20px;">
        <iframe src="./hist_observHistoricoInt.php?ntitu=<?= $_GET['ntitu'] ?>&idhogar=<?= $_GET['idhogar'] ?>&rubs=<?= $_GET['nrorub'] ?>&numeroConsulta=<?= $numeroConsulta ?>&idpersonahogar=<?= $_GET['idpersonahogar'] ?>"
                style="width:100%; height:400px; border:1px solid #aaa; border-radius:8px;">
        </iframe>
    </div>

    <!-- 🔧 Script para mostrar/ocultar -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function(){
            $("#toggleHistoricoInt").click(function(e){
                e.preventDefault();
                $("#historicoObservacionesInt").slideToggle();
                $(this).text($(this).text() == '(Ver histórico...)' ? '(Ocultar histórico...)' : '(Ver histórico...)');
            });
        });
    </script>

<?php
}
?>
