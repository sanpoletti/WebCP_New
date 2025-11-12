<?php
$observseg = $sh->getHogarCP()->getObservacionessegui();
$numeroConsulta = $_GET['numeroConsulta'] ?? '';

if (count($observseg->getData()) > 0) {
    $observacionesseg = $observseg->getData();
?>
    <h3 style='text-align:left'>
        OBSERVACION DEL TITULAR DEL HOGAR - Vigente 
        <a href="#" id="toggleHistorico">(Ver histórico...)</a>
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
        $bg_cssc = '';
        foreach ($observacionesseg as $obrdo) {
            echo "<tr>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('NRO_DOC')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('APELLIDO')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('NOMBRE')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('DESCRIP')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('FALTA')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('AREAALTA')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('FECHAELIM')}</td>
                    <td style='border: solid 1px black;$bg_cssc'>{$obrdo->getProperty('AREAELIM')}</td>
                </tr>";
        }
        ?>
    </table>

    <!-- 🔽 Contenedor oculto del histórico -->
    <div id="historicoObservaciones" style="display:none; margin-top:20px;">
        <iframe src="./hist_observHistorico.php?ntitu=<?= $_GET['ntitu'] ?>&idhogar=<?= $_GET['idhogar'] ?>&rubs=<?= $_GET['nrorub'] ?>&numeroConsulta=<?= $numeroConsulta ?>&idpersonahogar=<?= $_GET['idpersonahogar'] ?>"
                style="width:100%; height:200px; border:1px solid #aaa; border-radius:8px;">
        </iframe>
    </div>

    <!-- 🔧 Script para mostrar/ocultar -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function(){
            $("#toggleHistorico").click(function(e){
                e.preventDefault();
                $("#historicoObservaciones").slideToggle();
                $(this).text($(this).text() == '(Ver histórico...)' ? '(Ocultar histórico...)' : '(Ver histórico...)');
            });
        });
    </script>

<?php
} else {
    echo "Sin datos";
}
?>

