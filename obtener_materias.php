<?php
session_start();
include_once 'models/TutoriaModel.php';

if (isset($_GET['profesor_id'])) {
    $profesor_id = $_GET['profesor_id'];
    $model = new TutoriaModel();
    $materias = $model->obtenerMateriasPorProfesor($profesor_id);
    
    // DEBUG: Ver qué estamos obteniendo
    error_log("=== DEBUG OBTENER_MATERIAS ===");
    error_log("Profesor ID: " . $profesor_id);
    error_log("Materias encontradas: " . count($materias));
    error_log("Materias: " . print_r($materias, true));
    
    header('Content-Type: application/json');
    echo json_encode($materias);
} else {
    echo json_encode([]);
}
?>