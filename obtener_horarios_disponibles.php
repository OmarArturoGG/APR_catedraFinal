<?php
session_start();
include_once 'db/conexion.php';
include_once 'models/TutoriaModel.php';

if (isset($_GET['profesor_id']) && isset($_GET['materia_id'])) {
    $profesor_id = $_GET['profesor_id'];
    $materia_id = $_GET['materia_id'];
    
    $conexion = new Conexion();
    $db = $conexion->conectar();
    
    // Obtener horarios del profesor para esta materia
    $sql = "SELECT hp.*, 
                   (SELECT COUNT(*) FROM tutorias t 
                    WHERE t.profesor_id = hp.profesor_id 
                    AND t.materia_id = hp.materia_id 
                    AND DATE(t.fecha) = DATE(CONCAT(CURDATE() + INTERVAL (FIELD(hp.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado') - 1) DAY, ' ', hp.hora_inicio))
                    AND t.estado = 'pendiente') as ocupado
            FROM horarios_profesores hp
            WHERE hp.profesor_id = $profesor_id 
            AND hp.materia_id = $materia_id
            ORDER BY hp.dia_semana, hp.hora_inicio";
    
    $resultado = $db->query($sql);
    $horarios = $resultado->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear respuesta
    $response = [];
    foreach ($horarios as $horario) {
        $response[] = [
            'id' => $horario['id'],
            'dia_semana' => $horario['dia_semana'],
            'hora_inicio' => date('h:i A', strtotime($horario['hora_inicio'])),
            'hora_fin' => date('h:i A', strtotime($horario['hora_fin'])),
            'ocupado' => $horario['ocupado'] > 0
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode([]);
}
?>