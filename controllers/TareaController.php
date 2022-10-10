<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index() {
        session_start();
        isAuth();
        $proyectoId = $_GET['id'];
        if(!$proyectoId) {
            header('Location: /dashboard');
        }

        $proyecto = Proyecto::where('url', $proyectoId);
        if(!$proyecto || $proyecto->propetarioId !== $_SESSION['id']) {
            header('Location: /404');
        }

        $tareas = Tarea::belongsTo('proyectoId',$proyecto->id);
        echo json_encode(['tareas' => $tareas]);
    }
    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            $proyectoid = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url',$proyectoid);

            if(!$proyecto || $proyecto->propetarioId !== $_SESSION['id']) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($repuesta);
                return;
            }
            //Todo bien instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }
    public static function actualizar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            //Validar que el proyecto exista
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            if(!$proyecto || $proyecto->propetarioId !== $_SESSION['id']) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($repuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Actualizado Correctamente'

                ];
                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    }
    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            //Validar que el proyecto exista
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            if(!$proyecto || $proyecto->propetarioId !== $_SESSION['id']) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($repuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' => 'exito'

            ];
            echo json_encode($resultado);

        } 
    }
}