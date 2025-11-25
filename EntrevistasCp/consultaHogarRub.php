<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Hogar RUB</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function validar_form() {
            const texto = document.getElementById("search-text").value;
            if (!texto) {
                alert("Por favor, ingrese un valor para buscar.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-5xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-center mb-6">Consulta de Hogares RUB</h1>

            <form method="POST" onsubmit="return validar_form()" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <input type="text" name="search-text" id="search-text" placeholder="Ingrese número o apellido" class="flex-1 border rounded px-4 py-2">
                    <div class="flex gap-4">
                        <label><input type="radio" name="search_by" value="ntitu" checked> Ntitular</label>
                        <label><input type="radio" name="search_by" value="ndoc"> Documento</label>
                        <label><input type="radio" name="search_by" value="apellido"> Apellido</label>
                        <label><input type="radio" name="search_by" value="nroRub"> Nro Rub</label>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Buscar</button>
                </div>
            </form>

            <div class="mt-8">
                <?php
                require_once __DIR__ . '/../login/phpUserClass/access.class.php';
                $user = new flexibleAccess();
                
                if (!$user->is_loaded()) {
                    header("Location: /DGPOLA/login/index.php");
                    exit;
                }

                if (!isset($_GET['seguimiento_hogar'])) {
                    if (isset($_POST['search-text']) && !empty($_POST['search_by'])) {
                        $ntitu = $dni = $nroRub= $apellido = '';
                        if ($_POST['search_by'] == 'ntitu') {
                            $ntitu = $_POST['search-text'];
                        } elseif ($_POST['search_by'] == 'ndoc') {
                            $dni = $_POST['search-text'];
                        } elseif ($_POST['search_by'] == 'apellido') {
                            $apellido = $_POST['search-text'];
                        }elseif ($_POST['search_by'] == 'nroRub') {
                            $nroRub = $_POST['search-text'];
                        }
                        if (!empty($ntitu) || !empty($dni) || !empty($apellido) || !empty($nroRub)) {
                            include "entrevistas.class.php";
                            $personas = new Personas($ntitu, $dni, $apellido,$nroRub);

                            if (!$personas->isEmpty()) {
                                echo '<div class="mt-6 space-y-4">';
                                foreach ($personas->getData() as $rdo) {
                                    $url = "generar_rub.php?NroDoc={$rdo->getProperty('dni')}&ntitu={$rdo->getProperty('nrotitular')}&idpersonahogar={$rdo->getProperty('idpersonahogar')}&idhogar={$rdo->getProperty('idhogar')}&numpersona={$rdo->getProperty('numpersona')}&rubs={$rdo->getProperty('nrorub')}";
                                    //var_dump($rdo->getProperty('nrorub'));
                                    echo "<a href='$url' target='_blank' class='block p-3 bg-gray-100 rounded hover:bg-blue-50'>";
                                    echo "<strong>{$rdo->getProperty('APELLIDO')}, {$rdo->getProperty('NOMBRE')}</strong> | Ntitular: {$rdo->getProperty('nrotitular')} | {$rdo->getProperty('categoria')}";
                                    echo "</a>";
                                }
                                echo '</div>';
                            } else {
                                echo '<p class="mt-4 text-red-600">No se encuentran inscriptos con los datos ingresados.</p>';
                            }
                        }
                    } else {
                        echo '<p class="mt-4">Ingrese un criterio para iniciar la búsqueda.</p>';
                    }
                } else {
                    echo '<iframe src="http://www.buenosaires.gov.ar/areas/des_social/ciudadania_portenia/?menu_id=21936" width="100%" height="300"></iframe>';
                }
                ?>
            </div>
        </div>
    </div>
    <footer class="text-center mt-8 text-gray-500">2025 DGPOLA</footer>
</body>
</html>


