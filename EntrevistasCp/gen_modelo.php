<?php

// Protejo que este seteado el NTITUAR
//$rubs= 1325;
if ( !isset($ntitu) || !isset($nrorub)|| !isset($idhogar)|| !isset($numeroConsulta)|| !isset($idpersonahogar)) {
    //echo $ntitu;
    //echo $rubs;
    echo "ERROR";
    die();
}

include_once 'entrevistas.class.php';



// Creo objeto Seguimiento del Hogar (contendra todos los datos de la ficha)
//$sh = new Entrevistas($ntitu, $nrorub, $idhogar,$numeroConsulta,$idpersonahogar);
$sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);

?>