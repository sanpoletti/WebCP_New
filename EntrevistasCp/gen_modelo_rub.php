<?php

// Protejo que este seteado el NTITUAR
if (isset($_GET['rubs']) && !empty($_GET['rubs'])){
   
    
    
    $idpersonahogar   = $_GET['idpersonahogar'] ?? '';
    $numeroConsulta   = $_GET['numeroConsulta'] ?? '';
    $nrorub           = $_GET['rubs'] ?? '';
    $idhogar          = $_GET['idhogar'] ?? '';
    $ntitu            = $_GET['ntitu'] ?? '';
    
 //   echo "ntitularfdf = " . ($_GET['ntitu'] ?? 'NO LLEGA') . "\n";
//	echo "ERROR: No esta seteado \$ntitu";
//	die();
}

include_once 'Entrevistas.class.php';

//$fr = new Entrevistas($ntitu, $nrorub);
$sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);

//echo "ntitular = " . ($_GET['ntitu'] ?? 'NO LLEGA') . "\n";

?>

