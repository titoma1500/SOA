<?php
require_once 'soa_conexion.php';

class SoaModel {
    
    public static function selectStudent() {
        try {
            $objetoConexion = new SoaConexion();
            $conectar = $objetoConexion->conectar();
            $sqlSelect = "SELECT * FROM estudiantes";
            $resultado = $conectar->prepare($sqlSelect);
            $resultado->execute();
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($data);
        } catch(Exception $e) {
            return json_encode(["error" => "Error al obtener estudiantes: " . $e->getMessage()]);
        }
    }

    public static function searchStudentByCedula() {
        try {
            if (!isset($_GET['cedula'])) {
                return json_encode(["error" => "Cédula no proporcionada para búsqueda"]);
            }
            
            $objetoConexion = new SoaConexion();
            $conectar = $objetoConexion->conectar();
            $cedula = $_GET['cedula'];
            $sqlSearch = "SELECT * FROM estudiantes WHERE cedula = ?";
            $resultado = $conectar->prepare($sqlSearch);
            $resultado->execute([$cedula]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($data)) {
                return json_encode(["error" => "No se encontró estudiante con esa cédula"]);
            }
            
            return json_encode($data);
        } catch(Exception $e) {
            return json_encode(["error" => "Error al buscar estudiante: " . $e->getMessage()]);
        }
    }

    public static function deleteStudent() {
        try {
            if (!isset($_GET['cedula'])) {
                return json_encode(["error" => "Cédula no proporcionada"]);
            }
            
            $objetoConexion = new SoaConexion();
            $conectar = $objetoConexion->conectar();
            $cedula = $_GET['cedula'];
            $sqlDelete = "DELETE FROM estudiantes WHERE cedula = ?";
            $resultado = $conectar->prepare($sqlDelete);
            $resultado->execute([$cedula]);
            
            if ($resultado->rowCount() > 0) {
                $data = "Estudiante eliminado correctamente";
            } else {
                $data = "No se encontró el estudiante con esa cédula";
            }
            return json_encode($data);
        } catch(Exception $e) {
            return json_encode(["error" => "Error al eliminar estudiante: " . $e->getMessage()]);
        }
    }

    public static function insertStudent() {
        try {
            if (!isset($_POST['cedula']) || !isset($_POST['nombre']) || !isset($_POST['apellido']) || 
                !isset($_POST['direccion']) || !isset($_POST['telefono'])) {
                return json_encode(["error" => "Faltan datos obligatorios"]);
            }
            
            $objetoConexion = new SoaConexion();
            $conectar = $objetoConexion->conectar();
            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];
            
            $sqlInsert = "INSERT INTO estudiantes (cedula, nombre, apellido, direccion, telefono) VALUES (?, ?, ?, ?, ?)";
            $resultado = $conectar->prepare($sqlInsert);
            $resultado->execute([$cedula, $nombre, $apellido, $direccion, $telefono]);
            $data = "Estudiante insertado correctamente";
            return json_encode($data);
        } catch(Exception $e) {
            if ($e->getCode() == 23000) {
                return json_encode(["error" => "La cédula ya existe en el sistema"]);
            }
            return json_encode(["error" => "Error al insertar estudiante: " . $e->getMessage()]);
        }
    }

    public static function updateStudent() {
        try {
            if (!isset($_GET['cedula']) || !isset($_GET['nombre']) || !isset($_GET['apellido']) || 
                !isset($_GET['direccion']) || !isset($_GET['telefono'])) {
                return json_encode(["error" => "Faltan datos obligatorios para actualizar"]);
            }
            
            $objetoConexion = new SoaConexion();
            $conectar = $objetoConexion->conectar();
            $cedula = $_GET['cedula'];
            $nombre = $_GET['nombre'];
            $apellido = $_GET['apellido'];
            $direccion = $_GET['direccion'];
            $telefono = $_GET['telefono'];
            
            $sqlUpdate = "UPDATE estudiantes SET nombre=?, apellido=?, direccion=?, telefono=? WHERE cedula=?";
            $resultado = $conectar->prepare($sqlUpdate);
            $resultado->execute([$nombre, $apellido, $direccion, $telefono, $cedula]);
            
            if ($resultado->rowCount() > 0) {
                $data = "Estudiante actualizado correctamente";
            } else {
                $data = "No se encontró el estudiante con esa cédula o no hubo cambios";
            }
            return json_encode($data);
        } catch(Exception $e) {
            return json_encode(["error" => "Error al actualizar estudiante: " . $e->getMessage()]);
        }
    }
}
?>