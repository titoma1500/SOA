<?php
require_once "models/soa_model.php";

class SoaController {
    
    public static function soaApiController() {
        $opc = $_SERVER['REQUEST_METHOD'];
        
        switch($opc){
            case 'GET':
                if (isset($_GET['search']) && $_GET['search'] === 'cedula') {
                    $response = SoaModel::searchStudentByCedula();
                } else {
                    $response = SoaModel::selectStudent();
                }
                break;
            case 'POST':
                $response = SoaModel::insertStudent();
                break;
            case 'PUT':
                $response = SoaModel::updateStudent();
                break;
            case 'DELETE':
                $response = SoaModel::deleteStudent();
                break;
            default:
                $response = json_encode(["error" => "Método no permitido"]);
                break;
        }
        
        header('Content-Type: application/json');
        echo $response;
    }
}
?>