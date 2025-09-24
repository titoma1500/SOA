<?php
include 'conexion.php';
//session_start();
$cedula = $_POST['CED_EST'];
$sqlBorrar="delete from estudiantes where CED_EST='$cedula'";
if($conn->query($sqlBorrar)==TRUE)
{
    echo json_encode("se elimino el estudiante");
}
else
{
    echo json_encode("no se elimino el estudiante".$sqlBorrar.$mysqli->error);
}
$conn->close();
?>