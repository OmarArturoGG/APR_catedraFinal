<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'alumno') {
    header('Location: ../index.php');
    exit;
}

$alumno_id = $_SESSION['usuario']['id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tutorías Pendientes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-alumno">
        <a href="alumno.php" class="btn-volver">← Volver</a>
        
        <h2>Mis Tutorías Pendientes</h2>
        
        <?php
        include_once 'models/TutoriaModel.php';
        $model = new TutoriaModel();
        
        // Obtener solo tutorías PENDIENTES y FUTURAS
        $conexion = new Conexion();
        $db = $conexion->conectar();
        
        $sql = "SELECT t.*, u.nombre as profesor_nombre, m.nombre as materia_nombre 
                FROM tutorias t 
                JOIN usuarios u ON t.profesor_id = u.id 
                JOIN materias m ON t.materia_id = m.id 
                WHERE t.alumno_id = $alumno_id 
                AND t.estado = 'pendiente'
                AND t.fecha > NOW()
                ORDER BY t.fecha ASC";
        
        $resultado = $db->query($sql);
        $tutorias = $resultado->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($tutorias)) {
            echo "<p>No tienes tutorías pendientes.</p>";
        } else {
            echo "<div class='horarios-list'>";
            foreach ($tutorias as $tutoria) {
                $fecha = date('d/m/Y h:i A', strtotime($tutoria['fecha']));
                
                echo "<div class='horario-item'>";
                echo "<h4>Tutoría de " . $tutoria['materia_nombre'] . "</h4>";
                echo "<p><strong>Profesor:</strong> " . $tutoria['profesor_nombre'] . "</p>";
                echo "<p><strong>Fecha y hora:</strong> " . $fecha . "</p>";
                
                echo "<div style='margin-top: 10px;'>";
                echo "<button style='padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;' onclick='cancelarTutoria(" . $tutoria['id'] . ")'>Cancelar Tutoría</button>";
                echo "</div>";
                
                echo "</div>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <script>
        function cancelarTutoria(tutoria_id) {
            if (confirm('¿Estás seguro de cancelar esta tutoría?')) {
                window.location = 'cancelar_tutoria.php?id=' + tutoria_id;
            }
        }
    </script>
</body>
</html>