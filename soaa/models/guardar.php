<?php
include 'conexion.php';
$cedula= $_POST['CED_EST'];
$nombre= $_POST['NOM_EST'];
$apellido= $_POST['APE_EST'];
$direccion= $_POST['DIR_EST'];
$telefono= $_POST['TEL_EST'];
$sqlInsert= "insert into estudiantes values('$cedula', '$nombre', '$apellido', '$direccion', '$telefono')";
if($conn->query($sqlInsert)==TRUE)
{
    echo json_encode("se inserto el estudiante");
}
else{
    echo json_encode("no se inserto el estudiante".$sqlInsert.$mysqli->error);
}
//$conn->close();


?>