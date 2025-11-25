<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/path.php';
require_once APP_ROOT . '/login/phpUserClass/access.class.php';
$user = new flexibleAccess();

if (!$user->is_loaded()) {
    header("Location: /DGPOLA/login/index.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Revision Entrevistas - Ciudadanía Porteña</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: #e3f2fd;
      font-family: 'Segoe UI', sans-serif;
    }
    header {
      background-color: #0d6efd;
      color: white;
      padding: 1.2rem;
      margin-bottom: 2rem;
      text-align: center;
    }
    .card {
      margin: auto;
      max-width: 95%; /* más ancho que antes */
    }
    .footer {
      text-align: center;
      padding: 1rem;
      background-color: #0d6efd;
      color: white;
      margin-top: 3rem;
    }

    /* Scroll superior e inferior */
    .table-scroll-top {
      overflow-x: auto;
      overflow-y: hidden;
      height: 20px;
      margin-bottom: 5px;
    }
    .table-scroll-bottom {
      overflow-x: auto;
    }
    /* Ajuste para que no rompa diseño */
    .scroll-container {
      max-width: 100%;
    }
    /* Opcional: bordes redondeados y sombra */
    .table-container {
      background: #fff;
      border-radius: 8px;
      padding: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    table {
      min-width: 1400px; /* Forzar scroll si hay muchas columnas */
    }
    th {
      background: #0d6efd;
      color: #fff;
      text-align: center;
    }
  </style>
</head>
<body>
<header>
  <h1>Revision de Entrevistas</h1>
  <p>Ciudadanía Porteña</p>
</header>

<div class="card p-4 shadow-sm">
  <form method="get" action="revisionEntrevista.php">
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Buscar por:</label>
      <div class="col-sm-10">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="search_by" value="fecha" id="rbFecha" checked onclick="searchBy('fechaDiv')">
          <label class="form-check-label" for="rbFecha">Fecha</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="search_by" value="ntitu" id="rbNtitular" onclick="searchBy('ntituDiv')">
          <label class="form-check-label" for="rbNtitular">Ntitular</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="search_by" value="ndoc" id="rbNdoc" onclick="searchBy('ndocDiv')">
          <label class="form-check-label" for="rbNdoc">Documento</label>
        </div>
        
        <?php
        $evaluada = isset($_GET['evaluada']) ? $_GET['evaluada'] : '';
        ?>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="search_by" value="eva" id="rbEva" onclick="searchBy('evaDiv')">
          <label class="form-check-label" for="rbEva">Evaluada</label>
            
         </div>
        
      </div>
    </div>

    <div class="row mb-3" id="fechaDiv">
      <label for="fecha" class="col-sm-2 col-form-label">Fecha:</label>
      <div class="col-sm-4">
        <input type="date" name="fecha" id="fecha" class="form-control">
      </div>
    </div>

    <div class="row mb-3 d-none" id="ndocDiv">
      <label for="nrodoc" class="col-sm-2 col-form-label">Documento:</label>
      <div class="col-sm-4">
        <input type="text" name="nrodoc" id="nrodoc" class="form-control">
      </div>
    </div>

    <div class="row mb-3 d-none" id="ntituDiv">
      <label for="ntitular" class="col-sm-2 col-form-label">Titular:</label>
      <div class="col-sm-4">
        <input type="text" name="ntitular" id="ntitular" class="form-control">
      </div>
    </div>
    
    
    <div class="row mb-3 d-none" id="evaDiv">
      <label for="eva" class="col-sm-2 col-form-label">Evaluada:</label>
      <div class="col-sm-4">
        <select name="evaluada" id="eva">
		        <option value="" <?php if ($evaluada == '') echo 'selected'; ?>></option>
            	<option value="NO" <?php if ($evaluada == 'NO') echo 'selected'; ?>>No</option>
            	<option value="SI" <?php if ($evaluada == 'SI') echo 'selected'; ?>>Si</option>
            </select>
      </div>
    </div>
    
    
    

    <div class="row">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
    </div>
  </form>
</div>









<div class="card mt-4 p-3 shadow-sm">
  <div class="menu mb-3">
  </div>

<div class="turnos-wrapper">
    <?php
      $option = $_GET['option'] ?? '';

        include 'turnos_revision.php';
     
    ?>
</div>

</div>

<div class="footer">
  <p>&copy; Ciudadanía Porteña - GCBA</p>
</div>

<script>
  function searchBy(divId) {
    document.getElementById('fechaDiv').classList.add('d-none');
    document.getElementById('ndocDiv').classList.add('d-none');
    document.getElementById('ntituDiv').classList.add('d-none');
	document.getElementById('evaDiv').classList.add('d-none');
    document.getElementById(divId).classList.remove('d-none');
  }
</script>
</body>
</html>

