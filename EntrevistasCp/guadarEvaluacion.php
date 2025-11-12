<?php

include_once 'entrevistas.class.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

try {
    $numeroConsulta = $_GET['numeroconsulta'] ?? null;
    $idhogar = $_GET['idhogar'] ?? null;
    $nrorub = $_GET['nrorub'] ?? null;
    $idpersonahogar = $_GET['idpersonahogar'] ?? null;
    $ntitu = $_GET['ntitu'] ?? null;
    $identrevista = $_GET['identrevista'] ?? 0;
    $observacion = trim($_GET['observacion'] ?? '');
    
    if (!$numeroConsulta || !$idhogar || !$nrorub || !$idpersonahogar || !$ntitu) {
        throw new Exception('Faltan parámetros.');
    }
    
    $sh = new Entrevistas($ntitu, $idhogar, $nrorub, $numeroConsulta, $idpersonahogar);
    $resultado = new AbmEvaluacionEntrevista($identrevista, $observacion, $_SESSION['username']);
    
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo insertar']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
