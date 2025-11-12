

<?php
ob_start();
?>

<style>
/* 🎨 Estilos visuales */
.contenedor-principal {
    width: 100%;
    text-align: left;
    overflow-x: auto;
}
.contenedor-turnos {
    display: inline-block;
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 20px auto;
}
#tabla-turnos {
    border-collapse: collapse;
    table-layout: auto;
    width: 100%;
}
#tabla-turnos thead {
    background-color: #007bff;
    color: white;
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
#select-all {
    transform: scale(1.2);
}
th.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 18px;
}
th.sortable::after {
    content: "⇅";
    position: absolute;
    right: 6px;
    color: #fff;
    font-size: 12px;
}
th.sortable.asc::after { content: "↑"; }
th.sortable.desc::after { content: "↓"; }
</style>

<script>
// ✅ Checkbox general
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.querySelector('#select-all');
    const checkboxes = document.querySelectorAll('.row-check');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(chk => chk.checked = this.checked);
        });
    }

    // ✅ Ordenamiento de columnas
    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', function () {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const index = Array.from(th.parentNode.children).indexOf(th);
            const isAsc = !th.classList.contains('asc');
            
            // Limpiamos clases en todos los headers
            table.querySelectorAll('th.sortable').forEach(h => h.classList.remove('asc', 'desc'));
            th.classList.add(isAsc ? 'asc' : 'desc');
            
            // Obtenemos filas y ordenamos
            const filas = Array.from(tbody.querySelectorAll('tr')).sort((a, b) => {
                const valorA = a.cells[index].textContent.trim();
                const valorB = b.cells[index].textContent.trim();

                // 👇 Si es la columna "Fecha", la comparamos como fecha real
                if (th.textContent.includes("Fecha")) {
                    // Convierte "dd/mm/yyyy" a objeto Date
                    const parseFecha = (str) => {
                        const [dia, mes, anio] = str.split('/');
                        return new Date(`${anio}-${mes}-${dia}`);
                    };
                
                    const fechaA = parseFecha(valorA);
                    const fechaB = parseFecha(valorB);
                
                    return isAsc ? fechaA - fechaB : fechaB - fechaA;
                }

                // 👇 Si no, comparamos como texto normal
                return isAsc
                    ? valorA.localeCompare(valorB)
                    : valorB.localeCompare(valorA);
            });

            // Reinsertamos las filas ordenadas
            filas.forEach(fila => tbody.appendChild(fila));
        });
    });
});

// ✅ Filtros dinámicos
function filtrarTurnos() {
    const filtroOficina = document.getElementById('filtroOficina').value.toLowerCase();
    const filtroEvaluada = document.getElementById('filtroEvaluada').value.toLowerCase();
    const filas = document.querySelectorAll('#tabla-turnos tbody tr');

    filas.forEach(fila => {
        const evaluada = fila.cells[9].textContent.toLowerCase().trim();
        const oficina = fila.cells[10].textContent.toLowerCase().trim();
        const coincideOficina = !filtroOficina || oficina.includes(filtroOficina);
        const coincideEvaluada = !filtroEvaluada || evaluada.includes(filtroEvaluada);
        fila.style.display = (coincideOficina && coincideEvaluada) ? '' : 'none';
    });
}

function resetFiltros() {
    document.getElementById('filtroOficina').value = '';
    document.getElementById('filtroEvaluada').value = '';
    document.querySelectorAll('#tabla-turnos tbody tr').forEach(fila => fila.style.display = '');
}

// ✅ Cambiar color de fila al pasar el mouse
function ChangeColor(row, highlight) {
    row.style.backgroundColor = highlight ? '#f0f0f0' : '';
}

// ✅ Redirección al hacer clic
function DoNav(url) {
    window.location = url;
}
</script>


<?php
require_once 'entrevistas.class.php';
$tipo = 'inte';
echo '<div class="contenedor-turnos">';

/* 🧩 Control principal */
if (isset($_GET['search_by'])) {
    $searchBy = $_GET['search_by'];
    $fecha = $_GET['fecha'] ?? '';
    $nrodoc = $_GET['nrodoc'] ?? '';
    $ntitular = $_GET['ntitular'] ?? '';
    $eva = $_GET['evaluada'] ?? '';
    $oficina = $_GET['oficina'] ?? '';
    $tipo = $_GET['tipo'] ?? 'inte';

    /* 🔍 Por Nro Doc o Titular */
    if (($searchBy === 'ndoc' && !empty($nrodoc)) || ($searchBy === 'ntitu' && !empty($ntitular))) {
        $personasObj = new Personas($ntitular, $nrodoc, '', '');
        $personas = $personasObj->getData();

        if (!empty($personas)) {
            foreach ($personas as $persona) {
                echo '<p><b>' . $persona->APELLIDO . ', ' . $persona->NOMBRE . '</b></p>';
                $idhogar = $persona->idhogar ?? '';
                $nrorub = $persona->nrorub ?? '';
                $turnosObj = new Turnos($idhogar, 'inte');
                $turnos = $turnosObj->getData();

                echo '<table id="tabla-turnos"><thead><tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th class="sortable">Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Categoria</th>
                        <th>Resolución</th>
                        <th>Evaluada</th>
                        <th>Oficina</th>
                        <th>Usuario Carga</th>
                      </tr></thead><tbody>';

                foreach ($turnos as $turno) {
                    $url = 'generar.php?NroDoc=' . $turno->nro_doc .
                        '&ntitu=' . urlencode($turno->ntitular) .
                        '&idhogar=' . $turno->idhogar .
                        '&nrorub=' . $nrorub .
                        '&hora=' . urlencode($turno->fecha . ' ' . $turno->hora) .
                        '&numeroConsulta=' . $turno->numeroconsulta .
                        '&idpersonahogar=' . $turno->idpersonahogar .
                        '&evaluada=' . $turno->registrado_eva;

                    echo "<tr onclick=\"DoNav('$url')\" onmouseover='ChangeColor(this,true)' onmouseout='ChangeColor(this,false)'>";
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
                echo '</tbody></table>';
            }
        } else {
            echo "<p>No se encuentran inscriptos con los datos ingresados.</p>";
        }
    }

    /* 🔍 Por Fecha o Evaluada */
    elseif (($searchBy === 'fecha' && !empty($fecha)) || ($searchBy === 'eva' && !empty($eva))) {
        // ✅ Manejo de fecha seguro
        $fechaFormateada = '';
        if (!empty($fecha)) {
            $fechaRaw = str_replace('/', '-', trim($fecha));
            $ts = strtotime($fechaRaw);
            if ($ts) {
                $fechaFormateada = date('d-m-Y', $ts);
            }
        }

        require_once 'TurnosFecha_revision.php';
        try {
            $turnos = new TurnosFecha($fechaFormateada, $tipo, $eva);
        } catch (Throwable $e) {
            echo "<p style='color:red;'>Error cargando turnos: {$e->getMessage()}</p>";
            $turnos = null;
        }

        if (!$turnos || count($turnos->getResult()) === 0) {
            echo "<p>No se encontraron turnos para la fecha seleccionada.</p>";
        } else {
            $resultados = $turnos->getResult();
            $oficinas = array_unique(array_column($resultados, 'oficina'));
            $evaluadas = array_unique(array_column($resultados, 'registrado_eva'));
            sort($oficinas);
            sort($evaluadas);

            echo '<div style="margin:10px 0;">
                    <label><b>Oficina:</b></label>
                    <select id="filtroOficina" onchange="filtrarTurnos()">
                        <option value="">-- Todas --</option>';
            foreach ($oficinas as $of) {
                echo '<option value="' . htmlspecialchars($of) . '">' . htmlspecialchars($of) . '</option>';
            }
            echo '</select>
                <label style="margin-left:10px;"><b>Evaluada:</b></label>
                <select id="filtroEvaluada" onchange="filtrarTurnos()">
                    <option value="">-- Todas --</option>';
            foreach ($evaluadas as $ev) {
                echo '<option value="' . htmlspecialchars($ev) . '">' . htmlspecialchars($ev) . '</option>';
            }
            echo '</select>
                <button onclick="resetFiltros()" style="margin-left:12px;padding:6px 10px;background:#dc3545;color:#fff;border:none;border-radius:4px;cursor:pointer;">Reset filtros</button>
                </div>';

            echo '<table id="tabla-turnos"><thead><tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Apellido</th><th>Nombre</th><th>Nro. Documento</th>
                    <th class="sortable">Fecha</th><th>Hora</th><th>Estado</th>
                    <th>Categoria</th><th>Resolución</th><th>Evaluada</th>
                    <th>Oficina</th><th>Usuario Carga</th>
                  </tr></thead><tbody>';

            foreach ($resultados as $r) {
                $url = 'generar.php?NroDoc=' . $r['nro_doc'] .
                    "&ntitu=" . urlencode($r['ntitular']) .
                    "&idhogar=" . $r['idhogar'] .
                    '&nrorub=' . $r['nrorub'] .
                    
                    '&numeroConsulta=' . $r['numeroconsulta'] .
                    '&idpersonahogar=' . $r['idpersonahogar'] .
                    
                    "&hora=" . urlencode($r['fecha'] . ' ' . $r['hora']) .
                    "&evaluada=" . $r['registrado_eva'];

                echo "<tr onclick=\"DoNav('$url')\" onmouseover='ChangeColor(this,true)' onmouseout='ChangeColor(this,false)'>";
                echo '<td><input type="checkbox" class="row-check" onclick="event.stopPropagation();"></td>';
                echo '<td>' . htmlspecialchars($r['apellido']) . '</td>';
                echo '<td>' . htmlspecialchars($r['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($r['nro_doc']) . '</td>';
                echo '<td>' . htmlspecialchars($r['fecha']) . '</td>';
                echo '<td>' . htmlspecialchars($r['hora']) . '</td>';
                echo '<td>' . htmlspecialchars($r['estado']) . '</td>';
                echo '<td>' . htmlspecialchars($r['categoria']) . '</td>';
                echo '<td>' . htmlspecialchars($r['resolucion'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($r['registrado_eva'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($r['oficina']) . '</td>';
                echo '<td>' . htmlspecialchars($r['nombreusuario']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo "<p><i>Cantidad de turnos:</i> <b>" . count($resultados) . "</b></p>";
        }
    }
} else {
    echo "<p>No se recibieron parámetros de búsqueda.</p>";
}

echo '</div>';
?>

