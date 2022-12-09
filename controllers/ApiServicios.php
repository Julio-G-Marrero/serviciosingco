<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class ApiServicios {
    public static function index(Router $router){
        header('Access-Control-Allow-Origin: *');
        $prouectos = Proyecto::all();
        echo json_encode($prouectos);

    }
    public static function proyecto(Router $router){
        header('Access-Control-Allow-Origin: *');
        $id = $_POST['id'];
        $prouectos = Proyecto::where('id',$id);
        echo json_encode($prouectos);

    }
}
