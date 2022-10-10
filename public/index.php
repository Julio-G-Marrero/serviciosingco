<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\LoginController;
use Controllers\DashboardContoller;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

//Login
$router->get('/',[LoginController::class, 'login']);
$router->post('/',[LoginController::class, 'login']);
$router->get('/logout',[LoginController::class, 'logout']);

//Crear cuenta
$router->get('/crear',[LoginController::class, 'crear']);
$router->post('/crear',[LoginController::class, 'crear']);

//Recuperar Password
$router->get('/olvide',[LoginController::class, 'olvide']);
$router->post('/olvide',[LoginController::class, 'olvide']);

//Colocar nueva Password
$router->get('/restablecer',[LoginController::class, 'restablecer']);
$router->post('/restablecer',[LoginController::class, 'restablecer']);

//Confirmar Cuenta
$router->get('/mensaje',[LoginController::class, 'mensaje']);
$router->get('/confirmar',[LoginController::class, 'confirmar']);

//zona de proyectos
$router->get('/dashboard',[DashboardContoller::class,'index']);
$router->get('/crear-proyecto',[DashboardContoller::class,'crear_proyecto']);
$router->post('/crear-proyecto',[DashboardContoller::class,'crear_proyecto']);
$router->get('/proyecto',[DashboardContoller::class,'proyecto']);
$router->get('/perfil',[DashboardContoller::class,'perfil']);
$router->get('/cambiar-password',[DashboardContoller::class,'cambiar_password']);
$router->post('/cambiar-password',[DashboardContoller::class,'cambiar_password']);
$router->post('/perfil',[DashboardContoller::class,'perfil']);

//API para las tareas
$router->get('/api/tareas',[TareaController::class,'index']);
$router->post('/api/tarea',[TareaController::class,'crear']);
$router->post('/api/tarea/actualizar',[TareaController::class,'actualizar']);
$router->post('/api/tarea/eliminar',[TareaController::class,'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();