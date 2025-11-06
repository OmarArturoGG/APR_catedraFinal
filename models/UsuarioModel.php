<?php
include_once 'db/conexion.php';

class UsuarioModel {
    
    public function login($carnet, $password) {
        $conexion = new Conexion();
        $db = $conexion->conectar();
        
        $sql = "SELECT * FROM usuarios WHERE carnet = '$carnet'";
        $resultado = $db->query($sql);
        
        if ($resultado->rowCount() > 0) {
            $usuario = $resultado->fetch();
            
            
            // aqui para verificar la contra chavo
            if (password_verify($password, $usuario['password'])) {
                return $usuario;
            }
        }
        
        return false;
    }
    



    //funcion para registrar
    public function registrar($carnet, $email, $password, $tipo, $nombre) {
        $conexion = new Conexion();
        $db = $conexion->conectar();
        



        // para validar correo de la udb/institucional
        $correo_correcto = $carnet . "@alumno.udb.edu.sv";
        if ($email != $correo_correcto) {
            return "Error: El correo debe ser " . $correo_correcto;
        }
        



        // Verificar si ya existe
        $sql_check = "SELECT id FROM usuarios WHERE carnet = '$carnet'";
        if ($db->query($sql_check)->rowCount() > 0) {
            return "Error: El usuario ya existe";
        }
        


        // Encriptar la contra
        $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
        
        



        $sql = "INSERT INTO usuarios (carnet, email, password, tipo, nombre) 
                VALUES ('$carnet', '$email', '$password_encriptada', '$tipo', '$nombre')";
        
        if ($db->exec($sql)) {
            return true;
        } else {
            return "Error al registrar";
        }
    }
}
?>