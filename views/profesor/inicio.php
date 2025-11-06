<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'profesor') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-alumno">
        <h2>Bienvenido Profesor</h2>
        <p>Hola <?php echo $_SESSION['usuario']['nombre']; ?></p>
        
        <div class="opciones">
            <a href="profesor.php?pagina=horarios" class="opcion">
                Gestionar horarios
            </a>
            
            <a href="profesor.php?pagina=tutorias" class="opcion">
                Ver tutorías pendientes
            </a>
            
            <a href="index.php?action=logout" class="opcion logout">
                Cerrar sesión
            </a>
        </div>
    </div>
</body>
</html>