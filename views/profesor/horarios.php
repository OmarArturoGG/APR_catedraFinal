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
    <title>Mis Horarios</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-alumno">
        <a href="profesor.php" class="btn-volver">← Volver</a>
        
        <h2>Mis Horarios Disponibles</h2>
        
        <?php
        include_once 'models/TutoriaModel.php';
        $model = new TutoriaModel();
        
        // Obtener horarios del profesor
        $conexion = new Conexion();
        $db = $conexion->conectar();
        
        $sql = "SELECT hp.*, m.nombre as materia_nombre 
                FROM horarios_profesores hp 
                JOIN materias m ON hp.materia_id = m.id 
                WHERE hp.profesor_id = $profesor_id 
                ORDER BY hp.dia_semana, hp.hora_inicio";
        
        $resultado = $db->query($sql);
        $horarios = $resultado->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($horarios)) {
            echo "<p>No tienes horarios disponibles.</p>";
        } else {
            echo "<div class='horarios-list'>";
            foreach ($horarios as $horario) {
                echo "<div class='horario-item'>";
                echo "<h4>" . $horario['materia_nombre'] . "</h4>";
                echo "<p><strong>Día:</strong> " . $horario['dia_semana'] . "</p>";
                echo "<p><strong>Hora:</strong> " . date('h:i A', strtotime($horario['hora_inicio'])) . " - " . date('h:i A', strtotime($horario['hora_fin'])) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 30px;">
            <h3>Agregar nuevo horario:</h3>
            <form method="POST" action="guardar_horarios_semana.php">
                <div style="margin: 15px 0;">
                    <label><input type="checkbox" name="dias_semana[]" value="Lunes"> Lunes</label><br>
                    <label><input type="checkbox" name="dias_semana[]" value="Martes"> Martes</label><br>
                    <label><input type="checkbox" name="dias_semana[]" value="Miércoles"> Miércoles</label><br>
                    <label><input type="checkbox" name="dias_semana[]" value="Jueves"> Jueves</label><br>
                    <label><input type="checkbox" name="dias_semana[]" value="Viernes"> Viernes</label><br>
                    <label><input type="checkbox" name="dias_semana[]" value="Sábado"> Sábado</label>
                </div>
                
                <label>Hora inicio:</label>
                <select name="hora_inicio" required>
                    <option value="07:00:00">7:00 AM</option>
                    <option value="08:00:00">8:00 AM</option>
                    <option value="09:00:00">9:00 AM</option>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="11:00:00">11:00 AM</option>
                    <option value="12:00:00">12:00 PM</option>
                    <option value="13:00:00">1:00 PM</option>
                    <option value="14:00:00">2:00 PM</option>
                    <option value="15:00:00">3:00 PM</option>
                    <option value="16:00:00">4:00 PM</option>
                    <option value="17:00:00">5:00 PM</option>
                </select>
                
                <label>Hora fin:</label>
                <select name="hora_fin" required>
                    <option value="08:00:00">8:00 AM</option>
                    <option value="09:00:00">9:00 AM</option>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="11:00:00">11:00 AM</option>
                    <option value="12:00:00">12:00 PM</option>
                    <option value="13:00:00">1:00 PM</option>
                    <option value="14:00:00">2:00 PM</option>
                    <option value="15:00:00">3:00 PM</option>
                    <option value="16:00:00">4:00 PM</option>
                    <option value="17:00:00">5:00 PM</option>
                    <option value="18:00:00">6:00 PM</option>
                </select>
                
                <label>Materia:</label>
                <select name="materia_id" required>
                    <option value="">Selecciona una materia</option>
                    <?php
                    $materias = $model->obtenerMaterias();
                    foreach ($materias as $materia) {
                        echo "<option value='{$materia['id']}'>{$materia['nombre']}</option>";
                    }
                    ?>
                </select>
                
                <button type="submit">Agregar Horarios</button>
            </form>
        </div>
    </div>
</body>
</html>