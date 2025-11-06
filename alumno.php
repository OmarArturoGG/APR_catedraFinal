<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'alumno') {
    header('Location: index.php');
    exit;
}

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'inicio';

include_once 'models/TutoriaModel.php';
$tutoriaModel = new TutoriaModel();

switch ($pagina) {
    case 'inicio':
        include 'views/alumnos/inicio.php';
        break;
        
    case 'reservar':
        // Obtener materias normales (sin info de profesores)
        $materias = $tutoriaModel->obtenerMaterias();
        $profesores = $tutoriaModel->obtenerProfesores();
        
        // Procesar reserva si se envió el formulario
        if ($_POST) {
            $profesor_id = $_POST['profesor_id'];
            $materia_id = $_POST['materia_id'];
            $fecha = $_POST['fecha'];
            
            $resultado = $tutoriaModel->reservarTutoria(
                $_SESSION['usuario']['id'],
                $profesor_id,
                $materia_id,
                $fecha
            );
            
            if ($resultado) {
                header('Location: alumno.php?pagina=pendientes&mensaje=Tutoría reservada correctamente');
                exit;
            } else {
                $error = "Error al reservar la tutoría";
            }
        }
        
        include 'views/alumnos/reservar.php';
        break;
        
    case 'pendientes':
        include 'views/alumnos/pendientes.php';
        break;
        
    case 'historial':
        include 'views/alumnos/historial.php';
        break;
        
    default:
        include 'views/alumnos/inicio.php';
        break;
}
?>