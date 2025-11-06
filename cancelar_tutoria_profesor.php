<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'profesor') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $tutoria_id = $_GET['id'];
    $profesor_id = $_SESSION['usuario']['id'];
    
    include_once 'db/conexion.php';
    $conexion = new Conexion();
    $db = $conexion->conectar();
    
   
    $sql_verificar = "SELECT * FROM tutorias WHERE id = $tutoria_id AND profesor_id = $profesor_id AND estado = 'pendiente'";
    $resultado = $db->query($sql_verificar);
    
    if ($resultado->rowCount() > 0) {
        

        
        $sql_cancelar = "UPDATE tutorias SET estado = 'cancelada' WHERE id = $tutoria_id";
        if ($db->exec($sql_cancelar)) {
            header('Location: profesor.php?pagina=tutorias&mensaje=Tutoría cancelada correctamente');
        } else {
            header('Location: profesor.php?pagina=tutorias&error=Error al cancelar la tutoría');
        }
    } else {
        header('Location: profesor.php?pagina=tutorias&error=No se puede cancelar esta tutoría');
    }
} else {
    header('Location: profesor.php?pagina=tutorias');
}
?>