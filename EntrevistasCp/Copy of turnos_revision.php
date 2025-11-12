

<?php
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
 echo "evaluada = " . ($_GET['evaluada'] ?? 'NO LLEGA') . "\n";
 echo "</pre>";

*/
ob_start();
?>


<style>
/* 🔹 Contenedor principal */

.contenedor-principal {
    width: 100%;
    text-align: left; /* centra el contenedor-turnos */
    overflow-x: auto;   /* si no entra en pantalla, scroll horizontal */
}




.contenedor-turnos {
    display: inline-block;      /* se ajusta al contenido */
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 20px auto;
}




/* 🔹 Estilos de la tabla */
#tabla-turnos {
    border-collapse: collapse;
    table-layout: auto; /* se ajusta al contenido */
}



#tabla-turnos thead {
    background-color: #007bff;
    color: white;
    text-align: left;
}

#tabla-turnos th, #tabla-turnos td {
    padding: 8px 12px;
    border: 1px solid #ddd;
    white-space: nowrap;
}

#tabla-turnos tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

/* 🔹 Checkbox alineado */
#select-all {
    transform: scale(1.2);
}

/* 🔹 Filtro */
.filtro-oficina {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filtro-oficina select {
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

/* 🔹 Responsive */
@media (max-width: 768px) {
    .contenedor-turnos {
        width: 100%;
        padding: 10px;
    }

    #tabla-turnos th, #tabla-turnos td {
        font-size: 12px;
        padding: 6px;
    }
}
</style>

<?php 

// Funciones JS necesarias para interactividad
echo <<<JS
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.querySelector('#tabla-turnos #select-all');
    const checkboxes = document.querySelectorAll('#tabla-turnos .row-check');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(chk => chk.checked = this.checked);
        });
    }
});

function DoNav(url) {
    window.location = url;
}

function ChangeColor(row, highlight) {
    row.style.backgroundColor = highlight ? '#f0f0f0' : '';
}

function filtrarTurnos() {
    const filtroOficina = document.getElementById('filtroOficina').value.toLowerCase();
    const filtroEvaluada = document.getElementById('filtroEvaluada').value.toLowerCase();
    const filas = document.querySelectorAll('#tabla-turnos tbody tr');

    filas.forEach(fila => {
        const evaluada = fila.cells[9].textContent.toLowerCase().trim();  // Columna Evaluada
        const oficina = fila.cells[10].textContent.toLowerCase().trim();  // Columna Oficina

        const coincideOficina = (filtroOficina === '' || oficina.includes(filtroOficina));
        const coincideEvaluada = (filtroEvaluada === '' || evaluada.includes(filtroEvaluada));

        fila.style.display = (coincideOficina && coincideEvaluada) ? '' : 'none';
    });
}

function resetFiltros() {
    document.getElementById('filtroOficina').value = '';
    document.getElementById('filtroEvaluada').value = '';

    const filas = document.querySelectorAll('#tabla-turnos tbody tr');
    filas.forEach(fila => fila.style.display = '');
}








</script>

JS;

require_once 'entrevistas.class.php';
$tipo = 'inte';


/* ✅ Inicia el contenedor */



echo 

'<div class="contenedor-turnos">';


if (isset($_GET['search_by'])) {
    $searchBy = $_GET['search_by'];
    $fecha    = $_GET['fecha'] ?? '';
    $nrodoc   = $_GET['nrodoc'] ?? '';
    $ntitular = $_GET['ntitular'] ?? '';
    $eva      = $_GET['evaluada'] ?? '';
    $oficina  = $_GET['oficina'] ?? '';
    $tipo     = $_GET['tipo'] ?? 'inte';

    /** 🔍 Búsqueda por Nro Doc o Titular o evaluada **/
    if (($searchBy === 'ndoc' && !empty($nrodoc)) || ($searchBy === 'ntitu' && !empty($ntitular))) {
        $personasObj = new Personas($ntitular, $nrodoc, '','');
        $personas = $personasObj->getData();

        if (!empty($personas)) {
            foreach ($personas as $persona) {
                echo '<p>' .$persona->APELLIDO . ', ' . $persona->NOMBRE . ', ' . $persona->nrotitular  . '</p>';
                $nrorub = $persona->nrorub;
                $idhogar = $persona->idhogar;
                $numeroConsulta = 0;
                $idpersonahogar = $persona->IDPERSONAHOGAR;

                echo '<table id="tabla-turnos" border="1" cellpadding="4" cellspacing="0">';
                echo '<thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Categoria</th>
                            <th>Resolución</th>
                            <th>Evaluada</th>
                            <th>Oficina</th>
                            <th>Usuario Carga</th>
                        </tr>
                      </thead>
                      <tbody>';

                $turnosObj = new Turnos($idhogar, 'inte');
                $turnos = $turnosObj->getData();

                foreach ($turnos as $turno) {
                    $url = 'generar.php?NroDoc=' . $turno->nro_doc .
                        '&ntitu=' . urlencode($turno->ntitular) .
                        '&idhogar=' . $turno->idhogar .
                        '&nrorub=' . $nrorub .
                        '&hora=' . urlencode($turno->fecha . ' ' .$turno->hora) .
                        '&numeroConsulta=' . $turno->numeroconsulta .
                        '&idpersonahogar=' . $turno->idpersonahogar .
                        '&evaluada=' . $turno->registrado_eva;

                    echo "<tr onmouseover='ChangeColor(this, true);' onmouseout='ChangeColor(this, false);' onclick=\"DoNav('$url')\">";
                    echo '<td><input type="checkbox" class="row-check" onclick="event.stopPropagation();"></td>';
                    echo '<td>' . htmlspecialchars($turno->fecha) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->hora) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->estado) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->categoria) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->resolucion) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->registrado_eva) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->oficina) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->nombreusuario) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody></table><br>';
            }
        } else {
            echo "<p>No se encuentran inscriptos con los datos ingresados.</p>";
        }
    }

    /** 🔍 Búsqueda por Fecha **/
    elseif ($searchBy === 'fecha' && !empty($fecha) || ($searchBy === 'eva' && !empty($eva)) ) {
        $fechaRaw = $_GET['fecha'];
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaRaw)) {
            list($anio, $mes, $dia) = explode('-', $fechaRaw);
            $fechaFormateada = "$dia-$mes-$anio";
        } elseif (preg_match('/^\d{8}$/', $fechaRaw)) {
            $anio = substr($fechaRaw, 0, 4);
            $mes = substr($fechaRaw, 4, 2);
            $dia = substr($fechaRaw, 6, 2);
            $fechaFormateada = "$dia-$mes-$anio";
        } else {
            $fechaFormateada = '';
        }

        require_once 'TurnosFecha_revision.php';
        try {
            $turnos = new TurnosFecha($fechaFormateada, $tipo,$eva);
        } catch (Throwable $e) {
            echo "<p style='color:red;'>Error cargando turnos: " . $e->getMessage() . "</p>";
            $turnos = null;
        }

        if (!is_object($turnos)) {
            echo "<p>Error cargando turnos por fecha.</p>";
        } elseif (count($turnos->getResult()) === 0) {
            echo "<p>No se encontraron turnos para la fecha seleccionada.</p>";
        } else {
            
            // 🔹 Agregar filtro dinámico por oficina
            $oficinas = array_unique(array_map(function ($t) {
                return $t['oficina'];
            }, $turnos->getResult()));
                sort($oficinas);
                
                // 🔹 Agregar filtro dinámico por evaluada
            $evaluadas = array_unique(array_map(function ($t) {
                    return $t['registrado_eva'];
                }, $turnos->getResult()));
                    sort($evaluadas);
            
                    
                echo '<div style="margin:10px 0;">';
                    echo '<label for="filtroOficina"><b>Oficina:</b></label> ';
                    echo '<select id="filtroOficina" onchange="filtrarTurnos()" style="padding:4px; margin-left:8px;">';
                    echo '<option value="">-- Todas --</option>';
                    foreach ($oficinas as $of) {
                        echo '<option value="' . htmlspecialchars($of) . '">' . htmlspecialchars($of) . '</option>';
                    }
                    echo '</select>';
               
                echo '<label for="filtroEvaluada"><b>Evaluada:</b></label> ';
                echo '<select id="filtroEvaluada" onchange="filtrarTurnos()" style="padding:4px; margin-left:8px;">';
                echo '<option value="">-- Todas --</option>';
                foreach ($evaluadas as $eva) {
                    echo '<option value="' . htmlspecialchars($eva) . '">' . htmlspecialchars($eva) . '</option>';
                }
                echo '</select>';
                echo '<button type="button" onclick="resetFiltros()" style="margin-left: 12px; padding: 6px 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Reset filtros</button>';
                
                echo '</div>';
            
                
            
            echo '<table id="tabla-turnos" border="1" cellpadding="4" cellspacing="0">';
            echo '<thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Nro. Documento</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Categoria</th>
                        <th>Resolución</th>
                        <th>Evaluada</th>
                        <th>Oficina</th>
                        <th>Usuario Carga</th>
                    </tr>
                  </thead>
                  <tbody>';

            foreach ($turnos->getResult() as $rdoTurno) {
                if (!empty($oficina) && stripos($rdoTurno['oficina'], $oficina) === false) {
                    continue; // Filtro por oficina aplicado
                }

                $url = 'generar.php?NroDoc=' . $rdoTurno['nro_doc'] .
                    "&ntitu=" . urlencode($rdoTurno['ntitular']) .
                    "&nrorub=" . $rdoTurno['nrorub'] .
                    "&numeroConsulta=" . $rdoTurno['numeroconsulta'] .
                    "&evaluada=" . $rdoTurno['registrado_eva'] .
                    "&idpersonahogar=" . $rdoTurno['idpersonahogar'] .
                    "&idhogar=" . $rdoTurno['idhogar'] .
                    "&hora=" . urlencode($rdoTurno['fecha'] . ' ' . $rdoTurno['hora']);

                echo "<tr onmouseover='ChangeColor(this, true);' onmouseout='ChangeColor(this, false);' onclick=\"DoNav('$url')\">";
                echo '<td><input type="checkbox" class="row-check" onclick="event.stopPropagation();"></td>';
                echo '<td>' . $rdoTurno['apellido'] . '</td>';
                echo '<td>' . $rdoTurno['nombre'] . '</td>';
                echo '<td>' . $rdoTurno['nro_doc'] . '</td>';
                echo '<td>' . $rdoTurno['fecha'] . '</td>';
                echo '<td>' . $rdoTurno['hora'] . '</td>';
                echo '<td>' . $rdoTurno['estado'] . '</td>';
                echo '<td>' . $rdoTurno['categoria'] . '</td>';
                echo '<td>' . ($rdoTurno['resolucion'] ?? '') . '</td>';
                echo '<td>' . ($rdoTurno['registrado_eva'] ?? '') . '</td>';
                echo '<td>' . $rdoTurno['oficina'] . '</td>';
                echo '<td>' . $rdoTurno['nombreusuario'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br>';
            echo "<i>Cantidad de turnos</i>: <b>" . count($turnos->getResult()) . "</b></p>";
        }
    }

    /** 🔍 Búsqueda por Oficina **/
    elseif ($searchBy === 'oficina' && !empty($oficina)) {
        require_once 'TurnosFecha_revision.php'; // Usamos la misma clase
        try {
            $turnos = new TurnosFecha('', $tipo); // Sin fecha, traemos todos (o según tu lógica)
        } catch (Throwable $e) {
            echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
            $turnos = null;
        }

        if (!is_object($turnos) || count($turnos->getResult()) === 0) {
            echo "<p>No se encontraron turnos para la oficina seleccionada.</p>";
        } else {
            echo "<h3>Resultados para la oficina: <b>" . htmlspecialchars($oficina) . "</b></h3>";
            echo '<table id="tabla-turnos" border="1" cellpadding="4" cellspacing="0">';
            echo '<thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Nro. Documento</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Categoria</th>
                        <th>Resolución</th>
                        <th>Evaluada</th>
                        <th>Oficina</th>
                        <th>Usuario Carga</th>
                    </tr>
                  </thead>
                  <tbody>';

            foreach ($turnos->getResult() as $rdoTurno) {
                if (stripos($rdoTurno['oficina'], $oficina) === false) {
                    continue;
                }

                echo '<tr>';
                echo '<td><input type="checkbox" class="row-check" onclick="event.stopPropagation();"></td>';
                echo '<td>' . $rdoTurno['apellido'] . '</td>';
                echo '<td>' . $rdoTurno['nombre'] . '</td>';
                echo '<td>' . $rdoTurno['nro_doc'] . '</td>';
                echo '<td>' . $rdoTurno['fecha'] . '</td>';
                echo '<td>' . $rdoTurno['hora'] . '</td>';
                echo '<td>' . $rdoTurno['estado'] . '</td>';
                echo '<td>' . $rdoTurno['categoria'] . '</td>';
                echo '<td>' . ($rdoTurno['resolucion'] ?? '') . '</td>';
                echo '<td>' . ($rdoTurno['registrado_eva'] ?? '') . '</td>';
                echo '<td>' . $rdoTurno['oficina'] . '</td>';
                echo '<td>' . $rdoTurno['nombreusuario'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table><br>';
        }
    } else {
        echo "<p>DEBUG: No se especificaron criterios válidos o parámetros insuficientes.</p>";
    }
} else {
    echo "<p>DEBUG: No se recibieron parámetros de búsqueda.</p>";
}

echo '</div>'; // 🔹 Cierre del contenedor
?>
