<?php
class SoaConexion {
    public function conectar() {
        $server = "localhost";
        $user = "root";
        $password = "";
        $database = "soa";
        try {
            $conn = new PDO("mysql:host=$server; dbname=$database", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        return $conn;
    }
}
?>