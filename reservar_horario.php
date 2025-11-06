<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'alumno') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['horario_id'])) {
    $horario_id = $_GET['horario_id'];
    $alumno_id = $_SESSION['usuario']['id'];
    
    include_once 'models/TutoriaModel.php';
    $model = new TutoriaModel();
    
    $resultado = $model->reservarPorHorario($alumno_id, $horario_id);
    
    if ($resultado) {
        // NOTIFICAR AL PROFESOR
        include_once 'models/EmailModel.php';
        $emailModel = new EmailModel();
        $emailModel->notificarReserva($resultado); // Pasar ID de la tutoría creada
        
        header('Location: alumno.php?pagina=pendientes&mensaje=Tutoría reservada correctamente');
    } else {
        header('Location: alumno.php?pagina=reservar&error=Error al reservar la tutoría');
    }
} else {
    header('Location: alumno.php?pagina=reservar');
}
?>