<?php
class EnlacesPaginas{
public static function EnlacesPaginasModel($enlacesmodel)
{
    if($enlacesmodel =="nosotros" || 
       $enlacesmodel == "servicios" ||
       $enlacesmodel == "contactanos" ){
        $module = "views/".$enlacesmodel.".php";
       }
    else{
        $module= "views/inicio.php";
    }
    return $module;
}

}



?>