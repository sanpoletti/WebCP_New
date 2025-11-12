<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Documentación Adjuntada del Hogar</title>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .contenedor {
      background: #fff;
      margin-top: 40px;
      width: 85%;
      max-width: 900px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      border-radius: 10px;
      overflow: hidden;
      animation: fadeIn 0.5s ease;
    }

    h2 {
      text-align: center;
      background-color: #007bff;
      color: white;
      margin: 0;
      padding: 15px;
      font-size: 1.4em;
      letter-spacing: 0.5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }

    th {
      background-color: #f1f1f1;
      font-weight: bold;
      color: #333;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .acciones a {
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 600;
      transition: background-color 0.2s;
    }

    .btn-ver {
      background-color: #28a745;
      color: white;
    }

    .btn-ver:hover {
      background-color: #218838;
    }

    .btn-eliminar {
      background-color: #dc3545;
      color: white;
    }

    .btn-eliminar:hover {
      background-color: #c82333;
    }

    p {
      text-align: center;
      color: #555;
      padding: 15px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <script>
    function Delete(archivo) {
      if (confirm('¿Estás seguro de eliminar este archivo?')) {
        document.location = 'borrar.php?archivo=' + archivo;
      }
    }
  </script>
</head>
<body>

<div class="contenedor">
  <h2>📂 Documentación Adjuntada del Hogar</h2>

  <?php
  session_start();
  $hora    = $_SESSION['hora'] ?? '';
  $numeroConsulta = $_SESSION['numeroConsulta'];
  $ntitu  = $_SESSION['ntitu'];
  $sec   = $_GET['sec'] ?? '';
  $idhogar= $_SESSION['idhogar'] ?? '';
  $nrorub = $_SESSION['nrorub'] ?? '';
  $uname  = $_SESSION['uname'] ?? '';
  $tipo   = $_GET['tipo'] ?? '';

  function lista_archivos($carpeta, $numeroConsulta) {
    //$server  = 'http://' . $_SERVER['HTTP_HOST'] . "/EntrevistasCp/uploads/doc_adjuntada/";
    $server  = 'http://' . $_SERVER['HTTP_HOST'] . "/DGPOLA/EntrevistasCp/uploads/doc_adjuntada/";
    
    
    
    //$carpeta = $_SERVER['DOCUMENT_ROOT'] . "/EntrevistasCp/uploads/doc_adjuntada/";
    
    $carpeta = $_SERVER['DOCUMENT_ROOT'] . "/DGPOLA/EntrevistasCp/uploads/doc_adjuntada/";
    

    if (is_dir($carpeta)) {
      if ($dir = opendir($carpeta)) {
        echo "<table>";
        echo "<tr><th>Documentación</th><th>Fecha</th><th colspan='2'>Acciones</th></tr>";

        $encontrado = false;

        while (($archivo = readdir($dir)) !== false) {
          if ($archivo != '.' && $archivo != '..' && $archivo != '.htaccess') {
            $comparacion = substr($archivo, 0, 6);
            if ($numeroConsulta == $comparacion) {
              $encontrado = true;
              $pdfFechaCreacion = $carpeta . $archivo;
              $nombre = substr($archivo, 6);
              $fecha = date("d/m/Y", filemtime($pdfFechaCreacion));
              $linkVer = $server . $archivo;

              echo "<tr>
                      <td>$nombre</td>
                      <td>$fecha</td>
                      <td class='acciones'><a class='btn-ver' href='$linkVer' target='_blank'>Ver</a></td>
                      <td class='acciones'><a class='btn-eliminar' href='javascript:Delete(\"$archivo\")'>Eliminar</a></td>
                    </tr>";
            }
          }
        }

        if (!$encontrado) {
          echo "<tr><td colspan='4'><p>No hay documentación adjunta.</p></td></tr>";
        }

        echo "</table>";
        closedir($dir);
      } else {
        echo "<p>No se pudo abrir la carpeta.</p>";
      }
    } else {
      echo "<p>No existe la carpeta de documentación.</p>";
    }
  }

  lista_archivos("./uploads/doc_adjuntada/", $numeroConsulta);
  ?>
</div>

</body>
</html>

