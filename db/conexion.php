<?php
class Conexion {


    public function conectar() {
        $servidor = "localhost";
        $usuario = "root";
        $password = "123456";
        $base_datos = "tutorias_udb";

        
        try {
            $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos", $usuario, $password);
            return $conexion;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}
?>