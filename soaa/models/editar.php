<?php
include 'conexion.php';
//session_start();
$cedula = $_POST['CED_EST'];
$nombre = $_POST['NOM_EST'];
$apellido = $_POST['APE_EST'];
$direccion = $_POST['DIR_EST'];
$telefono = $_POST['TEL_EST'];
$sqlActualizar="update estudiantes set NOM_EST = '$nombre', 
                                       APE_EST = '$apellido',
                                       DIR_EST = '$direccion',
                                       TEL_EST = '$telefono'
                                       where CED_EST='$cedula'";
if($conn->query($sqlActualizar)==TRUE)
{
    echo json_encode("se actualizo el estudiante");
}
else
{
    echo json_encode("no se actualizo el estudiante".$sqlActualizar.$mysqli->error);
}
$conn->close();
?>