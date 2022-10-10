<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //Verificar que el usuario existe
                $usuario = Usuario::where('email',$auth->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error','El usuario no existe O no esta confirmado');
                }else {
                    //El usuario existe y esta confirmado
                    if(password_verify($_POST['password'], $usuario->password)) {
                        //Inicar sesion
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        //Redireccionar
                        header('Location: /dashboard');

                    }else {
                        Usuario::setAlerta('error','Password Incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('location:/');
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                if($existeUsuario) {
                    Usuario::setAlerta('error','El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear Password
                    $usuario->hassPassword();

                    //Eliminar password2
                    unset($usuario->password2);

                    //Generar el Token
                    $usuario->crearToken();

                    //Crear Usuario
                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('location: /mensaje');
                    }

                }
            }
        }

        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    
    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->valdiarEmail();

            if(empty($alertas)) {
                //Buscar usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado){
                    //generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir una alerta
                    Usuario::setAlerta('exito','Te enviamos las instrucciones a tu Email');
                }else { 
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //Render a la vista
        $router->render('auth/olvide',[
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router){
        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token) header('Location: /');

        //Identificar el ususario con ese Token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error','Token no valido');
            $mostrar = false;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Añadir nuevo password
            $usuario->sincronizar($_POST);

            //Validar password
            $alertas =$usuario->validarPassword();
            if(empty($alertas)) {
                //Hasear Password
                $usuario->hassPassword();
                //Eliminar token
                $usuario->token = null;
                //Guardar el usuario
                $resultado = $usuario->guardar();
                //Redireccionar 
                if($resultado) {
                    header('Location: /');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/restablecer',[
            'titulo' => 'Restablecer mi Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){
        //Render a la vista
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta Creada Exitosamente'

        ]);    
    }

    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        if(!$token) {
            header('Location: /');
        }

        //Encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //No se encontro ningun Usuario
            Usuario::setAlerta('error','Token no valido');
        }else {
            //Verificar usuario
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = null;

            //Guardar usuario
            $usuario->guardar();

            Usuario::setAlerta('exito','Cuenta Comprobada Exitosamente');

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }

}