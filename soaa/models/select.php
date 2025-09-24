<?php
include 'conexion.php';
$sql = "select * from estudiantes";
$respuesta = $conn->query($sql);
$resultado = array();
if ($respuesta->num_rows >0) 
{
    while($fila = $respuesta->fetch_assoc())
    {
        array_push($resultado,$fila);
    } 
}
 else {
        $resultado = "No hay estudiantes";
    }
    print_r (json_encode($resultado));


?>