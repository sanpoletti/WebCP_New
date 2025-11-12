<?php
$domicilio_cp = $sh->getHogarCP()->getDomicilio()->getUniqueData();
$nroDoc = $_GET['NroDoc'] ?? '';
$ntitu = $_GET['ntitu'] ?? '';
$rubs = $_GET['rubs'] ?? '';
$idhogar = $_GET['idhogar'] ?? '';

if (!$domicilio_cp->isEmpty()) {
?>
    <h3 style="text-align:left">
        DOMICILIO - Vigente
        <a href="#" id="toggleHistoricodomi">(Ver histórico...)</a>
    </h3>

    <table style="width: 100%;border: solid 1px black; border-collapse: collapse" align="center">
        <tr>
            <th style="width: 24%; text-align: center; border: solid 1px black"><i>Calle</i></th>
            <th style="width: 6%; text-align: center; border: solid 1px black"><i>Altura</i></th>
            <th style="width: 8%; text-align: center; border: solid 1px black"><i>Villa</i></th>
            <th style="width: 6%; text-align: center; border: solid 1px black"><i>Tira</i></th>
            <th style="width: 4%; text-align: center; border: solid 1px black"><i>Piso</i></th>
            <th style="width: 4%; text-align: center; border: solid 1px black"><i>Dto</i></th>
            <th style="width: 16%; text-align: center; border: solid 1px black"><i>Barrio</i></th>
            <th style="width: 4%; text-align: center; border: solid 1px black"><i>Manz</i></th>
            <th style="width: 4%; text-align: center; border: solid 1px black"><i>Hab</i></th>
            <th style="width: 6%; text-align: center; border: solid 1px black"><i>Casa</i></th>
            <th style="width: 12%; text-align: center; border: solid 1px black"><i>Telef.</i></th>
            <th style="width: 6%; text-align: center; border: solid 1px black"><i>Cert domi.</i></th>
        </tr>

        <tr>
            <td style="text-align: left; border: solid 1px black"><?php echo $domicilio_cp->getProperty('calle'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('numero'); ?></td>
            <td style="text-align: center; border: solid 1px black"></td>
            <td style="text-align: center; border: solid 1px black"></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('piso'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('depto'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('barrio'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('manzana'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('hab'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('nrocasa'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('telefono'); ?></td>
            <td style="text-align: center; border: solid 1px black"><?php echo $domicilio_cp->getProperty('cert_domi'); ?></td>
        </tr>
    </table>

    <!-- 🔽 Contenedor oculto con iframe histórico -->
    <div id="historicoDomicilio" style="display:none; margin-top:20px;">
        <iframe src="./hist_domi.php?NroDoc=<?= $nroDoc ?>&ntitu=<?= $ntitu ?>&rubs=<?= $rubs ?>&idhogar=<?= $idhogar ?>"
                style="width:100%; height:250px; border:1px solid #aaa; border-radius:8px;">
        </iframe>
    </div>

    <!-- 🔧 Script toggle -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function(){
            $("#toggleHistoricodomi").click(function(e){
                e.preventDefault();
                $("#historicoDomicilio").slideToggle();
                $(this).text($(this).text() == '(Ver histórico...)' ? '(Ocultar histórico...)' : '(Ver histórico...)');
            });
        });
    </script>

<?php
} else {
    echo "Sin datos";
}
?>
