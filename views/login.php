<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tutorías UDB</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>



    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=procesar_login">
            <input type="text" name="carnet" placeholder="Tu carnet (ej: mg273678)" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        
        <div class="registro-link">
            <a href="index.php?action=registro">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </div>
</body>
</html>