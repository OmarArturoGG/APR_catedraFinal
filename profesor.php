<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['usuario']['tipo'] != 'profesor') {
    header('Location: index.php');
    exit;
}

$pagina = 'inicio';
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
}

if ($pagina == 'inicio') {
    include 'views/profesor/inicio.php';
} elseif ($pagina == 'horarios') {
    include 'views/profesor/horarios.php';
} elseif ($pagina == 'tutorias') {
    include 'views/profesor/tutorias.php';
} else {
    include 'views/profesor/inicio.php';
}
?>