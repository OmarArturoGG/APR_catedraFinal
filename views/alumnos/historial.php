<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'alumno') {
    header('Location: ../index.php');
    exit;
}

$alumno_id = $_SESSION['usuario']['id'];

// Función para determinar el estado real (incluyendo fechas pasadas)
function obtenerEstadoReal($tutoria) {
    // Si ya está completada o cancelada, mantener ese estado
    if ($tutoria['estado'] == 'completada' || $tutoria['estado'] == 'cancelada') {
        return $tutoria['estado'];
    }
    
    // Si es pendiente pero la fecha ya pasó, marcarla como completada
    $fechaTutoria = strtotime($tutoria['fecha']);
    $ahora = time();
    
    if ($fechaTutoria < $ahora) {
        return 'completada';
    }
    
    return 'pendiente';
}

// Función para mostrar cada card de tutoría
function mostrarCardTutoria($tutoria, $estado_real) {
    $fecha = date('d/m/Y h:i A', strtotime($tutoria['fecha']));
    
    echo '<div class="card-tutoria ' . $estado_real . '">';
    echo '<div class="card-header">' . $tutoria['materia_nombre'] . '</div>';
    echo '<div class="card-info"><strong>Profesor:</strong> ' . $tutoria['profesor_nombre'] . '</div>';
    echo '<div class="card-info"><strong>Fecha:</strong> ' . $fecha . '</div>';
    echo '<div class="card-info"><strong>Estado:</strong> ' . ucfirst($estado_real) . '</div>';
    
    // Solo mostrar botón cancelar si está realmente pendiente (y no ha pasado la fecha)
    if ($estado_real == 'pendiente') {
        echo '<button class="btn-cancelar" onclick="cancelarTutoria(' . $tutoria['id'] . ')">Cancelar Tutoría</button>';
    }
    
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Historial</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .seccion-historial {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .seccion-titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .grid-tutorias {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .card-tutoria {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #007bff;
        }
        .card-tutoria.pendiente {
            border-left-color: #ffc107;
            background: #fff3cd;
        }
        .card-tutoria.completada {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .card-tutoria.cancelada {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .card-header {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .card-info {
            margin: 5px 0;
            font-size: 14px;
        }
        .btn-cancelar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 8px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="menu-alumno">
        <a href="alumno.php" class="btn-volver">← Volver al Inicio</a>
        
        <h2>Mi Historial de Tutorías</h2>
        
        <?php
        include_once 'models/TutoriaModel.php';
        $model = new TutoriaModel();
        $tutorias = $model->obtenerTutoriasAlumno($alumno_id);
        
        if (empty($tutorias)) {
            echo "<p>No tienes tutorías en tu historial.</p>";
        } else {
           


            foreach ($tutorias as &$tutoria) {
                $tutoria['estado_real'] = obtenerEstadoReal($tutoria);
            }
            
           
            
            $pendientes = array_filter($tutorias, function($t) { 
                return $t['estado_real'] == 'pendiente'; 
            });
            $completadas = array_filter($tutorias, function($t) { 
                return $t['estado_real'] == 'completada'; 
            });
            $canceladas = array_filter($tutorias, function($t) { 
                return $t['estado'] == 'cancelada'; 
            });
            
            // Sección PENDIENTES
            if (!empty($pendientes)) {
                echo '<div class="seccion-historial">';
                echo '<div class="seccion-titulo">📅 Tutorías Pendientes</div>';
                echo '<div class="grid-tutorias">';
                foreach ($pendientes as $tutoria) {
                    mostrarCardTutoria($tutoria, $tutoria['estado_real']);
                }
                echo '</div></div>';
            }
            
           
            if (!empty($completadas)) {
                echo '<div class="seccion-historial">';
                echo '<div class="seccion-titulo">✅ Tutorías Completadas</div>';
                echo '<div class="grid-tutorias">';
                foreach ($completadas as $tutoria) {
                    mostrarCardTutoria($tutoria, $tutoria['estado_real']);
                }
                echo '</div></div>';
            }
          
            if (!empty($canceladas)) {
                echo '<div class="seccion-historial">';
                echo '<div class="seccion-titulo">❌ Tutorías Canceladas</div>';
                echo '<div class="grid-tutorias">';
                foreach ($canceladas as $tutoria) {
                    mostrarCardTutoria($tutoria, $tutoria['estado_real']);
                }
                echo '</div></div>';
            }
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