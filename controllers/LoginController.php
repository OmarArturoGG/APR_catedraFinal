<?php
include_once 'models/UsuarioModel.php';

class LoginController {
    
    public function mostrarLogin() {
        include 'views/login.php';
    }
    




    public function procesarLogin() {
        if ($_POST) {
            $carnet = $_POST['carnet'];
            $password = $_POST['password'];
            
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->login($carnet, $password);
            
            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                



                
                if ($usuario['tipo'] == 'alumno') {
                    header('Location: alumno.php');
                } else {
                    header('Location: profesor.php');
                }
            } else {
                $error = "Carnet o contraseña incorrectos";
                include 'views/login.php';
            }
        }
    }

    



    
    public function mostrarRegistro() {
        include 'views/registro.php';
    }
    
public function procesarRegistro() {
    if ($_POST) {
        $carnet = $_POST['carnet'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $tipo = 'alumno'; // Siempre será alumno
        $nombre = $_POST['nombre'];
        
        $usuarioModel = new UsuarioModel();
        $resultado = $usuarioModel->registrar($carnet, $email, $password, $tipo, $nombre);
        
        if ($resultado === true) {
            header('Location: index.php?registro=exitoso');
        } else {
            $error = $resultado;
            include 'views/registro.php';
        }
    }
}
}
?>