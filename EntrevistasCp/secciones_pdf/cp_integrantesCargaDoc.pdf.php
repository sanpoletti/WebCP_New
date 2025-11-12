<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../login/phpUserClass/access.class.php';
$user = new flexibleAccess();

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

if (count($sh->getHogarCP()->getData()) > 0) {
    ?>
    <table style="width: 100%; border: solid 1px black; border-collapse: collapse" align="center">
        <tr>
            <th style="width: 15%; text-align: center; border: solid 1px black"><i>Apellido y Nombre</i></th>
            <th style="width: 5%; text-align: center; border: solid 1px black"><i>Nro.Doc.</i></th>
            <th style="width: 5%; text-align: center; border: solid 1px black"><i>Cuil.</i></th>
            <th style="width: 5%; text-align: center; border: solid 1px black"><i>Fech.Nac.</i></th>
            <th style="width: 4%; text-align: center; border: solid 1px black"><i>Edad actual.</i></th>
            <th style="width: 3%; text-align: center; border: solid 1px black"><i>Sec.</i></th>
            <th style="width: 10%; text-align: center; border: solid 1px black"><i>Baja</i></th>
            <th style="width: 5%; text-align: center; border: solid 1px black"><i>Adjuntar</i></th>
            <th style="width: 5%; text-align: center; border: solid 1px black"><i>Ver</i></th>
        </tr>
        <?php
        $tipo = 'a';
        foreach ($sh->getHogarCP()->getData() as $hdo) {
            echo "<tr>";
            echo "<td style='text-align: left; border: solid 1px black;'>" . getApeNom($hdo) . "</td>";
            echo "<td style='text-align: right; border: solid 1px black;'>" . $hdo->getProperty('NRO_DOC') . "</td>";
            echo "<td style='text-align: right; border: solid 1px black;'>" . $hdo->getProperty('CUIL') . "</td>";
            echo "<td style='text-align: right; border: solid 1px black;'>" . $hdo->getProperty('FECHA_NAC') . "</td>";
            echo "<td style='text-align: center; border: solid 1px black;'>" . $hdo->getProperty('EDADAC') . "</td>";
            echo "<td style='text-align: center; border: solid 1px black;'>" . $hdo->getProperty('SECUENCIA') . "</td>";
            echo "<td style='text-align: center; border: solid 1px black;'>" . $hdo->getProperty('BAJA') . "</td>";

            $sec = $hdo->getProperty('SECUENCIA');
            if ($sec !== null) {
                $Doc = './cargadoc_multiplespdf.php?NroDoc=' . $hdo->getProperty('NRO_DOC') .
                    '&ntitu=' . $_GET['ntitu'] .
                    '&sec=' . $hdo->getProperty('SECUENCIA') .
                    '&numeroconsulta=' . $numeroconsulta .
                    '&ape=' . $hdo->getProperty('apellido') .
                    '&nom=' . $hdo->getProperty('nombre') .
                    '&tipo=' . $tipo;

                // ✅ Adjuntar documento (ícono clip verde)
                if ($user->tienePermiso('entrevistas')) {
                    echo "<td style='text-align: center; border: solid 1px black;'>
                        <a href='#' 
                           title='Adjuntar Documentación' 
                           style='color: green; font-size:18px;' 
                           onclick=\"abrirModal('$Doc'); return false;\">
                           <i class='fa-solid fa-paperclip'></i>
                        </a>
                    </td>";
                } else {
                    echo "<td style='border: solid 1px black;'>&nbsp;</td>";
                }

                $verDoc = './PostlistarPdf2.php?NroDoc=' . $hdo->getProperty('NRO_DOC') .
                    '&ntitu=' . $_GET['ntitu'] .
                    '&sec=' . $hdo->getProperty('SECUENCIA') .
                    '&numeroconsulta=' . $numeroconsulta .
                    '&ape=' . $hdo->getProperty('apellido') .
                    '&nom=' . $hdo->getProperty('nombre') .
                    '&tipo=' . $tipo;

                // 👁 Ver documento (ícono ojo azul)
                echo "<td style='text-align: center; border: solid 1px black;'>
                    <a href='#' 
                       title='Ver Documentación' 
                       style='color: blue; font-size:18px;' 
                       onclick=\"
                        var width = 900;
                        var height = 400;
                        var left = (screen.width - width) / 2;
                        var top = (screen.height - height) / 2;
                        window.open('$verDoc','popup','width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',scrollbars=yes,resizable=yes');
                        return false;
                    \"><i class='fa-solid fa-eye'></i></a>
                </td>";
            } else {
                echo "<td style='text-align: center; border: solid 1px black;'>---</td>";
                echo "<td style='text-align: center; border: solid 1px black;'>---</td>";
            }

            echo "</tr>";
        }
        ?>
    </table>
<?php
}
?>

<!-- 🔹 Modal reutilizable -->
<div id="modalFormulario" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">
    <div style="position:relative; display:inline-block; width:80%; height:80%; margin-top:3%; background:#fff; border-radius:8px; box-shadow:0 0 15px rgba(0,0,0,0.3);">
        <button onclick="cerrarModal()" 
                style="position:absolute; top:10px; right:15px; background:red; color:white; border:none; font-size:18px; cursor:pointer; border-radius:4px;">X</button>
        <iframe id="modalIframe" src="" style="width:100%; height:100%; border:none; border-radius:8px;"></iframe>
    </div>
</div>

<script>
function abrirModal(url) {
    document.getElementById('modalIframe').src = url;
    document.getElementById('modalFormulario').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalFormulario').style.display = 'none';
    document.getElementById('modalIframe').src = '';
    // 🔄 Refrescar página al cerrar
    location.reload();
}
</script>

