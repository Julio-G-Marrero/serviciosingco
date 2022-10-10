<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardContoller {
    public static function index(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propetarioId',$id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'alertas' => $alertas,
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            //Valicadion 
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //Generar una url Unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar el creador del proyecto
                $proyecto->propetarioId = $_SESSION['id'];

                //Guardar Proyecto  
                $resultado =$proyecto->guardar();

                //Redireccionar
                if($resultado) {
                    header('Location: /proyecto?id=' . $proyecto->url);
                }
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        $token = $_GET['id'];

        if(!$token) header('location: /dashboard');
        //Revisar que la persona que visita el proyecto es el que lo creo
        $proyecto = Proyecto::where('url',$token);
        if($proyecto->propetarioId !== $_SESSION['id']) {
            header('location: /dashboard');
        }
    
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email',$usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //Mensaje ya exixste ussuario
                    Usuario::setAlerta('error','Email no valido, ya pertenece a otra cuenta');

                }else {
                    //Guardar Registro
                    $usuario->guardar();
    
                    //Reescribir datos en sesion
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
    
                    Usuario::setAlerta('exito','Guardado correctamente');
                }
            }
        }   
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas =[];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            //Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();
            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    //Asingar nuevo Password
                    $usuario->password = $usuario->passwordNuevo;

                    // //Eliminar propediades
                    unset($usuario->passwordActual);
                    unset($usuario->passwordNuevo);

                    //hasear password
                    $usuario->hassPassword();

                    //Actualizar
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito','Password Guardado Correctamente');

                    }
                }else {
                    Usuario::setAlerta('error','Password Incorrecto');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiarPassword',[
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas,

        ]);
    }
}