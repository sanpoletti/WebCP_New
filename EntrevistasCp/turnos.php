<?php
// turnos.php - Búsqueda y muestra de turnos según diferentes criterios

// Funciones JS necesarias para interactividad
echo <<<JS
<script>
function DoNav(url) {
    window.location = url;
}
function ChangeColor(row, highlight) {
    row.style.backgroundColor = highlight ? '#f0f0f0' : '';
}
function filtrarOficina() {
    const filtro = document.getElementById('filtroOficina').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaTurnos tbody tr');
    
    filas.forEach(fila => {
        const oficina = fila.cells[8].textContent.toLowerCase(); // Columna Oficina (índice 8)
        fila.style.display = (filtro === '' || oficina === filtro) ? '' : 'none';
    });
}
</script>
JS;

require_once 'entrevistas.class.php';

$tipo = 'inte';
if (isset($_GET['search_by'])) {
    $searchBy = $_GET['search_by'];
    $fecha = $_GET['fecha'] ?? '';
    $nrodoc = $_GET['nrodoc'] ?? '';
    $ntitular = $_GET['ntitular'] ?? '';
    $tipo = $_GET['tipo'] ?? 'inte';
    
    if (($searchBy === 'ndoc' && !empty($nrodoc)) || ($searchBy === 'ntitu' && !empty($ntitular))) {
        $personasObj = new Personas($ntitular, $nrodoc,'','');
        $personas = $personasObj->getData();
        
        if (!empty($personas)) {
            foreach ($personas as $persona) {
                $ntitular =  $persona->idhogar;
                echo '<p>' .$persona->APELLIDO . ', ' . $persona->NOMBRE . ', ' . $persona->nrotitular  . '</p>';
                $nrorub          = $persona->nrorub;
                $idhogar         = $persona->idhogar;
                $numeroConsulta  = 0;
                $idpersonahogar  = $persona->IDPERSONAHOGAR;
                
                $entrevistas = new Entrevistas(
                    $ntitular,
                    $idhogar,
                    $nrorub,
                    $numeroConsulta,
                    $idpersonahogar
                    );
                
                echo '<table border="1" cellpadding="4" cellspacing="0">';
                echo '<thead><tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Categoria</th><th>Resolución</th><th>Oficina</th><th>Usuario Carga</th></tr></thead><tbody>';
                
                $turnosObj = new Turnos($idhogar, 'inte');
                $turnos = $turnosObj->getData();
                
                foreach ($turnos as $turno) {
                    $url = 'generar.php?NroDoc=' . $turno->nro_doc .
                    '&ntitu=' . urlencode($turno->ntitular) .
                    '&idhogar=' . $turno->idhogar .
                    '&nrorub=' . $nrorub .
                    '&hora=' . urlencode($turno->fecha . ' ' .$turno->hora) .
                    '&numeroConsulta=' . $turno->numeroconsulta.
                    '&idpersonahogar=' . $turno->idpersonahogar .
                    '&evaluada=' . $turno->registrado_eva;
                    
                    echo "<tr onmouseover='ChangeColor(this, true);' onmouseout='ChangeColor(this, false);' onclick=\"DoNav('$url')\">";
                    echo '<td>' . htmlspecialchars($turno->fecha) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->hora) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->estado) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->categoria) . '</td>';
                    echo '<td>' . htmlspecialchars($turno->resolucion) . '</td>';
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
    
    elseif ($searchBy === 'fecha' && !empty($fecha)) {
        $fechaRaw = $_GET['fecha'] ?? '';
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
        
        require_once 'TurnosFecha.php';
        try {
            $turnos = new TurnosFecha($fechaFormateada, $tipo);
            
            if (!is_object($turnos)) {
                echo "<p>Error: No se pudieron cargar los turnos para la fecha {$fecha}</p>";
            } else {
                echo "<p>Cantidad de turnos: " . count($turnos->getResult()) . "</p>";
            }
        } catch (Throwable $e) {
            echo "<pre style='color:red;'>";
            echo "ERROR: Excepción capturada: " . $e->getMessage() . "\n";
            echo "Archivo: " . $e->getFile() . "\n";
            echo "Línea: " . $e->getLine() . "\n";
            echo "Trace:\n" . $e->getTraceAsString();
            echo "</pre>";
            $turnos = null;
        }
        
        if (!is_object($turnos)) {
            echo "<p>Error cargando turnos por fecha.</p>";
        } elseif (count($turnos->getResult()) === 0) {
            echo "<p>No se encontraron turnos para la fecha seleccionada.</p>";
        } else {
            // 🔹 Agregar filtro por oficina dinámico
            $oficinas = array_unique(array_map(function($t) {
                return $t['oficina'];
            }, $turnos->getResult()));
                sort($oficinas);
                
                echo '<div style="margin:10px 0;">';
                echo '<label for="filtroOficina"><b>Oficina:</b></label> ';
                echo '<select id="filtroOficina" onchange="filtrarOficina()" style="padding:4px; margin-left:8px;">';
                echo '<option value="">-- Todas --</option>';
                foreach ($oficinas as $of) {
                    echo '<option value="'.htmlspecialchars($of).'">'.htmlspecialchars($of).'</option>';
                }
                echo '</select>';
                echo '</div>';
                
                // Tabla con ID para filtrar
                echo '<table id="tablaTurnos" border="1" cellpadding="4" cellspacing="0">';
                echo '<thead><tr><th>Apellido</th><th>Nombre</th><th>Nro. Documento</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Categoria</th><th>Resolución</th><th>Oficina</th><th>Usuario Carga</th></tr></thead><tbody>';
                
                $url_all = "generar.php?";
                $last_ntitu = '';
                
                foreach ($turnos->getResult() as $rdoTurno) {
                    $ntitu = $rdoTurno['ntitular'];
                    $nrub = $rdoTurno['nrorub'];
                    $numeroConsulta = $rdoTurno['numeroconsulta'];
                    $idhogar = $rdoTurno['idhogar'];
                    $idpersonahogar = $rdoTurno['idpersonahogar'];
                    $reg_evaluada = $rdoTurno['registrado_eva'];
                    
                    $url = 'generar.php?NroDoc=' . $rdoTurno['nro_doc'] .
                    "&ntitu=" . urlencode($ntitu) .
                    "&nrorub=$nrub&numeroConsulta=$numeroConsulta&evaluada=$reg_evaluada&idpersonahogar=$idpersonahogar&idhogar=$idhogar&hora=" .
                    urlencode($rdoTurno['fecha'] . ' ' . $rdoTurno['hora']);
                    
                    echo "<tr onmouseover='ChangeColor(this, true);' onmouseout='ChangeColor(this, false);' onclick=\"DoNav('$url')\">";
                    echo '<td>' . $rdoTurno['apellido'] . '</td>';
                    echo '<td>' . $rdoTurno['nombre'] . '</td>';
                    echo '<td>' . $rdoTurno['nro_doc'] . '</td>';
                    echo '<td>' . $rdoTurno['fecha'] . '</td>';
                    echo '<td>' . $rdoTurno['hora'] . '</td>';
                    echo '<td>' . $rdoTurno['estado'] . '</td>';
                    echo '<td>' . $rdoTurno['categoria'] . '</td>';
                    echo '<td>' . ($rdoTurno['resolucion'] ?? '') . '</td>';
                    echo '<td>' . $rdoTurno['oficina'] . '</td>';
                    echo '<td>' . $rdoTurno['nombreusuario'] . '</td>';
                    echo '</tr>';
                    
                    if ($last_ntitu != $ntitu) {
                        $hora = $rdoTurno['fecha'] . ' ' . $rdoTurno['hora'];
                        $url_all .= "ntitulares[]=" . urlencode($ntitu) . "&rubs[]=$nrub&horas[]=" . urlencode($hora) . "&idhogar[]=$idhogar&";
                        $last_ntitu = $ntitu;
                    }
                }
                
                echo '</tbody></table><br>';
                
                $url_all = rtrim($url_all, '&');
                echo "<p><a href='$url_all'><img src='images/vertodo.gif' alt='Ver todo' /></a>&nbsp;&nbsp;&nbsp;<i>Cantidad de turnos</i>: <b>" . count($turnos->getResult()) . "</b></p>";
        }
    } else {
        echo "<p>DEBUG: No se especificaron criterios válidos o parámetros insuficientes.</p>";
    }
} else {
    echo "<p>DEBUG: No se recibieron parámetros de búsqueda.</p>";
}
?>

