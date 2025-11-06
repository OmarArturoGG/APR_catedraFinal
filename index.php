<?php
include_once 'controllers/LoginController.php';

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'mostrarLogin';

$controller = new LoginController();

switch ($action) {
    case 'mostrarLogin':
        $controller->mostrarLogin();
        break;
    case 'procesar_login':
        $controller->procesarLogin();
        break;
    case 'registro':
        $controller->mostrarRegistro();
        break;
    case 'procesar_registro':
        $controller->procesarRegistro();
        break;
    default:
        $controller->mostrarLogin();
        break;
}
?>