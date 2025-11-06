<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Alumno</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="menu-alumno">
        <div class="bienvenida">
            <h2>¡Bienvenido, <?php echo $_SESSION['usuario']['nombre']; ?>!</h2>
            <p>Carnet: <?php echo $_SESSION['usuario']['carnet']; ?></p>
        </div>

        <div class="opciones">
            <a href="alumno.php?pagina=reservar" class="opcion">
                📅 Reservar una tutoría
            </a>

            <a href="alumno.php?pagina=pendientes" class="opcion">
                📋 Ver mis tutorías pendientes
            </a>

            <a href="alumno.php?pagina=historial" class="opcion">
                📊 Mi historial completo
            </a>

            <a href="index.php?action=logout" class="opcion logout">
                🚪 Cerrar sesión
            </a>
        </div>
    </div>
</body>

</html>