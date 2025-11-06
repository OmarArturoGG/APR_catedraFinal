<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'profesor') {
    header('Location: ../index.php');
    exit;
}

$profesor_id = $_SESSION['usuario']['id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tutorías</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-alumno">
        <a href="profesor.php" class="btn-volver">← Volver</a>
        
        <h2>Mis Tutorías Pendientes</h2>
        
        <?php
        include_once 'models/TutoriaModel.php';
        $model = new TutoriaModel();
        
        // Obtener tutorías del profesor
        $conexion = new Conexion();
        $db = $conexion->conectar();
        
        $sql = "SELECT t.*, u.nombre as alumno_nombre, m.nombre as materia_nombre 
                FROM tutorias t 
                JOIN usuarios u ON t.alumno_id = u.id 
                JOIN materias m ON t.materia_id = m.id 
                WHERE t.profesor_id = $profesor_id 
                ORDER BY t.fecha ASC";
        
        $resultado = $db->query($sql);
        $tutorias = $resultado->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($tutorias)) {
            echo "<p>No tienes tutorías pendientes.</p>";
        } else {
            echo "<div class='horarios-list'>";
            foreach ($tutorias as $tutoria) {
                echo "<div class='horario-item'>";
                echo "<h4>Tutoría de " . $tutoria['materia_nombre'] . "</h4>";
                echo "<p><strong>Alumno:</strong> " . $tutoria['alumno_nombre'] . "</p>";
                echo "<p><strong>Fecha y hora:</strong> " . date('d/m/Y h:i A', strtotime($tutoria['fecha'])) . "</p>";
                echo "<p><strong>Estado:</strong> " . $tutoria['estado'] . "</p>";
                
                // Botón para cancelar solo si está pendiente
                if ($tutoria['estado'] == 'pendiente') {
                    echo "<div style='margin-top: 10px;'>";
                    echo "<button style='padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;' onclick='cancelarTutoriaProfesor(" . $tutoria['id'] . ")'>Cancelar Tutoría</button>";
                    echo "</div>";
                }
                
                echo "</div>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <script>
        function cancelarTutoriaProfesor(tutoria_id) {
            if (confirm('¿Estás seguro de cancelar esta tutoría?')) {
                window.location = 'cancelar_tutoria_profesor.php?id=' + tutoria_id;
            }
        }
    </script>
</body>
</html>