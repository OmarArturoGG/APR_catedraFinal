<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tutorías UDB</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Crear Cuenta de Alumno</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=procesar_registro">
            <input type="text" name="carnet" placeholder="Tu carnet (ej: mg273678)" required>
            <input type="email" name="email" placeholder="Correo institucional" required>
            <input type="text" name="nombre" placeholder="Tu nombre completo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            
           
            <input type="hidden" name="tipo" value="alumno">
            
            <button type="submit">Registrarse</button>
        </form>
        
        <div class="registro-link">
            <a href="index.php">¿Ya tienes cuenta? Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>