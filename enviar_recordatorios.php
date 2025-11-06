<?php
include_once 'models/EmailModel.php';
include_once 'db/conexion.php';

function enviarRecordatoriosDelDia() {
    $conexion = new Conexion();
    $db = $conexion->conectar();
    $emailModel = new EmailModel();
    

    $sql = "SELECT t.*, u_alumno.email as email_alumno, u_alumno.nombre as nombre_alumno, 
                   u_profesor.email as email_profesor, u_profesor.nombre as nombre_profesor,
                   m.nombre as materia_nombre
            FROM tutorias t
            JOIN usuarios u_alumno ON t.alumno_id = u_alumno.id
            JOIN usuarios u_profesor ON t.profesor_id = u_profesor.id
            JOIN materias m ON t.materia_id = m.id
            WHERE t.estado = 'pendiente'
            AND DATE(t.fecha) >= CURDATE()  
            AND TIME(t.fecha) > '07:00:00'  
            AND t.recordatorio_enviado = 0";  
    
    $tutorias = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    $enviados = 0;
    foreach ($tutorias as $tutoria) {
        if ($emailModel->recordatorioTutoria($tutoria['id'])) {
   
            $sql_update = "UPDATE tutorias SET recordatorio_enviado = 1 WHERE id = " . $tutoria['id'];
            $db->exec($sql_update);
            $enviados++;
        }
    }
    
    return $enviados;
}


if (php_sapi_name() === 'cli' || isset($_GET['ejecutar'])) {
    $cantidad = enviarRecordatoriosDelDia();
    echo "Recordatorios enviados para hoy: $cantidad\n";
    
    
    if (file_exists('emails.log')) {
        echo "\n=== ÚLTIMOS EMAILS ===\n";
        echo file_get_contents('emails.log');
    }
}
?>