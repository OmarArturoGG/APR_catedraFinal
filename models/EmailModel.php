<?php
class EmailModel {
    
    public function enviarEmail($destinatario, $asunto, $mensaje) {
        // Para desarrollo local, simulamos el envío guardando en un log
        // En producción usarías mail() o PHPMailer
        
        $log = "=== EMAIL SIMULADO ===\n";
        $log .= "Para: $destinatario\n";
        $log .= "Asunto: $asunto\n";
        $log .= "Mensaje: $mensaje\n";
        $log .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $log .= "=====================\n\n";
        
        // Guardar en archivo log (para ver en desarrollo)
        file_put_contents('emails.log', $log, FILE_APPEND);
        
        // En producción, descomentar esto:
        // return mail($destinatario, $asunto, $mensaje, $headers);
        
        return true; // Simulamos que se envió correctamente
    }
    
    public function recordatorioTutoria($tutoria_id) {
    include_once 'db/conexion.php';
    $conexion = new Conexion();
    $db = $conexion->conectar();
    
    // Obtener datos de la tutoría
    $sql = "SELECT t.*, u_alumno.email as email_alumno, u_alumno.nombre as nombre_alumno, 
                   u_profesor.email as email_profesor, u_profesor.nombre as nombre_profesor,
                   m.nombre as materia_nombre
            FROM tutorias t
            JOIN usuarios u_alumno ON t.alumno_id = u_alumno.id
            JOIN usuarios u_profesor ON t.profesor_id = u_profesor.id
            JOIN materias m ON t.materia_id = m.id
            WHERE t.id = $tutoria_id";
    
    $tutoria = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
    
    if ($tutoria) {
        $fecha = date('d/m/Y h:i A', strtotime($tutoria['fecha']));
        
        // Email para el ALUMNO (más personalizado para recordatorio matutino)
        $asunto_alumno = "🔔 Recordatorio: Tu tutoría de hoy - " . $tutoria['materia_nombre'];
        $mensaje_alumno = "¡Buenos días " . $tutoria['nombre_alumno'] . "! 🌅\n\n";
        $mensaje_alumno .= "Te recordamos que **HOY** tienes una tutoría programada:\n\n";
        $mensaje_alumno .= "📚 Materia: " . $tutoria['materia_nombre'] . "\n";
        $mensaje_alumno .= "👨‍🏫 Profesor: " . $tutoria['nombre_profesor'] . "\n";
        $mensaje_alumno .= "🕐 Hora: " . $fecha . "\n\n";
        $mensaje_alumno .= "¡Te esperamos! 🎓\n";
        $mensaje_alumno .= "Sistema de Tutorías UDB";
        
     
        $asunto_profesor = "🔔 Recordatorio: Tutoría hoy con " . $tutoria['nombre_alumno'];
        $mensaje_profesor = "¡Buenos días Profesor " . $tutoria['nombre_profesor'] . "! 🌅\n\n";
        $mensaje_profesor .= "Le recordamos que **HOY** tiene una tutoría programada:\n\n";
        $mensaje_profesor .= "📚 Materia: " . $tutoria['materia_nombre'] . "\n";
        $mensaje_profesor .= "👨‍🎓 Alumno: " . $tutoria['nombre_alumno'] . "\n";
        $mensaje_profesor .= "🕐 Hora: " . $fecha . "\n\n";
        $mensaje_profesor .= "Sistema de Tutorías UDB";
        
        // Enviar emails
        $this->enviarEmail($tutoria['email_alumno'], $asunto_alumno, $mensaje_alumno);
        $this->enviarEmail($tutoria['email_profesor'], $asunto_profesor, $mensaje_profesor);
        
        return true;
    }
    
    return false;
}
    
    public function notificarReserva($tutoria_id) {
        include_once 'db/conexion.php';
        $conexion = new Conexion();
        $db = $conexion->conectar();
        
       
        $sql = "SELECT t.*, u_profesor.email as email_profesor, u_profesor.nombre as nombre_profesor,
                       u_alumno.nombre as nombre_alumno, m.nombre as materia_nombre
                FROM tutorias t
                JOIN usuarios u_profesor ON t.profesor_id = u_profesor.id
                JOIN usuarios u_alumno ON t.alumno_id = u_alumno.id
                JOIN materias m ON t.materia_id = m.id
                WHERE t.id = $tutoria_id";
        
        $tutoria = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        if ($tutoria) {
            $fecha = date('d/m/Y h:i A', strtotime($tutoria['fecha']));
            
            $asunto = "Nueva tutoría reservada - " . $tutoria['materia_nombre'];
            $mensaje = "Hola Profesor " . $tutoria['nombre_profesor'] . ",\n\n";
            $mensaje .= "Tiene una nueva tutoría reservada:\n";
            $mensaje .= "Alumno: " . $tutoria['nombre_alumno'] . "\n";
            $mensaje .= "Materia: " . $tutoria['materia_nombre'] . "\n";
            $mensaje .= "Fecha y hora: " . $fecha . "\n\n";
            $mensaje .= "Puede ver sus tutorías pendientes en el sistema.\n";
            $mensaje .= "Sistema de Tutorías UDB";
            
            return $this->enviarEmail($tutoria['email_profesor'], $asunto, $mensaje);
        }
        
        return false;
    }
}
?>